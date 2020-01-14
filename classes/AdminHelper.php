<?php

use Ramsey\Uuid\Uuid;

class AdminHelper
{
    private $config;
    private $conn;

    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
    }

    // Load all users
    public function getUsers() {
        $handle = $this->conn->prepare('SELECT * FROM users ORDER BY name, id');
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Load single user
    public function getUser($id) {
        $handle = $this->conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    // Create new user
    public function newUser($name, $email, $password) {
        $handle = $this->conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $handle->bindValue(1, $name);
        $handle->bindValue(2, $email);
        $handle->bindValue(3, password_hash($password, PASSWORD_DEFAULT));
        if ($handle->execute()) {
            $this->sendWelcomeEmail($email, $password);
            return true;
        } else {
            return false;
        }
    }

    public function sendWelcomeEmail($email, $password) {
        $handle = $this->conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $handle->bindValue(1, $email);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            return;
        }

        $email_message = "Welcome to Broskies Portal!</br></br>Broskies Portal is where you can access a bunch of useful resources all in one place. Please login at: https://broskies.gtdu.org/. Your login is:</br>Email: " . $email . "</br>Password: " . $password . "</br></br>If you have any questions, please respond to this auto-generated email.";
        send_email($email, 'Welcome To Broskies Portal!', $email_message);
    }

    // Modify user permissions
    public function setUserPerm($user_id, $perm_field, $perm_level) {
        $handle = $this->conn->prepare('UPDATE users SET `' . $perm_field . '` = ? WHERE id = ?');
        $handle->bindValue(1, $perm_level);
        $handle->bindValue(2, $user_id);
        return $handle->execute();
    }

    // Reset password
    public function resetUserPassword($user_id, $new_password) {
        $handle = $this->conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $handle->bindValue(1, password_hash($new_password, PASSWORD_DEFAULT));
        $handle->bindValue(2, $user_id);
        return $handle->execute();
    }

    // Delete user
    public function deleteUser($user_id) {
        $handle = $this->conn->prepare('DELETE FROM users WHERE id = ?');
        $handle->bindValue(1, $user_id);
        return $handle->execute();
    }

    // Load all modules
    public function getModules() {
        $handle = $this->conn->prepare('SELECT * FROM modules');
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get specific module
    public function getModule($id) {
        $handle = $this->conn->prepare('SELECT * FROM modules WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    // Create new module
    public function createNewModule($module_name, $root_url, $external, $defaultAccess) {
        $api_key = Uuid::uuid4()->toString();
        $perm_name = strtolower(removeNonAlphaNumeric($module_name));

        if ($external == "on") {
            $external = 1;
        } else {
            $external = 0;
        }

        $handle = $this->conn->prepare('INSERT INTO modules (api_token, name, pem_name, root_url, external) VALUES (?, ?, ?, ?, ?)');
        $handle->bindValue(1, $api_key);
        $handle->bindValue(2, $module_name);
        $handle->bindValue(3, $perm_name);
        $handle->bindValue(4, $root_url);
        $handle->bindValue(5, $external);
        $handle->execute();

        $handle = $this->conn->prepare('ALTER TABLE `users` ADD `' . $perm_name . '` INT(1) NOT NULL DEFAULT ?');
        $handle->bindValue(1, $defaultAccess);
        return $handle->execute();
    }

    // Edit module
    public function editModule($module_id, $root_url) {
        $handle = $this->conn->prepare('UPDATE modules SET root_url = ? WHERE id = ?');
        $handle->bindValue(1, $root_url);
        $handle->bindValue(2, $module_id);
        return $handle->execute();
    }

    // Delete a module
    public function deleteModule($module_id) {
        $handle = $this->conn->prepare('SELECT pem_name FROM modules WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $module_id);
        $handle->execute();
        $pem_name = $handle->fetchAll(\PDO::FETCH_ASSOC)[0]['pem_name'];

        $handle = $this->conn->prepare('ALTER TABLE `users` DROP COLUMN `' . $pem_name . '`');
        if ($handle->execute()) {
            $handle = $this->conn->prepare('DELETE FROM modules WHERE id = ?');
            $handle->bindValue(1, $module_id);
            return $handle->execute();
        }
        else {
            return false;
        }
    }

    // Get dynamic configuration values
    public function getDynamicConfig() {
        global $config;

        $handle = $config['dbo']->prepare('SELECT * FROM config ORDER BY id');
        $handle->execute();
        $results = $handle->fetchAll(\PDO::FETCH_ASSOC);

        $output = array();
        foreach ($results as $r) {
            $output[$r['key']] = $r['value'];
        }

        return $output;
    }

    // Modify dynamic configurations
    public function updateDynamicConfig($key, $value) {
        $handle = $this->conn->prepare('UPDATE config SET `value` = ? WHERE `key` = ?');
        $handle->bindValue(1, $value);
        $handle->bindValue(2, $key);
        return $handle->execute();
    }
}
