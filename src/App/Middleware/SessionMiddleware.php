<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\Response\{
	RedirectResponse
};
use Zend\Diactoros\Uri;

class SessionMiddleware implements MiddlewareInterface
{
	const AUTHORIZED_PATHS = [
		'/auth' => true,
		'/auth/login' => true,
	];

	/**
	 * @var \Psr\Container\ContainerInterface  Container
	 */
	protected $container;

	/**
	 * Creates the middleware
	 *
	 * @param \Psr\Container\ContainerInterface   $contsainer Container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

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
		if (!$this->isApiRequest($request))
		{
			if (!isset(self::AUTHORIZED_PATHS[$request->getUri()->getPath()]))
			{
				if (!$this->container->get('session')->has('authenticated_user'))
				{
					return new RedirectResponse('/auth');
				}
			}
		}

		// TODO: check ACL permissions here

		return $next($request, $response);
	}

	/**
	 * Test if the given request it's an api call
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 *
	 * @return bool
	 */
	private function isApiRequest(RequestInterface $request) : bool
	{
		return (strpos($request->getUri()->getPath(), 'api') > 0) ? true : false;
	}
}