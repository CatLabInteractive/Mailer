<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 31/05/15
 * Time: 10:15
 */

namespace CatLab\Mailer;

use CatLab\Mailer\Collections\ServiceCollection;
use CatLab\Mailer\Services\Service;

class Mailer {

	/** @var self */
	static $instance;

	/**
	 * @var ServiceCollection
	 */
	private $services;

	public function __construct ()
	{
		$this->services = new ServiceCollection ();
	}

	/**
	 * Return mailer (with services) from config.
	 * @return Mailer
	 */
	public static function fromConfig ()
	{
		$mailer = self::getInstance ();
		return $mailer;
	}

	/**
	 * @return Mailer
	 */
	public static function getInstance ()
	{
		if (!isset (self::$instance)) {
			self::$instance = new self ();
		}
		return self::$instance;
	}

	/**
	 * @param Service $service
	 * @return self
	 */
	public function addService (Service $service)
	{
		$this->services->add ($service);
		return $this;
	}

	/**
	 * @return ServiceCollection
	 */
	public function getServices ()
	{
		return $this->services;
	}
}