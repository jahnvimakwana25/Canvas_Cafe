<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if (isset($_POST['mail-send'])) {
    $Uname = $_POST['Uname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'canvascafe02@gmail.com';
        $mail->Password   = 'woxl cmfx deca wpga'; // Use app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('canvascafe02@gmail.com', 'Canvas Cafe');
        $mail->addReplyTo($email, $Uname);
        $mail->addAddress('canvascafe02@gmail.com');

        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "Name: $Uname\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";

        $mail->send();

        header('Location: contact.php?success=1');
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit();
    }
}
?>
