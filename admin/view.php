<?php
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	session_name('survais_wordpress');
	session_start();

	$survais_active_survai = html_entity_decode(get_option('survais_active_survai'));
	$survais_embed_code = html_entity_decode(get_option('survais_embed_code'));

	DEFINE('IS_LOCAL', (getenv("tmp") == '\xampp\tmp') ? true : false);

	if(IS_LOCAL){
		echo '<span style="font-size: 18px; font-weight: bold;">Local</span>';
		$survais_loginURL = "//survais.bootstrap/wp/wp-login.php";
		$survais_apiURL = "//survais.bootstrap/wp/wp-api.php";
	} else {
		$survais_loginURL = "https://www.survais.com/wp/wp-login.php";
		$survais_apiURL = "https://www.survais.com/wp/wp-api.php";
	}

?>

<div class="wrap survais-body">

	<script>
		var survais_loginURL = '<?php echo $survais_loginURL ?>';
		var survais_apiURL = '<?php echo $survais_apiURL ?>';
	</script>

	<?php if(!$_SESSION['survais_loggedIn']){ ?>

	<div class="login-forms login-form">
		<form action="#">
			<h3>Survais Login</h3>
			<label for="email">Email:</label><br>
			<input id="email" type="email" autocomplete="email"><br><br>

			<label for="password">Password:</label><br>
			<input id="password" type="password" autocomplete="current-password"><br><br>
		</form>
		<button id="signInBtn">Login</button>

		<br>
		<p>You can register over at <a href="https://www.survais.com/?ref=wp_plugin">www.survais.com</a></p>
	</div>

	<?php } else { ?>
		
		<script type="text/javascript">
			var survais_user = {
					survais_user: '<?php echo $_SESSION['survais_user']; ?>',
					survais_api: '<?php echo $_SESSION['survais_api']; ?>',
					survais_id: '<?php echo $_SESSION['survais_id']; ?>',
					survais_email: '<?php echo $_SESSION['survais_email']; ?>',
					survais_name: '<?php echo $_SESSION['survais_name']; ?>',
					survais_loggedIn: '<?php echo $_SESSION['survais_loggedIn']; ?>'
				};

			jQuery(document).ready(function(){
				survais_getDataForUser(survais_user);
			});
			
			var survais_active_survai = '<?php echo $survais_active_survai; ?>';
			// var survais_embed_code = '<?php //echo $survais_embed_code; ?>';
		</script>
		
		<div class="user-bar">
			<a class="logout-link" href="#">Logout</a>
		</div>

		<div class="loading-view">
			<div class="loader"></div>
		</div>

		<div class="survais-view">

			<p>The Survais WordPress Plugin allows you to easily enable Survais across all your pages. Create your Survais over at <a href="https://www.survais.com/app" target="_blank">survais.com/app</a>, and they will appear in the list below.</p>

			<p><a href="https://www.survais.com/app" target="_blank">Manage your Survais</a> - <a href="https://www.survais.com/app" target="_blank">View and analyse your Survais responses.</a></p>

			<div class="global-survais">
				<h2>Global Survais</h2>
			</div>

			<div class="single-survais">
				<h2>Single Survais</h2>
			</div>

			<footer>
				<a href="mailto:shane@survais.com?subject=Survais - Wordpress Support" target="_blank">Support</a>
				- <a href="https://www.survais.com/" target="_blank">www.survais.com</a>
				- &copy; <?php echo Date('Y');?>
			</footer>

		</div>

	<?php } ?>
</div>