<?php
	session_name('survais_wordpress');
	session_start();
	
	$user = $_POST['user'];
	$_SESSION['survais_user'] = htmlspecialchars($user['identifier']);
	$_SESSION['survais_id'] = htmlspecialchars($user['id']);
	$_SESSION['survais_api'] = htmlspecialchars($user['api']);
	$_SESSION['survais_name'] = htmlspecialchars($user['name']);
	$_SESSION['survais_email'] = htmlspecialchars($user['email']);
	$_SESSION['survais_loggedIn'] = true;
