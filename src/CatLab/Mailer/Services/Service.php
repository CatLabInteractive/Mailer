<?php

namespace CatLab\Mailer\Services;

abstract class Service {

	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig (array $config)
	{
		return $this;
	}

}