<?php

namespace CatLab\Mailer\Models;

use Neuron\Core\Template;

class Mail {

	/**
	 * @var string
	 */
	private $subject;

	/**
	 * @var string
	 */
	private $body;

	/**
	 * @var string
	 */
	private $text;

	/**
	 * @var Template
	 */
	private $template;

	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param string $subject
	 * @return self
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 * @param string $body
	 * @return self
	 */
	public function setBody($body)
	{
		$this->body = $body;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @param string $text
	 * @return self
	 */
	public function setText($text)
	{
		$this->text = $text;
		return $this;
	}

	/**
	 * @return Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @param Template $template
	 * @return self
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
		return $this;
	}

}