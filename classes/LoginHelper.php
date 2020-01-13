<?php

use Ramsey\Uuid\Uuid;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class LoginHelper
{
    private $config;
    private $conn;

    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
    }

    public function validateLogin($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        $handle = $this->conn->prepare('SELECT id, password, name FROM users WHERE email = ? LIMIT 1');
        $handle->bindValue(1, $data['email']);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result) && password_verify($data['password'], $result[0]['password'])) {
            $token = Uuid::uuid4()->toString();

            $handle = $this->conn->prepare('UPDATE users SET session_token = ? WHERE id = ?');
            $handle->bindValue(1, $token);
            $handle->bindValue(2, $result[0]['id']);
            $handle->execute();

            $_SESSION['token'] = $token;
            $_SESSION['name'] = $result[0]['name'];
            $_SESSION['email'] = $data['email'];

            return true;
        } else {
            return false;
        }
    }

    public function logout()
    {
        $handle = $this->conn->prepare('UPDATE users SET session_token = NULL WHERE session_token = ?');
        $handle->bindValue(1, $_SESSION['token']);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            session_destroy();
            return true;
        } else {
            return false;
        }
    }

    public function sendPasswordReset($email) {
        $handle = $this->conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $handle->bindValue(1, $email);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            return;
        }

        $code = Uuid::uuid4()->toString();

        $handle = $this->conn->prepare('UPDATE users SET password_reset = ? WHERE id = ?');
        $handle->bindValue(1, $code);
        $handle->bindValue(2, $result[0]['id']);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'ryan.cobelli@gmail.com';               // SMTP username
            $mail->Password   = $this->config['email_password'];                     // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('broskies@gtdu.org', 'Broskies Portal');
            $mail->addAddress($email);     // Add a recipient
            $mail->addReplyTo('webmaster@gtdu.org', 'Webmaster');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Broskies Portal Password Reset';
            $mail->Body    = "You've requested to reset your password. Please visit this url: https://broskies.gtdu.org/reset.php?code=" . $code . ". If you did not request this reset, please reply to this email.";

            $mail->send();
        } catch (Exception $e) {
            exit("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    // Reset password
    public function resetUserPassword($reset_code, $new_password) {
        $handle = $this->conn->prepare('UPDATE users SET password = ?, password_reset = NULL WHERE password_reset = ?');
        $handle->bindValue(1, password_hash($new_password, PASSWORD_DEFAULT));
        $handle->bindValue(2, $reset_code);
        return $handle->execute();
    }
}
