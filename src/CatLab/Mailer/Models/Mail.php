<?php

namespace CatLab\Mailer\Models;

use CatLab\Mailer\Collections\ContactCollection;
use CatLab\Mailer\Collections\ImageCollection;
use Neuron\Core\Template;

/**
 * Class Mail
 * @package CatLab\Mailer\Models
 */
class Mail
{

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
	 * @var ContactCollection
	 */
	private $to;

	/**
	 * @var ContactCollection
	 */
	private $cc;

	/**
	 * @var ContactCollection
	 */
	private $bcc;

	/**
	 * @var Contact
	 */
	private $from;

	/**
	 * @var ImageCollection
	 */
	private $images;

	/**
	 * @var Contact
	 */
	private $replyTo;

	/**
	 * @var boolean
	 */
	private $isHTML;

	public function __construct()
	{
		$this->to = new ContactCollection ();
		$this->cc = new ContactCollection ();
		$this->bcc = new ContactCollection ();
		$this->images = new ImageCollection ();
	}

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
	 * Return the html body or the text string, and fill in the isHTML property
	 * @return string
	 */
	public function getHtmlOrText()
	{
		if ($template = $this->getTemplate ()) {
			if (!isset ($this->isHTML))
				$this->setIsHTML(true);
			return $template->parse ();
		} else if ($body = $this->getBody ()) {
			$this->setIsHTML(true);
			return $body;
		} else if ($text = $this->getText ()) {
			$this->setIsHTML(false);
			return $text;
		}

		$this->setIsHTML(false);
		return 'No body set.';
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

	/**
	 * @return ContactCollection
	 */
	public function getTo()
	{
		return $this->to;
	}

	/**
	 * @param ContactCollection $to
	 * @return self
	 */
	public function setTo(ContactCollection $to)
	{
		$this->to = $to;
		return $this;
	}

	/**
	 * @return ContactCollection
	 */
	public function getCc()
	{
		return $this->cc;
	}

	/**
	 * @param ContactCollection $cc
	 * @return self
	 */
	public function setCc(ContactCollection $cc)
	{
		$this->cc = $cc;
		return $this;
	}

	/**
	 * @return ContactCollection
	 */
	public function getBcc()
	{
		return $this->bcc;
	}

	/**
	 * @param ContactCollection $bcc
	 * @return self
	 */
	public function setBcc(ContactCollection $bcc)
	{
		$this->bcc = $bcc;
		return $this;
	}

	/**
	 * @return Contact
	 */
	public function getFrom()
	{
		return $this->from;
	}

	/**
	 * @param Contact $from
	 * @return self
	 */
	public function setFrom($from)
	{
		$from = Contact::fromMixed ($from);

		$this->from = $from;
		return $this;
	}

	/**
	 * @return ImageCollection
	 */
	public function getImages()
	{
		return $this->images;
	}

	/**
	 * @param ImageCollection $images
	 * @return self
	 */
	public function setImages($images)
	{
		$this->images = $images;
		return $this;
	}

	/**
	 * @return Contact
	 */
	public function getReplyTo()
	{
		return $this->replyTo;
	}

	/**
	 * @param Contact $replyTo
	 * @return self
	 */
	public function setReplyTo($replyTo)
	{
		$this->replyTo = $replyTo;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isHTML ()
	{
		return $this->isHTML;
	}

	/**
	 * @param boolean $isHTML
	 * @return self
	 */
	public function setIsHTML ($isHTML)
	{
		$this->isHTML = $isHTML;
		return $this;
	}
}