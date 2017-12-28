<?php
return [
	'host' => 'http://0.0.0.0:8080/',
	'database' => [
		'dbname' => '',
		'user' => '',
		'password' => '',
		'host' => 'localhost',
		'driver' => 'pdo_mysql',
	],
	'paths' => [
		'logfile' => __DIR__ . '/../../log/leftaro.log',
		'views' => __DIR__ . '/../../resource/views/',
		'views_cache' => __DIR__ . '/../../resource/cache/',
		'uploads' => __DIR__ . '/../../app/uploads/',
	],
	'middlewares' => [
		'before' => [
			\Leftaro\Core\Middleware\RouteMiddleware::class,
			\Leftaro\App\Middleware\ContentTypeMiddleware::class,
			\Leftaro\App\Middleware\AuthMiddleware::class,
		],
		'after' => [
			\Leftaro\App\Middleware\CorsMiddleware::class,
			\Leftaro\App\Middleware\LoggerMiddleware::class,
		],
	],
];