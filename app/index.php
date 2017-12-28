<?php
declare(strict_types = 1);
require __DIR__ . '/bootstrap.php';

// Future me: please improve this ugly af check
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|map|woff2|woff|ttf|ico)$/', $_SERVER["REQUEST_URI"]))
{
	return false;
}

$container = require __DIR__ . '/../config/local/container.php';

$application = new \Leftaro\App\Application($container);

$application->run(\Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
));