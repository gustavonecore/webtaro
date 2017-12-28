<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class ContentTypeMiddleware implements MiddlewareInterface
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
		if ($request->hasHeader('content-type') && ($request->getHeaderLine('content-type') === 'application/json' ||
			$request->getHeaderLine('content-type') === 'application/json; charset=utf-8'))
		{
			$input = json_decode($request->getBody(), true);
			$input = !is_array($input) ? [] : $input;

			$request = $request->withParsedBody($input);
			$request = $request->withAttribute('is_ajax', true);
		}

		return $next($request, $response);
	}
}