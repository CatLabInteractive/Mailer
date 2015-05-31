<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 31/05/15
 * Time: 11:09
 */

namespace CatLab\Mailer\Models;

class Contact {

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

	/**
	 * @return string
	 */
	public function getEmail ()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return self
	 */
	public function setEmail ($email)
	{
		$this->email = $email;
		return $this;
	}

}