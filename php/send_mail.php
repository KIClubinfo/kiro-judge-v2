<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


function send_mail($email, $team, $p1, $p2, $n2, $p3, $n3, $id, $password) {
$message= '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Validation de compte KIRO</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td align="center" bgcolor="#333333" style="padding: 40px 0 30px 0; color: #222222; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
                            <img src="https://nsa40.casimages.com/img/2020/08/04/200804062120397414.png" width="362" height="230" style="display: block;"/>

                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: #222222; font-family: Arial, sans-serif; font-size: 24px;">
                                        <b>Validation de ton compte KIRO</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 0 30px 0; color: #222222; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                        Salut '.$p1.',<br/>
                                        <br/>
                                        Le club informatique des Ponts (KI pour les intimes) te confirme que tu es bien inscrit au KIRO au sein de
                                        l’équipe :
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#444444" style="text-align:center; padding: 20px 0 20px 0; color: white; font-family: Arial, sans-serif; font-size: 30px; line-height: 20px;">
                                        '.$team.'
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 0 20px 0; color: #222222; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                        Avec tes coéquipiers talentueux :
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td width="260" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td bgcolor="#444444" style="text-align:center; padding: 20px 0 20px 0; color: white; font-family: Arial, sans-serif; font-size: 20px; line-height: 20px;">
                                                                '.$p2.'<br/>'.$n2.'
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="font-size: 0; line-height: 0;" width="20">
                                                    &nbsp;
                                                </td>
                                                <td width="260" valign="top">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td bgcolor="#444444" style="text-align:center; padding: 20px 0 20px 0; color: white; font-family: Arial, sans-serif; font-size: 20px; line-height: 20px;">
                                                                '.$p3.'<br/>'.$n3.'
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 0 0 0; color: #222222; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                        Pour accéder au site du KIRO Judge, où vous mettrez en ligne vos résultats et verrez votre place dans la
                                        compétition,<br/> c’est <a href="">ici</a> avec l’identifiant :<br/> '.$id.'<br/> et le mot de passe :<br/> '.$password.'.<br/>
                                        <br/>
                                        A bientôt au KIRO,<br/>
                                        <br/>
                                        Amicalement,<br/>
                                        <br/>
                                        Le KI.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#1BA6D3" style="padding: 30px 30px 30px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="color: rgb(30,30,30); font-family: Arial, sans-serif; font-size: 14px;" width="100%">
                                        Cet email a été envoyé automatiquement, merci de ne pas y répondre.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
';

$subject = 'Validation de compte KIRO';
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kiro.enpc@gmail.com';                     //SMTP username
    $mail->Password   = '8qd3ECZqxmrqN9A';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('kiro.enpc@gmail.com', 'Team KIRO');
    $mail->addAddress($email);     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;
    $mail->CharSet = "UTF-8";

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

};
};
?>
