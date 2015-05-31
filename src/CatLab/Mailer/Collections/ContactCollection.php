<?php

namespace CatLab\Mailer\Collections;


use CatLab\Mailer\Models\Contact;
use Neuron\Collections\Collection;

class ContactCollection
	extends Collection {

	public final function __construct ()
	{
		$this->on ('add', array ($this, 'transformContact'));
	}

	/**
	 * Transform contact.
	 * @param $value
	 * @param $index
	 */
	public function transformContact ($value, $index)
	{
		if (! ($this[$index] instanceof Contact)) {
			$contact = Contact::fromMixed ($value);
			$this[$index] = $contact;
		}
	}

}