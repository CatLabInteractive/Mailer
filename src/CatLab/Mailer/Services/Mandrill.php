<?php

namespace CatLab\Mailer\Services;

use CatLab\Mailer\Exceptions\MailException;
use CatLab\Mailer\Models\Contact;
use CatLab\Mailer\Models\Mail;

/**
 * Class Mandrill
 * @package CatLab\Mailer\Services
 */
class Mandrill extends Service
{
	/**
	 * @var \Mandrill
	 */
	private $mandrill;

	/**
	 * @var mixed[]
	 */
	private $config;

	/**
	 *
	 */
	protected function initialize()
	{

	}

	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig(array $config)
	{
		$this->config = $config;
		$this->mandrill = new \Mandrill ($this->config['key']);
		return $this;
	}

	/**
	 * @param Contact $contact
	 * @return array
	 */
	private function getContact(Contact $contact)
	{
		return array (
			'email' => $contact->getEmail ()
		);
	}

	/**
	 * @param Mail $mail
	 * @return array
	 */
	private function getTo(Mail $mail)
	{
		$out = array ();

		foreach ($mail->getTo () as $to) {
			$out[] = $this->getContact ($to);
		}

		return $out;
	}

	/**
	 * @param Mail $mail
	 * @return array
	 */
	private function getImages(Mail $mail)
	{
		$out = array ();

		foreach ($mail->getImages () as $image) {
			$out[] = array (
				'type' => $image->getMimetype (),
				'name' => $image->getName (),
				'content' => base64_encode (file_get_contents ($image->getPath ()))
			);
		}

		return $out;
	}

	/**
	 * @param Mail $mail
	 * @throws MailException
	 */
	public function send(Mail $mail)
	{
		$body = $mail->getHtmlOrText();

		$message = array (
			'subject' => $mail->getSubject(),
			'to' => $this->getTo($mail),
			'images' => $this->getImages($mail)
		);

		if ($mail->isHTML()) {
			$message['html'] = $body;
		}

		if ($text = $mail->getText()) {
			$message['text'] = $text;
		}

		if ($mail->getFrom()) {
			$message['from_email'] = $mail->getFrom()->getEmail();

			if ($mail->getFrom()->getName()) {
				$message['from_name'] = $mail->getFrom()->getName();
			}
		} else {
			throw new MailException("Mandrill requires a FROM email address to be set.");
		}

		$headers = array();

		if ($mail->getReplyTo()) {
			$headers['Reply-To'] = $mail->getReplyTo()->getEmail();
		}

		if (count($headers) > 0) {
			$message['headers'] = $headers;
		}

		$result = $this->mandrill->messages->send ($message);

        return true;
	}
}
