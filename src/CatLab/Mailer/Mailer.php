<?php

namespace CatLab\Mailer;

use CatLab\Mailer\Collections\ServiceCollection;
use CatLab\Mailer\Exceptions\MailException;
use CatLab\Mailer\Exceptions\NoServices;
use CatLab\Mailer\Models\Mail;
use CatLab\Mailer\Services\Service;
use Neuron\Config;

/**
 * Class Mailer
 * @package CatLab\Mailer
 */
class Mailer
{
	/** @var self */
	static $instance;

	/**
	 * @var ServiceCollection
	 */
	private $services;

	/**
	 *
	 */
	public function __construct ()
	{
		$this->services = new ServiceCollection ();
	}

	/**
	 * Return mailer (with services) from config.
	 * @return Mailer
	 */
	public static function fromConfig()
	{
		$mailer = self::getInstance();

		$services = Config::get ('mailer.services');
		if (!$services) {
			return $mailer;
		}

		foreach ($services as $k => $v) {
			$service = MapperFactory::getServiceMapper()->getFromToken($k);
			if ($service) {
				$service->setFromConfig ($v);
				$mailer->addService ($service);
			}
		}

		return $mailer;
	}

	/**
	 * @return Mailer
	 */
	public static function getInstance()
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
	public function addService(Service $service)
	{
		$this->services[] = $service;
		return $this;
	}

	/**
	 * @return ServiceCollection
	 */
	public function getServices()
	{
		return $this->services;
	}

	/**
	 * @param Mail $mail
	 * @throws MailException
	 */
	public function send(Mail $mail)
	{
		if (count ($this->getServices()) === 0)
			throw new NoServices ();

		foreach ($this->getServices() as $service) {
			if ($service->send ($mail)) {
				return;
			}
		}
	}
}