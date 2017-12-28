<?php namespace Leftaro\App\Controller;

use Zend\Diactoros\{Response, ServerRequest};

/**
 * Default controller
 */
class DefaultController extends BaseController
{
	/**
	 * Display the index view
	 *
	 * @param ServerRequest $request
	 * @return Response
	 */
	public function indexAction(ServerRequest $request) : Response
	{
		return $this->twig('default/index.twig', [
			'description' => 'Thanks for try Webtaro!',
		]);
	}
}