<?php

use Ramsey\Uuid\Uuid;

/**
* Class to manage all the login related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class LoginHelper extends Helper
{
    /**
    * Validate login credentials
    *
    * @param String The slack user ID
    * @return Boolean If the login credentials are valid
    */
    public function validateLogin($id)
    {
        // Check if required parameters are provided
        if (empty($id)) {
            $this->error = "All fields are required";
            return false;
        }

        // Query the DB if the slack_id exists
        $handle = $this->conn->prepare('SELECT id, name, core FROM users WHERE slack_id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        $result = $handle->fetchAll(PDO::FETCH_ASSOC);

        // Make sure there was a result
        if (!empty($result)) {

            // Generate a session token
            try {
                $token = Uuid::uuid4()->toString();
            } catch (Exception $e) {
                $this->error = "Unable to generate session token";
                return false;
            }

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
     * Validate login credentials
     *
     * @param String The slack user ID
     * @return bool If the user's core permission > 0
     */
    public function canLogin($id) {
        // Check if required parameters are provided
        if (empty($id)) {
            $this->error = "All fields are required";
            return false;
        }

        // Query the DB if the slack_id exists
        $handle = $this->conn->prepare('SELECT core FROM users WHERE slack_id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        $result = $handle->fetchAll(PDO::FETCH_ASSOC);

        // Make sure there was a result
        return !empty($result) && $result[0]['core'] > 0;
    }

    /**
     * Create a new user
     *
     * @param $name string The user's name. We recommend Last, First
     * @param $slack_id string The user's slack ID
     * @return bool
     */
    public function newUser($name, $slack_id)
    {
        // Validate data
        if (empty($name) || empty($slack_id)) {
            $this->error = "All fields are required";
            return false;
        }

        // Insert into the DB
        $handle = $this->conn->prepare('INSERT INTO users (name, slack_id) VALUES (?, ?)');
        $handle->bindValue(1, $name);
        $handle->bindValue(2, $slack_id);

        // Check if the operation was successful
        if ($handle->execute()) {
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
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }

        $result = $handle->fetchAll(PDO::FETCH_ASSOC);
        setcookie("broskies_portal", "", 1, '/');

        if (!empty($result)) {
            session_destroy();
            return true;
        } else {
            // Will only ever return false if the existing session token was invalid
            return false;
        }
    }
}
