<?php

use Ramsey\Uuid\Uuid;

/**
* Class to manage all the login related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class LoginHelper
{
    private $config;
    private $conn;

    /**
    * Setup the helper
    *
    * @param input The config array (contains config info, DB object, etc.)
    */
    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
    }

    /**
    * Validate login credentials
    *
    * @param data An array with a email key and password key
    * @return Boolean If the login credentials are valid
    */
    public function validateLogin($data)
    {
        // Check if required parameters are provided
        if (empty($data['email']) || empty($data['password'])) {
            return false;
        }

        // Query the DB if the email exists
        $handle = $this->conn->prepare('SELECT id, password, name FROM users WHERE email = ? LIMIT 1');
        $handle->bindValue(1, $data['email']);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        // Check that the email exists as a user
        // Attempt to verify the password
        if (!empty($result) && password_verify($data['password'], $result[0]['password'])) {
            // Generate a session token
            $token = Uuid::uuid4()->toString();

            // Update the user with the session token
            $handle = $this->conn->prepare('UPDATE users SET session_token = ? WHERE id = ?');
            $handle->bindValue(1, $token);
            $handle->bindValue(2, $result[0]['id']);
            $handle->execute();

            // Store in the session
            $_SESSION['token'] = $token;

            return true;
        } else {
            return false;
        }
    }

    /**
    * Destroy the session and remove the session token from the DB
    *
    * @return Boolean If the session was successfully destroyed
    */
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
            // Will only ever return false if the existing session token was invalid
            return false;
        }
    }

    /**
    * Send a user their password reset email when they can't login
    *
    * @param email The email address of the user
    *
    */
    public function sendPasswordReset($email)
    {
        // Check if the email exists as a user
        $handle = $this->conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $handle->bindValue(1, $email);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        // Stop if the user doen't exist
        if (empty($result)) {
            return;
        }

        // Generate a reset code
        $code = Uuid::uuid4()->toString();

        // Update the user with the reset code
        $handle = $this->conn->prepare('UPDATE users SET password_reset = ? WHERE id = ?');
        $handle->bindValue(1, $code);
        $handle->bindValue(2, $result[0]['id']);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        // Use standard lib to send the email
        $message = "You've requested to reset your password. Please visit this url: https://broskies.gtdu.org/reset.php?code=" . $code . ". If you did not request this reset, please reply to this auto-generated email.";
        send_email($email, 'Broskies Portal Password Reset', $message);
    }

    /**
    * Update the db with the user's new password
    *
    * @param reset_code The reset code generated in sendPasswordReset
    * @param new_password The user's new password
    */
    public function resetUserPassword($reset_code, $new_password)
    {
        // Update any user with that reset code to use the new passwod and no longer have a reset code
        $handle = $this->conn->prepare('UPDATE users SET password = ?, password_reset = NULL WHERE password_reset = ?');
        $handle->bindValue(1, password_hash($new_password, PASSWORD_DEFAULT));
        $handle->bindValue(2, $reset_code);
        
        // Return if the operation was successful
        return $handle->execute();
    }
}
