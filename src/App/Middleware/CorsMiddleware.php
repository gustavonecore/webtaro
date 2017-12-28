<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class CorsMiddleware implements MiddlewareInterface
{
	/**
	 * Handle the middleware call for request and response approach
	 *
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 * @param  \Psr\Http\Message\ResponseInterface   $response  Response instance
	 * @param  callable                              $next      Next callable Middleware
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		$requiredHeaders = $request->hasHeader('Access-Control-Request-Headers') ? $request->getHeader('Access-Control-Request-Headers')[0] : '*';

		$response = $response->withHeader('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
		$response = $response->withHeader('Access-Control-Allow-Headers', $requiredHeaders);
		$response = $response->withHeader('Access-Control-Allow-Origin', '*');

		return $next($request, $response);
	}
}