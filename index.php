<?php
	// Get action parameter
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri_data = explode("/",$_SERVER['REQUEST_URI']);
	$action = $request_uri_data['1'];
	$local_dir = dirname(__FILE__);

	/**
	 * Libs
	 */ 
	// PHP Mailer
	require $local_dir . '/lib/phpmailer/PHPMailerAutoload.php';
	
	// Mustache Template Engine
	require $local_dir . '/lib/mustache/src/Mustache/Autoloader.php';

	/**
	 * Modules
	 */ 

	// Contact Form
	require $local_dir . '/mod/email.php';
	
	// Templates
	require $local_dir . '/mod/mustache.php';