<?php

namespace CatLab\Mailer\Tests;

use CatLab\Mailer\Collections\ContactCollection;
use CatLab\Mailer\Collections\ImageCollection;
use CatLab\Mailer\Exceptions\MailException;
use CatLab\Mailer\Models\Contact;
use CatLab\Mailer\Models\Image;
use CatLab\Mailer\Models\Mail;
use CatLab\Mailer\Services\SMTP;
use PHPUnit\Framework\TestCase;

/**
 * Sends through a real SMTP server and reads the result back from Mailpit
 * (https://mailpit.axllent.org). Set MAILPIT_HOST to run, e.g.
 *
 *   docker run -d --name mailpit -e MP_SMTP_AUTH_ACCEPT_ANY=1 \
 *     -e MP_SMTP_AUTH_ALLOW_INSECURE=1 axllent/mailpit
 *
 * @group smtp
 */
#[\PHPUnit\Framework\Attributes\Group('smtp')]
class SmtpServiceTest extends TestCase
{
    /** @var string */
    private $host;

    protected function setUp(): void
    {
        $this->host = getenv('MAILPIT_HOST');
        if (!$this->host) {
            $this->markTestSkipped('Set MAILPIT_HOST to run the SMTP tests.');
        }
        $this->api('DELETE', '/api/v1/messages');
    }

    private function service($port = 1025)
    {
        $service = new SMTP();
        $service->setFromConfig([
            'server' => $this->host,
            'username' => 'user',
            'password' => 'secret',
            'security' => '',
            'port' => $port,
        ]);
        return $service;
    }

    private function api($method, $path)
    {
        $context = stream_context_create([ 'http' => [ 'method' => $method, 'ignore_errors' => true ] ]);
        $body = file_get_contents('http://' . $this->host . ':8025' . $path, false, $context);
        return json_decode($body, true);
    }

    public function testSendsHtmlMailWithAllRecipientsAndAttachment()
    {
        $to = new ContactCollection();
        $to[] = 'to@example.com';
        $cc = new ContactCollection();
        $cc[] = 'cc@example.com';
        $bcc = new ContactCollection();
        $bcc[] = 'bcc@example.com';

        $attachment = tempnam(sys_get_temp_dir(), 'mailer');
        file_put_contents($attachment, '%PDF-1.4 test');
        $images = new ImageCollection();
        $images[] = new Image($attachment, 'ledger.pdf');

        $mail = new Mail();
        $mail->setFrom('info@example.com');
        $mail->setTo($to);
        $mail->setCc($cc);
        $mail->setBcc($bcc);
        $mail->setReplyTo(Contact::fromMixed('reply@example.com'));
        $mail->setImages($images);
        $mail->setSubject('Tëst ✓');
        $mail->setBody('<p>Hallo wêreld</p>');

        $this->assertTrue($this->service()->send($mail));
        unlink($attachment);

        $list = $this->api('GET', '/api/v1/messages');
        $this->assertSame(1, $list['total']);
        $message = $this->api('GET', '/api/v1/message/' . $list['messages'][0]['ID']);

        $this->assertSame('Tëst ✓', $message['Subject']);
        $this->assertSame('info@example.com', $message['From']['Address']);
        $this->assertSame([ 'to@example.com' ], array_column($message['To'], 'Address'));
        $this->assertSame([ 'cc@example.com' ], array_column($message['Cc'], 'Address'));
        $this->assertSame([ 'bcc@example.com' ], array_column($message['Bcc'], 'Address'));
        $this->assertSame([ 'reply@example.com' ], array_column($message['ReplyTo'], 'Address'));
        $this->assertStringContainsString('<p>Hallo wêreld</p>', $message['HTML']);
        $this->assertSame([ 'ledger.pdf' ], array_column($message['Attachments'], 'FileName'));
    }

    public function testSendsPlainText()
    {
        $to = new ContactCollection();
        $to[] = 'to@example.com';

        $mail = new Mail();
        $mail->setFrom('info@example.com');
        $mail->setTo($to);
        $mail->setSubject('Plain');
        $mail->setText('Just text.');

        $this->assertTrue($this->service()->send($mail));

        $list = $this->api('GET', '/api/v1/messages');
        $message = $this->api('GET', '/api/v1/message/' . $list['messages'][0]['ID']);
        $this->assertSame('', $message['HTML']);
        $this->assertStringContainsString('Just text.', $message['Text']);
    }

    public function testUnreachableServerThrowsMailException()
    {
        $to = new ContactCollection();
        $to[] = 'to@example.com';

        $mail = new Mail();
        $mail->setFrom('info@example.com');
        $mail->setTo($to);
        $mail->setSubject('Nowhere');
        $mail->setText('x');

        $this->expectException(MailException::class);
        $this->service(1)->send($mail);
    }
}
