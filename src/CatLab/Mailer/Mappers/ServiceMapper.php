<?php

namespace CatLab\Mailer\Mappers;

use CatLab\Mailer\Services\Mandrill;

class ServiceMapper {

	/**
	 * @param $token
	 * @return Mandrill|null
	 */
	public function getFromToken ($token)
	{
		switch ($token)
		{
			case 'mandrill':
				return new Mandrill ();
			break;
		}
		return null;
	}

}