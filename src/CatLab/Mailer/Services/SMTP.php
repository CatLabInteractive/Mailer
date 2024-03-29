<?php

namespace CatLab\Mailer\Services;

use CatLab\Mailer\Exceptions\MailException;
use CatLab\Mailer\Models\Contact;
use CatLab\Mailer\Models\Image;
use CatLab\Mailer\Models\Mail;
use PHPMailer;
use phpmailerException;

/**
 * Class SNTP
 * @package CatLab\Mailer\Services
 */
class SMTP extends Service
{
	/**
	 * @var mixed[]
	 */
	private $config;

	/**
	 *
	 */
	protected function initialize()
	{

	}

	/**
	 * @param array $config
	 * @return self
	 */
	public function setFromConfig(array $config)
	{
        $this->config = $config;
	}

	/**
	 * @param Mail $sourceMail
	 * @throws MailException
	 */
	public function send(Mail $sourceMail)
	{

        try {
            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->CharSet = "UTF-8";
            $mail->isSMTP();                                      // Set mailer to use SMTP

            // force ipv4
            $mail->Host = gethostbyname($this->config['server']);  // Specify main and backup SMTP servers
            $mail->SMTPOptions = array('ssl' => array('verify_peer_name' => false));

            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->config['username'];                 // SMTP username
            $mail->Password = $this->config['password'];                           // SMTP password
            $mail->SMTPSecure = $this->config['security'];                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->config['port'];                                    // TCP port to connect to

            $mail->setFrom($sourceMail->getFrom()->getEmail(), $sourceMail->getFrom()->getName());

            foreach ($sourceMail->getTo() as $v) {
                $mail->addAddress($v->getEmail(), $v->getName());
            }

            foreach ($sourceMail->getCc() as $v) {
                $mail->addCC($v->getEmail(), $v->getName());
            }

            foreach ($sourceMail->getBcc() as $v) {
                $mail->addBCC($v->getEmail(), $v->getName());
            }

            foreach ($sourceMail->getImages() as $image) {
                /** @var Image $image */
                $mail->addAttachment($image->getPath(), $image->getName(), 'base64', $image->getMimeType());
            }

            if ($sourceMail->getReplyTo()) {
                $mail->addReplyTo($sourceMail->getReplyTo()->getEmail(), $sourceMail->getReplyTo()->getName());
            }

            $body = $sourceMail->getHtmlOrText();
            $subject = $sourceMail->getSubject();

            $mail->isHTML($sourceMail->isHTML());                                  // Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $body;

            if (!$mail->send()) {
                throw new MailException($mail->ErrorInfo);
            }

            return true;
        } catch (phpmailerException $e) {
            throw new MailException($e->getMessage(), $e->getCode(), $e);
        }
	}
}
