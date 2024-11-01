<?php
/*
Plugin Name: Survais WordPress Plugin
Plugin URI: https://www.survais.com
Description: Enable or disable Survais via the Survais WordPress plugin. Sign up at <a href="https://www.survais.com" target="_blank">www.survais.com</a>
Version: 1.0.1
Author: <a href="https://www.survais.com">Survais</a>
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action('wp_logout', 'endSurvaiSession');
add_action('wp_login', 'endSurvaiSession');

function endSurvaiSession() {
	session_name('survais_wordpress');
	session_start();
	session_destroy();
}

function survais_options_page(){
    add_menu_page(
        'Survais',
        'Survais',
        'manage_options',
        plugin_dir_path(__FILE__) . 'admin/view.php',
        null,
        plugin_dir_url(__FILE__) . 'images/survais.png'
    );
}
add_action('admin_menu', 'survais_options_page');


function survais_enqueue($hook) {
	//only for our special plugin admin page
	if('survais/admin/view.php' != $hook){
		return;
	}
	wp_register_style('survais-wp', plugins_url('css/survais.css', __FILE__));
	wp_enqueue_style('survais-wp');
	wp_enqueue_script('jquery');
	wp_enqueue_script('survais-wp-js', plugins_url('js/survais.js', __FILE__), array('jquery'));
	wp_localize_script('survais-wp-js', 'survaisPlugin', array(
		'pluginsUrl' => plugins_url('', __FILE__),
	));
}
add_action( 'admin_enqueue_scripts', 'survais_enqueue' );


function survais_activated(){
	$option = get_option('survais_options');
	if(empty($option)){
		$survais_options = [];
		$survais_options['active_survai'] = null;
		$survais_options['embed_code'] = null;
		add_option('survais_options', $survais_options);
	}
}
register_activation_hook( __FILE__, 'survais_activated' );

function survais_save_options(){
	$status = '';
	if(isset($_POST['survai_identifier']) && isset($_POST['survai_embed_code'])){
		$survai_identifier = htmlspecialchars($_POST['survai_identifier']);
		$survai_embed_code = htmlspecialchars($_POST['survai_embed_code']);
	    update_option('survais_active_survai', htmlentities(stripslashes($survai_identifier)));
	    update_option('survais_embed_code', htmlentities(stripslashes($survai_embed_code)));
	    $status = 'updated';
	} else {
		$status = 'failed_to_update';
	}
	echo $status;
	die();
}
add_action( 'wp_ajax_survais_save_options', 'survais_save_options' );


function survais_insert_embed_code(){
	$survais_embed_code = html_entity_decode(get_option('survais_embed_code'));
	if(isset($survais_embed_code) && !empty($survais_embed_code)){
		echo htmlspecialchars_decode($survais_embed_code);
	}
}
add_action('wp_footer', 'survais_insert_embed_code');