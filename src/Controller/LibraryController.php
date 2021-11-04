<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

class LibraryController extends AbstractController
{
	/*private $logger;

	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}*/


	/**
	 * @Route("/libraries", name="library.index")
	 */
	public function index(LoggerInterface $logger): Response
	{
		$logger->info('Index action called');

		return $this->json([
			'message' => 'Hola mundo'
		]);
	}
}