<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(400);
    echo json_encode(["message" => "Insert a valid ID"]);
    die();
}
$user = new User();
$email= $user->getUser($parts[5])["email"];
$name= $user->getUser($parts[5])["name"];

if ($result = $user->resetPassword($parts[5])) {
    echo json_encode($result);
    $mail = new PHPMailer(true);  //phpmailer
$mail->isSMTP();
$mail->SMTPDEBUG = 1;
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Port = 25;
$mail->Username = "paninaraiisviola@gmail.com";
$mail->Password = "paninara1234";
$mail->setFrom('paninaraiisviola@gmail.com', 'SandWEches');
$mail->addAddress($email, $name);
$mail->Subject = 'Reset your SandWEches password';
$mail->isHTML(true);
$mailContent = "<h1>Send HTML Email using SMTP in PHP</h1>
    <p> Your new password is: $result 
        Use it to log into your account and set a new password
        Mind to don't loose it again
        The SandWEches team</p>";
$mail->Body = $mailContent;


if($mail->send()){
    echo 'Message has been sent';
}else{
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} 

} else {
    http_response_code(400);
    echo json_encode(["message" => "User not found"]);
}