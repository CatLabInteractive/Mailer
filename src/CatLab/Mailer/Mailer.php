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
     * @throws MailException
     */
	public static function fromConfig()
	{
		$mailer = self::getInstance();

		$services = Config::get ('mailer.services');

		if (!$services) {
			return $mailer;
		}

		foreach ($services as $k => $v) {

            if (isset($v['token'])) {
                $service = MapperFactory::getServiceMapper()->getFromToken($v['token']);
            } elseif (is_string($k)) {
                $service = MapperFactory::getServiceMapper()->getFromToken($k);
            } else {
                throw new MailException("Could not initialize mailer: " . print_r($v, true));
            }

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
		if (count ($this->getServices()) === 0) {
            throw new NoServices ();
        }

        $error = null;
		foreach ($this->getServices() as $service) {
            try {
                if ($service->send($mail)) {
                    return;
                }
            } catch (MailException $e) {
                $error = $e;
                error_log($e->getMessage());
            }
		}

        if ($error) {
            throw $error;
        }
	}
}
