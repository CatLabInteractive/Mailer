<?php

namespace CatLab\Mailer\Exceptions;

class NoServices
	extends MailException {

	public function __construct () {
		parent::__construct ('No mail services have been defined.');
	}

}