<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true);

$username = "admin";
$password = "admin";
$database = new PDO("mysql:host=localhost;dbname=PortFolio;charset=utf8", $username, $password);

if (isset($_POST["send_contact"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $SendContact = $database->prepare("INSERT INTO Contact(name, email, subject, message) VALUES (:name, :email, :subject, :message);");
    $SendContact->bindParam(":name", $name);
    $SendContact->bindParam(":email", $email);
    $SendContact->bindParam(":subject", $subject);
    $SendContact->bindParam(":message", $message);

    $response = array();

    if ($SendContact->execute()) {
        $response["success"] = true;
        $response["message"] = "We Will Contact You soon in this email : " . $email;

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'zobirofkir30@gmail.com';
            $mail->Password   = 'skgorlvdyysyspsu';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom($email, $name);
            $mail->addAddress('Zobirofkir30@gmail.com', 'Zobir'); 

            $mail->Subject = $subject;
            $mail->Body = 'Name: ' . $name . ' , Email: ' . $email . ' , Message: ' . $message;

            $mail->send();
            $response["email_sent"] = true;

        } catch (Exception $e) {
            $response["email_sent"] = false;
            $response["email_error"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Error sending the message.";
    }

    echo json_encode($response);
}
?>
