<?php

\define('PSR_4', true);
\define('IS_DEV', \is_file(__DIR__ . '/dev.php'));
\define('PATH_APP', __DIR__ . '/app/');
\define('TEMPLATE', PATH_APP . 'Templates');
\define('APP_EVENT', PATH_APP . 'AppEvent.php');
\define('APP_KERNEL', PATH_APP . 'AppKernel.php');

include_once __DIR__ . '/vendor/autoload.php';

$env = ES\App\AuthApp::ENV_PROD;

if (IS_DEV) {
	$env = ES\App\AuthApp::ENV_DEV;
	include_once 'dev.php';
}

$application = (new ES\App\AuthApp())
	->setEnvironment($env)
	->setApplicationType('Auth');

$application->run();
$application->outputResponse();