<?php

$app = require __DIR__ . '/../bootstrap/start.php';

$mail = new \CatLab\Mailer\Models\Mail ();

$to = new \CatLab\Mailer\Collections\ContactCollection ();
$to[] = 'thijs@catlab.be';
$to[] = 'thijs+test@catlab.be';

$mail->setTo ($to);
$mail->setFrom ('info@catlab.be');
$mail->setSubject ('This is a test');
$mail->setText ('This is an example text message.');

\CatLab\Mailer\Mailer::getInstance ()->send ($mail);