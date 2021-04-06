<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


function send_mail($email, $team, $p1, $p2, $p3, $n2, $n3, $id, $password) {
$message= '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Validation de ton compte KIRO</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								    <td align="center" bgcolor="#333333" style="padding: 40px 0 30px 0; color: #222222; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
								    	<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfQAAAE+CAMAAABMexJqAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAC6UExURUdwTOYtWBqm0xqk1N8vX+YuWBqm0v7+/h+fz+QvWhql1OUtWBml0uMrV+UtWRql0ucvVxul0+UtWRuj0xenz+UsWRum0+YuWOYuWRum0Rum0+YtWBum0uYuWRqk0eQtV/7+/v////7+/v////////7+/v7+/v////7+/v7+/v////7+/uYuWfvh5/////7+/vSqvOc2X/nQ2vGXrOxjg+tUeOc9ZvCEnu50kOYxXOlIbeYuWRum0////1L8NQoAAAA7dFJOUwDv7zAQf3+vEDCPcFBAv78g399AIFCvr49wn8/Pn2Bg7xB/QCDfv1CfjzBw8ONgz8jY2MPAw8/Av+PIrm87BwAADntJREFUeNrtnWtDIjkThQEBuSqiIKKI6jiXndl9971e1vX//60dxwEF051KUqnczvm6swJJd3U9lVPVjUaS6g2Wzxps1H3WQQPKWRd/vtdJD+uSs3otxaZfY12y1kCx53MsS9Y6UNzorVOsS9Y6VtzoAyxL1hop9ryFLC7vLO5KseldrEvW6gLXitOZCteQxQHXoLx0qsriUIAtD9eQxQHXoMxw7USx6RdYl+Jw7RjLAlyD8tISuAZce87izrAuwDUoK8EjVSCuwSMFXAOuAdegDDWHR6o4oeheYBYHjxRwDbhWKK6NsC5ZCx6p8nTagkeqOKGlBbgGXCsU1+CRylvKojtu9KyFonuBgkeqQFxDSwtwDUX3/HWNont5uHYCjxRwDR4p4BpUBq6h6J63RmhpAa4B1/IXPFIF4ho8UsA1FN3LxDUU3QvM4oBrBeIasri8szh4pIBrz7rCjV4erqHonrfQ0lKe0NICXEPRvVBcQ9EduAZlJnikyhM8UgUKLS0F4ho8UsA14Fr+gkeqQFxDSwtwDVkccA3KUBj7W57gkSowi8PYX+AacK2AGx1ZXHmCR6o8HcAjVZ7Q0gJcA64VimvwSOUteKTKE4ruwDXgWgHC2F/gGoruheIaiu6Z4xo8UsA14BpwDcpQGPsLXEPRHbgGZSh4pArENYz9Ba6h6F4mrqHonrnQ0lKe4JEqMIuDRwq4hlejF4prKLrnLXikyhPG/hYoFN2Bayi6F4pr8EjlLXikyhM8UgUKHqkCcQ1jf4FrwLX8dQ1cKw/X0NICXEPRvUxcawHXgGtQXoJHCrgGXCtAaGkpENdQdAeuAdcKxTV4pJDFQfnjGrK4zLM4tLQA1+CRAq5BGQotLeVJ2dICjxRwDQKuQRniGjxSeQstLeUJLS0FCh6pAnENHingGnAtf8EjVSCunWDsL3ANRfcyce36IJgsnit9XrW1Hzjp+1JAXAup3fLApy/6XzBpPrKqqdv19uWjJ61k9nwU2Z4vd77d7cPTB+1PWEkv/dDXnjcnMlnccVx7vneae/f0dKj7CWP+xa9f+3bT16Z3wuFaSO2WBz7cPD093Wt+whH/4h/VfuDC155P2zI3eiuuPd8rD3z+vudPD/U/Ye1j+Wd1aaO3G30tc6MPIrvRd09z759+6LfaWDv1cs/JRpYXnQfEtYDaKw98fNn0m7qf0JG+6Wa+9vxxHK7oHhGuPf3UZzlc0+bR/nBtUSau7Z7m3v5ts+k12LbytQUr2chCqA4wZXFXkeFabx/XNjoUxLV6bJukjmvd+HFto0/SSVUVtqWOa2cJ4NpGD8KlsSpsG6eexcWGayMVrm30m2xp7MdDNkNcO/0zBVzb6OZWMql60VAO15pCx2uxFd0P1LhWg22Tpt9Nf4dtfgpBwLX3uFaNbeePnrXKDddOUsG1jT7Klca26gtFlmEUuNaV1m4WN7l5v+n7T3Vlaey8Y62mlp1XvB/4qmEcuBbYAL341/tN/0LANQfYnWmj7jhcXBbySAXtTP++vL9qb/Qm69mkMm4MtbjWSWjPT00BSlbfl/c/+5v+lZBUHdl/pPLPtT1GFnkRcC1g++KPSPv77p4/3BJwzb6qpf9zbd4PlBfFI3US7Nu90PC33U2/J5TGHGB3oS2SdcKV0QQ9UsFGEvxc3v++3fND47TLNInQFMlUoUCqjCZ4uhaqbXWzvN/e5HI3vxDSLoekSp+jnYcro4meri3DfL0tDf87KK7t/rlZ6rg2VyXr0WDbm0j7DyNcmzkmEXX0RwC6uKWeIzWPZejMm0j7982m3xmnXe64po0sRwnd6BVjf3v6E24R7SzvP9W4NuZNqgrFteOK7K4lj2s7y/tNBNdW2rixyBHXngsxymM38cHfe5H2fwK4NtOepPdTx7Xqsb+jCF6g/S7S/krENfukqq3HteSL7jVjf6/CY9s7Gv7jPa51eJOqoRWuJV903xyjnqlyOdHX9iiW9/cbv0V3/WGdMrKsE9rz+rG/qvPWK8ksTrG8//eMawstrjEf58WBa6819tDYRqmz6avkRupr48YkdVzTjf29CIptJBo+8l90X2hDQfJF951z85OQzilK4J75L7rv4to43GQYHunH/obENkqdTV8ld04icsM1wvnpPJxzirK8Ah6pgnCtERzbKHU2CY/U2mNkkdc1aezvMpBzilRnW8EjZYhrtLG/yn8m4JyiBG5m47nyz+WPa3NiQPCPbZMocW2VIa6pX41+FeLVHitCHEVLCz+u1SX5vp1TlDobt0cqf1wbGdhdA2CbLa4xe6Sm+be0VDkk5EvwlOWdwCNlKKVHqmeS8/nENhINMxvPV/njmtm79qSxjYJraGnxhWs2GYAMrnEbz/W4dl4MrtVhmzfnFIWGmY3nQ23cSL6lxfxde2eC2EZZ3raAR2roM7JEgWu6d+0txRpe2hRck2hpaXuMLPJZnM2r0eWwjYJrfeAaB65Z/V8+SvCkOhtzaey83JYWjYQaXig0LOGR6nuMLPIaWCbiMs4pCg1zt7QU4JFq2e7dXALbKL2IzB6pwltaGubYxuycCuCRKr6lxRzbeBterD1SaGnhxrXt/+z9veqq5b3029Iy1r5VoRiPlFq+nVPptLSsEtrzM0tc2+jKb8MLhYbj8EixtLSMFPJwirV0tLudesU265YWB1ybhsM1l/TKdc+MPsarc6qslhan9EoE17bf1CO2UZZXoqVlpg0FLLjWlTnCumCIJ/6cUyQaZsa1gB4p1/RKkrj8OaesPVI/nvqToYUWVh4pHlxbynQTdFmexyNP2EbySFU/9dneq6t/SwuLR+pUxoBm7pGiYxvDJboi0HA1rrG9qGkq1NJy7Bd+ueOJH+cUhYarq+RttrdfrmVw7UIG1/gMrUsf2ObW0sL2Xt0jYmSJIb0ifMwxmwfCh3Nq6IRrfO/VFfJIdX2WO+rjyXGPMTa54RqFhqur5GxvvwyIay3+8R49VipkxzZbj9RLUsX29svdJMJfS4sQrg1Y4wm3c8q6paVTGQIYcM1bS8soLVyrK8E7OKcoy9uRxjVvLS2M6ZUoFfI6p6w9UrPKEOCOa/5aWlQp0ZUMrrlRIWfDC+n1WtVH7R05XGNpaekJFd35D/GU39wS29zG/k6Aa2KHeHzOKWuP1KIyBFhpQYwsaeCan0M8tlHBCydcG3Pt+Z5HyhuuCRXdB16okMs51XfzSHnCtSw9Ugy+NibnlLVHShrX/HmkEsC12hK88cPJsaVlyrXpMy1PpOSR8hdPugwNL64tLar/dNm30N4zR/sv4sY1f4OhOP609djfn3vQ1t61UWkQDtfmHoOIGba5j/1VXTXNWPfcvmPYnQrZ4olzwwtDS8vUV57tQYl5pNQ6cHROWY/9XWsywThfpJNMS4v5NWXw8GBpaTlKZRxMOi0tFthGfnrwtLRMVLlcjPNgEmppMf8hVOeUtUdqpSe6aXx7LtXSIvAxDthm3dJCdkZHpaRaWnxhm6NH6o3WKYxtPU3TI0VPG0jOKduxv4oa+DSBWRFCuDYXiSe2DS+UjiFq4Faer8aFbcm1tJg/qwhJKevY3/PY5z9JtbQIHeJZOqdIHimyZSn6EnyCLS31urbANuuxvws6CURUgk+ypYUd29xaWojMH88M/iRbWixo5MwU10hjfzsm1b1oSvBC6dWBTDyp4YRabHNraVHqMt4SfKotLZpfZeic8jH2N+ISvFB6JXSIV/swqcY2P2N/oy3Bp9vSwomhQy9jf9uPkZbgE25pMce2lgmu0VtajNA/AmxLuqWlXgbOKVuPlPYMZeqroTyF9EqICndxgYxtpJaWpg12z2IswSfe0mJ+pSmfXG4tLQ1TEgyMbam3tPBgm8+xv5P4SvBC6dW1MK7V/bwTGq6tue7Y6LAt/ZYWjUgf7Db2Vyd/g7oZCxiJtbSYpyz72MbnkVJrHVcJXii98tvSYp5MXOhxjT72l6DLqJxTGePa9oLTViHcxv5SNI6pBD/KyyNFveIG5ri2cLM+qZLAy4hw7TrZeELNVfeutzUhcPcdxzpF5JwK6JE66cn9zOva681x7C9R0TinMmppMcG2veuN2yNlgG0hmpczamkxYZRTUVyrvW7ksS2rlpZ6zauvtxW/R4qObfIleKH0KmwW9/MJU3m9uY39NVEUzqmLEnDtfeHRAteY3rUXQQk+t5YW4q/du97cxv6aKQLnVMiWlgBJ67Ua1zx5pNQK7pzKsKWlXle2uDZ0xrWtQmObTMewaEtLvQ4Uz6+J8KvRAzunhNKr0whw7e1lPjfP4joEZzRZQZ1TeXukKrFt7zTXR0uLBbaNhRYg05YWXeo60NfZnFtaosW2XFtadF9Hj2t8HqkKbLNyTnUHDJqH80gdN8KpZ4xrbfZ3KawtsE0Vl1mUSUuLgRjG/trI/OxGWUZjUWYeKb3G3lpa6tU3Pm3r+trzBMf+Oopl7C8Xtq1MAyaLsvNI2dRJpl5xbRs+DE/blr72PMmxv04rf2npkWIopXSMTJIjX3su1dIyiuZG5xr7y4ZtVcfzyskwLFryr2o3mqK7sjIm5JGiP1qa4rgm1NJyEM2mi3mkXLGt1wKuceGabdGdyfIwIT84UsK1Ufq4tvB5IEat6KeEa1Jz6Sxl65HiOw9rE/+6tyxOyCN1FQ+uUbJnNo+UwXWXNq7FXXSXaWnRiHLapjylZFGWLS2muEbySLG62ShXlbcsLtOWlnhxjV6CP0sJ19IrujdFiu56bNs1SQ4SwrVGT6VoNr3RVsjm3/j4Hm3tOrKoAUEQBEEQBEEQBEEQBOWhDzdPUBE6fN30z1iNQnS/3fN7LEYh+vJ6o3/EapShm9vtnn/FahSiu+2e3z5gNcrQw+uNfofVKESfgGvANSj/LO6X7Z5/wmoA1yDgGpSLvr7iGrK4QvQRuFae7oFrJeMasjjgGpSrXovujbtDqAy9ZnF/ASFZcn68c8+JAAAAAElFTkSuQmCC" alt="Logo KIRO" width="362" height="230" style="display: block;"/>
									</td>
								</tr>
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
                                        compétition, c’est <a href="http://kiro.enpc.org">ici</a> avec les identifiants : <br/><br/>Mail :  '.$id.' <br/> Mot de passe : '.$password.'<br/>
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
                                    <td style="color: rgb(30,30,30); font-family: Arial, sans-serif; font-size: 14px; width="100%">
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

$subject = 'Validation de ton compte KIRO';
$mail = new PHPMailer(true);
try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kiro.enpc@gmail.com';                     //SMTP username
    $mail->Password   = 'NXWd!DXjm5bWb^';                               //SMTP password
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
	   $mail->Body = $message;
    // $mail->Body    = $message;
    // $mail->AltBody = $message;
    $mail->CharSet = "UTF-8";

    $mail->send();
} catch (Exception $e) {

};
};

function send_password($email, $password, $name) {
$message= '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nouveau mot de passe</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
								    <td align="center" bgcolor="#333333" style="padding: 40px 0 30px 0; color: #222222; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
								    	<img src="data:image/png;base64,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" alt="Logo KIRO" width="362" height="230" style="display: block;"/>
									</td>
								</tr>
                                <tr>
                                    <td style="color: #222222; font-family: Arial, sans-serif; font-size: 24px;">
                                        <b>Nouveau mot de passe</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 20px 0 30px 0; color: #222222; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                        Salut '.$name.',<br/>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding: 20px 0 0 0; color: #222222; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                        Nous avons bien reçu ta demande de modification de mot de passe. Voici un nouveau qui te permettra de te connecter: '.$password.'
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
                                    <td style="color: rgb(30,30,30); font-family: Arial, sans-serif; font-size: 14px; width="100%">
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

$subject = 'Nouveau mot de passe';
$mail = new PHPMailer(true);
try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'kiro.enpc@gmail.com';                     //SMTP username
    $mail->Password   = 'NXWd!DXjm5bWb^';                               //SMTP password
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
	   $mail->Body = $message;
    // $mail->Body    = $message;
    // $mail->AltBody = $message;
    $mail->CharSet = "UTF-8";

    $mail->send();
} catch (Exception $e) {

};
};
?>
