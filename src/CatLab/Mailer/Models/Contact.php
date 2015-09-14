<?php

namespace CatLab\Mailer\Models;

/**
 * Class Contact
 * @package CatLab\Mailer\Models
 */
class Contact
{
	/**
	 * @param mixed $email
	 * @return mixed
	 */
	public static function fromMixed ($email)
	{
		if ($email instanceof Contact) {
			return $email;
		}

		else {
			$value = new self ();
			$value->setEmail ($email);
			return $value;
		}
	}

	/** @var string */
	private $email;

	/** @var string */
	private $name;

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return self
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName ()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName ($name)
	{
		$this->name = $name;
		return $this;
	}
}