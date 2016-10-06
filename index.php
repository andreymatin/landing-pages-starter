<?php
	// Get action parameter
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri_data = explode('/', $request_uri);
	
	// Action parameter for / 
	$action = $request_uri_data['1'];
	
	// Local full path
	$local_dir = dirname(__FILE__);

	/**
	 * Load Libs
	 */ 

	// PHP Mailer
	require_once $local_dir . '/lib/phpmailer/PHPMailerAutoload.php';
	
	// Mustache Template Engine
	require_once $local_dir . '/lib/mustache/src/Mustache/Autoloader.php';

	/**
	 * Modules
	 */ 

	// Contact Form
	require_once $local_dir . '/mod/email.php';
	
	// Templates
	require_once $local_dir . '/mod/mustache.php';