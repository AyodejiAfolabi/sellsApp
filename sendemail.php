<?php
require 'vendor/autoload.php';
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
    ->setUsername ('joelayodeji2019@gmail.com')
    ->setPassword ('08088379502');

$mailer = new Swift_Mailer($transport);    

$message = (new Swift_Message('Weekly Hours'))
    ->setFrom (array('joelayodeji2019@gmail.com' => 'Joel'))
    ->setTo (array('oyerindejoshua133@gmail.com' => 'Mr.Ikhlas'))
    ->setSubject ('Weekly Hours')
    ->setBody ('Xup bro. Come nd teach me how to upload files', 'text/html');

$result = $mailer->send($message);
if($result){
    echo 'Sucessful';
}
else{
    echo 'NOOOO';
}
?>