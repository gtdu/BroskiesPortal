<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function init_site(site $site)
{
    $site->addHeader("../includes/header.php");
    $site->addFooter("../includes/footer.php");
}

function logMessage($message)
{
    global $config;

    print($message);
}

function getCurrentPermissions($config) {
    $handle = $config['dbo']->prepare('SELECT * FROM users WHERE session_token = ? LIMIT 1');
    $handle->bindValue(1, $_SESSION['token']);
    $handle->execute();
    return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
}

function devEnv()
{
    return gethostname() == "Ryans-MBP";
}

function currentPage($name)
{
    echo $name;
    echo basename(__FILE__, '.php') == $name ? "active" : "";
}

function removeNonAlphaNumeric($str)
{
    return preg_replace("/[^A-Za-z0-9 ]/", "", $str);
}

function str_replace_last($search, $replace, $str)
{
    if (($pos = strrpos($str, $search)) !== false) {
        $search_length  = strlen($search);
        $str    = substr_replace($str, $replace, $pos, $search_length);
    }
    return $str;
}

function send_email($to, $subject, $message) {
    global $config;

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $config['email_address'];              // SMTP username
        $mail->Password   = $config['email_password'];                     // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('broskies@gtdu.org', 'Broskies Portal');
        $mail->addAddress($to);
        $mail->addReplyTo('webmaster@gtdu.org', 'Webmaster');

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        exit("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
