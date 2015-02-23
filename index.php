<?php
	// Get action parameter
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri_data = explode("/",$_SERVER['REQUEST_URI']);
	$action = $request_uri_data['1'];

	// Mustache Template Engine
	require 'lib/mustache/src/Mustache/Autoloader.php';
	Mustache_Autoloader::register();

	$options =  array('extension' => '.html');

	$m = new Mustache_Engine(array(
		'template_class_prefix' => '__MyTemplates_',
		'cache' => dirname(__FILE__).'/tmp/cache/mustache',
		'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
		'cache_lambda_templates' => true,
		'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views', $options),
		'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/views/partials'),
		'helpers' => array('i18n' => function($text) {
			// do something translatey here...
		}),
		'escape' => function($value) {
			return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
		},
		'charset' => 'ISO-8859-1',
		'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
		'strict_callables' => true,
		'extension' => 'html'
	));

	$tpl = $m->loadTemplate('home');
	echo $tpl->render(array(
		'title' => 'title',
		'current_year' => date('Y'),
	));
?>