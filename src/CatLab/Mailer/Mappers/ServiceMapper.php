<?php

namespace CatLab\Mailer\Mappers;

use CatLab\Mailer\Services\Mandrill;
use CatLab\Mailer\Services\Service;
use CatLab\Mailer\Services\SMTP;

/**
 * Class ServiceMapper
 * @package CatLab\Mailer\Mappers
 */
class ServiceMapper
{
    const TOKEN_MANDRILL = 'mandrill';
    const TOKEN_SMTP = 'smtp';

    /**
     * @param $token
     * @return Service|null
     */
    public function getFromToken($token)
    {
        switch ($token) {
            case self::TOKEN_MANDRILL:
                return new Mandrill();

            case self::TOKEN_SMTP:
                return new SMTP();
        }
        return null;
    }

}