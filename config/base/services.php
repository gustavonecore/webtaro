<?php

use DI\Container;
use FastRoute\Dispatcher;
use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Noodlehaus\Config;
use Leftaro\App\Twig\AppExtension;
use Leftaro\App\Session;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

return [

	// Auto-wiring
	Config::class => function (ContainerInterface $container)
	{
		return $container->get('config');
	},

	Logger::class => function (ContainerInterface $container)
	{
		$log = new Logger('leftaro');
		$log->pushHandler(new StreamHandler($container->get('config')->get('paths.logfile'), Logger::DEBUG));
		return $log;
	},

	LoggerInterface::class => function (ContainerInterface $container)
	{
		return $container->get(Logger::class);
	},

	Dispatcher::class => function (ContainerInterface $container)
	{
		return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($container)
		{
			foreach (require_once __DIR__ . '/routes.php' as $route)
			{
				list($method, $endpoint, $handlerClass, $handlerMethod) = $route;

				$r->addRoute(strtoupper($method), $endpoint, $handlerClass . '::' . $handlerMethod);
			}
		});
	},

	Container::class => function(ContainerInterface $container)
	{
		return $container;
	},

	Session::class => function(ContainerInterface $container)
	{
		return (session_status() === PHP_SESSION_NONE) ? Session::init() : $container->get(Session::class);
	},

	// Helper and aliases
	'config' => function ()
	{
        return new Config(__DIR__ . '/settings.php');
	},

	'logger' => function (ContainerInterface $container)
	{
		return $container->get(Logger::class);
	},

	'twig' => function (ContainerInterface $container)
	{
		$loader = new Twig_Loader_Filesystem($container->get('config')->get('paths.views'));

		$twig = new Twig_Environment($loader,
		[
			//'cache' => $container->get('config')->get('paths.views_cache'),
		]);

		$twig->addExtension(new AppExtension($container));

		return $twig;
	},

	'dispatcher' => function(ContainerInterface $container)
	{
		return $container->get(Dispatcher::class);
	},

	'session' => function(ContainerInterface $container)
	{
		return $container->get(Session::class);
	},
];