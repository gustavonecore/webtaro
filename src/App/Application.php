<?php namespace Leftaro\App;

use Exception;
use Leftaro\Core\Application as LeftaroApplication;
use Leftaro\Core\Exception\NotFoundException;
use Leftaro\App\Exception\ApiException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Zend\Diactoros\Response\{
	JsonResponse,
	TextResponse,
	HtmlResponse,
	RedirectResponse
};

class Application extends LeftaroApplication
{
	/**
	 * @var bool  Flag to determine if the main application was properly loaded
	 */
	protected $autoLoad;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(ContainerInterface $container)
	{
		parent::__construct($container);

		$this->autoLoad = true;
	}


	/**
	 * Override error handling method
	 *
     * {@inheritDoc}
     */
    protected function handleException(Exception $e, RequestInterface $request) : ResponseInterface
    {
		if ($e instanceof NotFoundException)
		{
			if ($e->getRequest()->getAttribute('is_ajax') === true)
			{
				return new JsonResponse([
					'error' => 'Resource not found',
					'description' => 'The requested resource ' . $e->getRequest()->getUri()->getPAth() . ' was not found',
				], 404);
			}

			return new HtmlResponse($this->container->get('twig')->render('error/404.twig', [
				'title' => 'Page not found',
				'description' => 'The requested page "' . $e->getRequest()->getUri()->getPAth() . '" was not found',
			]), 404);
		}

		if ($e instanceof ApiException)
		{
			return new JsonResponse([
				'error' => $e->getMessage(),
				'code' => $e->getCode(),
			], $e->getHttpCode());
		}

        return parent::handleException($e, $request);
    }
}