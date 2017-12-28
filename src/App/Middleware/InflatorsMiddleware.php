<?php namespace Leftaro\App\Middleware;

use Gcore\Sanitizer\Template\TemplateSanitizer;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;

class InflatorsMiddleware implements MiddlewareInterface
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
		$query = (new TemplateSanitizer(['inflators' => 'string']))->sanitize($request->getQueryParams());

		$query['inflators'] = ($query['inflators'] === null) ? '' : $query['inflators'];

		$inflators = array_flip(array_values(array_filter(explode(',', $query['inflators']))));

		$request = $request->withAttribute('inflators', $inflators);

		return $next($request, $response);
	}
}