<?php

namespace CatLab\Mailer;

use Neuron\Router;

/**
 * Class Module
 * @package CatLab\Mailer
 */
class Module
	implements \Neuron\Interfaces\Module {

	/**
	 * @var Mailer
	 */
	private $mailer;

	public function __construct (Mailer $mailer = null)
	{
		if (!isset ($mailer)) {
			$mailer = Mailer::fromConfig ();
		}

		$this->mailer = $mailer;
	}

	/**
	 * Set template paths, config vars, etc
	 * @param string $routepath The prefix that should be added to all route paths.
	 * @return void
	 */
	public function initialize ($routepath)
	{
		// Nothing to do.
	}

	/**
	 * Register the routes required for this module.
	 * @param Router $router
	 * @return void
	 */
	public function setRoutes (Router $router)
	{
		// Nothing to do.
	}
}