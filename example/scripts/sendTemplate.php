<?php

use Neuron\Core\Template;

$app = require __DIR__ . '/../bootstrap/start.php';

$mail = new \CatLab\Mailer\Models\Mail ();

$mail->getImages ()->add (__DIR__ . '/../images/logo.png');

$mail->getTo ()->add ('thijs@catlab.be');

$mail->setFrom ('info@catlab.be');

$mail->setSubject ('This is a test');
$mail->setTemplate (new Template ('email/test.phpt', array (
	'title' => 'Welcome to QuizWitz',
	'message' => 'This message was sent at ' . date ('d/m/Y H:i:s')
)));

\CatLab\Mailer\Mailer::getInstance ()->send ($mail);