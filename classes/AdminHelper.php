<?php

use Ramsey\Uuid\Uuid;

/**
* Class to manage all the API related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class AdminHelper extends Helper
{
    /**
    * Return all the users and associated info
    *
    * Sort by the user's name and DB ID
    */
    public function getUsers()
    {
        $handle = $this->conn->prepare('SELECT * FROM users ORDER BY name, id');
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * Return the user's and associated info
    *
    * @param id The users DB ID
    */
    public function getUserByID($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    /**
    * Create a new user
    *
    * @param name The user's name. We recommend Last, First
    * @param eamil The user's slack ID
    * @param password The user's password
    * @param phone The user's phone number
    */
    public function newUser($name, $slack_id, $password, $phone)
    {
        // Clean data
        $phone = removeNonAlphaNumeric($phone);

        // Validate data
        if (empty($name) || empty($slack_id) || empty($password) || empty($phone)) {
            $this->error = "All fields are required";
            return false;
        }

        // Insert into the DB
        $handle = $this->conn->prepare('INSERT INTO users (name, slack_id, password, phone) VALUES (?, ?, ?, ?)');
        $handle->bindValue(1, $name);
        $handle->bindValue(2, $slack_id);
        $handle->bindValue(3, password_hash($password, PASSWORD_DEFAULT));
        $handle->bindValue(4, $phone);

        // Check if the operation was successful
        if ($handle->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Update a specific permission for the user
    *
    * @param user_id The user's DB ID
    * @param perm_field The technical permission name
    * @param perm_level The new value
    */
    public function setUserPerm($user_id, $perm_field, $perm_level)
    {
        // Validate data
        if (empty($user_id) || empty($perm_field)) {
            $this->error = "All fields are required";
            return false;
        }

        // Clean data
        $perm_field = removeNonAlphaNumeric($perm_field);

        // Perform operation
        $handle = $this->conn->prepare('UPDATE users SET `' . $perm_field . '` = ? WHERE id = ?');
        $handle->bindValue(1, $perm_level);
        $handle->bindValue(2, $user_id);
        return $handle->execute();
    }

    /**
    * Update the users phone number
    *
    * @param user_id The user's DB ID
    * @param value The phone number
    */
    public function setUserPhone($user_id, $value)
    {
        // Clean data
        $phone = removeNonAlphaNumeric($phone);

        // Validate data
        if (empty($user_id) || empty($value)) {
            $this->error = "All fields are required";
            return false;
        }

        // Perform operation
        $handle = $this->conn->prepare('UPDATE users SET `phone` = ? WHERE id = ?');
        $handle->bindValue(1, $value);
        $handle->bindValue(2, $user_id);
        return $handle->execute();
    }

    /**
    * Reset a user's password
    *
    * @param user_id The user's DB ID
    * @param new_password The new value
    */
    public function resetUserPassword($user_id, $new_password)
    {
        // Validate data
        if (empty($user_id) || empty($new_password)) {
            $this->error = "All fields are required";
            return false;
        }

        // Perform operation
        $handle = $this->conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $handle->bindValue(1, password_hash($new_password, PASSWORD_DEFAULT));
        $handle->bindValue(2, $user_id);
        return $handle->execute();
    }

    /**
    * Remove a user
    *
    * @param user_id The user's DB ID
    */
    public function deleteUser($user_id)
    {
        // Validate data
        if (empty($user_id)) {
            $this->error = "All fields are required";
            return false;
        }

        // Perform operation
        $handle = $this->conn->prepare('DELETE FROM users WHERE id = ?');
        $handle->bindValue(1, $user_id);
        return $handle->execute();
    }

    /**
    * Return all the modules and associated info
    *
    */
    public function getModules()
    {
        $handle = $this->conn->prepare('SELECT * FROM modules');
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
        return $handle->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * Return all the modules and associated info about a module
    *
    * @param id Module DB ID
    */
    public function getModuleByID($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM modules WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
        return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    /**
    * Create a new user
    *
    * @param module_name The modules's name
    * @param root_url The URL of the module's code
    * @param external Should the module open in a new tab
    * @param defaultAccess Default access level
    * @param icon_url The URL of the module's icon
    * @param levelNames The different level names
    */
    public function createNewModule($module_name, $root_url, $external, $defaultAccess, $icon_url, $levelNames)
    {
        // Validate data
        if (empty($module_name) || empty($root_url)) {
            $this->error = "All fields are required";
            return false;
        }

        // Clean data
        $perm_name = strtolower(removeNonAlphaNumeric($module_name));
        if ($external == "on") {
            $external = 1;
        } else {
            $external = 0;
        }

        // Generate the API key
        $api_key = Uuid::uuid4()->toString();

        // Perform operations
        $handle = $this->conn->prepare('INSERT INTO modules (api_token, name, pem_name, root_url, external, icon_url, levelNames) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $handle->bindValue(1, $api_key);
        $handle->bindValue(2, $module_name);
        $handle->bindValue(3, $perm_name);
        $handle->bindValue(4, $root_url);
        $handle->bindValue(5, $external);
        $handle->bindValue(6, $icon_url);
        $handle->bindValue(7, $levelNames);
        $handle->execute();

        $handle = $this->conn->prepare('ALTER TABLE `users` ADD `' . $perm_name . '` INT(1) NOT NULL DEFAULT ?');
        $handle->bindValue(1, $defaultAccess);
        return $handle->execute();
    }

    /**
    * Modify a module. Only thing that can be changed is the root url
    *
    * @param module_id The modules's DB ID
    * @param root_url The URL of the module's code
    * @param external If the module should open in a new tab or not
    * @param icon_url The URL of the module's icon
    * @param levelNames The different level names
    */
    public function editModule($module_id, $root_url, $external, $icon_url, $levelNames)
    {
        // Validate data
        if (empty($module_id) || empty($root_url)) {
            $this->error = "All fields are required";
            return false;
        }

        // Clean data
        if ($external == "on") {
            $external = 1;
        } else {
            $external = 0;
        }

        // Perform the operation
        $handle = $this->conn->prepare('UPDATE modules SET root_url = ?, external = ?, icon_url = ?, levelNames = ? WHERE id = ?');
        $handle->bindValue(1, $root_url);
        $handle->bindValue(2, $external);
        $handle->bindValue(3, $icon_url);
        $handle->bindValue(4, $levelNames);
        $handle->bindValue(5, $module_id);
        return $handle->execute();
    }

    /**
    * Delete a module
    *
    * @param module_id The modules's DB ID
    */
    public function deleteModule($module_id)
    {
        // Validate data
        if (empty($module_id)) {
            $this->error = "All fields are required";
            return false;
        }

        // Get the technical permission name
        $handle = $this->conn->prepare('SELECT pem_name FROM modules WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $module_id);
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
        $pem_name = $handle->fetchAll(\PDO::FETCH_ASSOC)[0]['pem_name'];

        // Remove the permission from the users table
        $handle = $this->conn->prepare('ALTER TABLE `users` DROP COLUMN `' . $pem_name . '`');
        if ($handle->execute()) {
            // Remove it from the modules table
            $handle = $this->conn->prepare('DELETE FROM modules WHERE id = ?');
            $handle->bindValue(1, $module_id);
            return $handle->execute();
        } else {
            return false;
        }
    }

    /**
    * Retrieve all the dynamic confiurations
    *
    * EX: The home page message
    */
    public function getDynamicConfig()
    {
        // Pull the data
        $handle = $this->conn->prepare('SELECT * FROM config ORDER BY id');
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
        $results = $handle->fetchAll(\PDO::FETCH_ASSOC);

        // Format it
        $output = array();
        foreach ($results as $r) {
            $output[$r['key']] = $r['value'];
        }

        return $output;
    }

    /**
    * Retrieve all the dynamic confiurations
    *
    * EX: The home page message
    *
    * @param key The config dictionary key
    * @param value The config dictionary value
    */
    public function updateDynamicConfig($key, $value)
    {
        // Validate data
        if (empty($key)) {
            $this->error = "All fields are required";
            return false;
        }

        // Perform the operation
        $handle = $this->conn->prepare('UPDATE config SET `value` = ? WHERE `key` = ?');
        $handle->bindValue(1, $value);
        $handle->bindValue(2, $key);
        return $handle->execute();
    }
}
