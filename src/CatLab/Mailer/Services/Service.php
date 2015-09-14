<?php

namespace CatLab\Mailer\Services;

use CatLab\Mailer\Models\Mail;

abstract class Service
{
	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig (array $config)
	{
		return $this;
	}

	/**
	 * Empty method to initialize external libraries and al lthat.
	 */
	protected function initialize () {

	}

	abstract function send (Mail $mail);
}