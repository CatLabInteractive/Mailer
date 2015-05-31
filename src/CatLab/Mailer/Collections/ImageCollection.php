<?php

namespace CatLab\Mailer\Collections;


use CatLab\Mailer\Models\Contact;
use CatLab\Mailer\Models\Image;
use Neuron\Collections\Collection;

class ImageCollection
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
		if (! ($this[$index] instanceof Image)) {
			$contact = Image::fromMixed ($value);
			$this[$index] = $contact;
		}
	}

}