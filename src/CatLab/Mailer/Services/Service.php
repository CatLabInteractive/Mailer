<?php

namespace CatLab\Mailer\Services;

use CatLab\Mailer\Models\Mail;

abstract class Service {

	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig (array $config)
	{
		return $this;
	}

	protected function getBodyHTML (Mail $mail) {

		if ($template = $mail->getTemplate ()) {
			return $template->parse ();
		}

		else if ($body = $mail->getBody ()) {
			return $body;
		}

		else if ($text = $mail->getText ()) {
			return $text;
		}

		return 'No body set.';
	}

	/**
	 * Empty method to initialize external libraries and al lthat.
	 */
	protected function initialize () {

	}

	abstract function send (Mail $mail);
}