<?php

	/**
	 * Template Engine
	 */
	Mustache_Autoloader::register();

	$options =  array('extension' => '.html');

	$m = new Mustache_Engine(array(
		'template_class_prefix' => '__mustache_',
		'cache' =>  $local_dir . '/tmp/cache/mustache',
		'cache_file_mode' => 0666, // Please, configure your umask instead of doing this :)
		'cache_lambda_templates' => true,
		'loader' => new Mustache_Loader_FilesystemLoader($local_dir . '/views', $options),
		'partials_loader' => new Mustache_Loader_FilesystemLoader($local_dir . '/views/partials'),
		'helpers' => array('i18n' => function($text) {
			// do something translatey here...
		}),
		'escape' => function($value) {
			return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
		},
		'charset' => 'UTF-8',
		'logger' => new Mustache_Logger_StreamLogger('php://stderr'),
		'strict_callables' => true,
		'extension' => 'html'
	));

	$tpl = $m->loadTemplate('home');

	echo $tpl->render(array(
		'current_year' => date('Y'),
		'contact_post' => $request_uri,
	));