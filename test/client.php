<?php

use PHPMailer\PHPMailer\PHPMailer;

include '../vendor/autoload.php';

//prevent web execution
if (php_sapi_name() !== 'cli'){
    exit();
}

try {
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'localhost';
    $mail->Port = 1025;
    $mail->SMTPDebug = true;

    $mail->SMTPAuth = true;
    $mail->Username = "foo@gmail.com";
    $mail->Password = "foo@gmail.com";

    $mail->setFrom('from@example.org', 'Mailer');
    $mail->addAddress('joe@example.org', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.org');               // Name is optional
    $mail->addReplyTo('info@example.org', 'Information');
    $mail->addCC('cc@example.org');
    $mail->addBCC('bcc@example.org');

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
catch(\Exception $e) {
    var_dump($e);
}