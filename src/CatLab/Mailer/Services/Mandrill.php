<?php

namespace CatLab\Mailer\Services;
use CatLab\Mailer\Models\Contact;
use CatLab\Mailer\Models\Mail;

class Mandrill
	extends Service {

	/**
	 * @var \Mandrill
	 */
	private $mandrill;

	/**
	 * @var mixed[]
	 */
	private $config;

	protected function initialize ()
	{

	}

	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig (array $config)
	{
		$this->config = $config;
		$this->mandrill = new \Mandrill ($this->config['key']);
		return $this;
	}

	private function getContact (Contact $contact) {
		return array (
			'email' => $contact->getEmail ()
		);
	}

	private function getTo (Mail $mail) {
		$out = array ();

		foreach ($mail->getTo () as $to) {
			$out[] = $this->getContact ($to);
		}

		return $out;
	}

	public function send (Mail $mail)
	{
		$message = array (
			'html' => $this->getBodyHTML ($mail),
			'subject' => $mail->getSubject (),
			'to' => $this->getTo ($mail),
			'from_email' => $mail->getFrom ()->getEmail ()
		);

		$result = $this->mandrill->messages->send ($message);
	}
}