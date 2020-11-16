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
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return the user's and associated info
     *
     * @param $id int The users DB ID
     * @return array
     */
    public function getUserByID($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC)[0];
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
     * Update a specific permission for the user
     *
     * @param $user_id int The user's DB ID
     * @param $perm_field string The technical permission name
     * @param $perm_level int The new value
     * @return bool
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
     * Remove a user
     *
     * @param $user_id int The user's DB ID
     * @return bool
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
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return all the modules and associated info about a module
     *
     * @param $id int Module DB ID
     * @return bool
     */
    public function getModuleByID($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM modules WHERE id = ? LIMIT 1');
        $handle->bindValue(1, $id);
        if (!$handle->execute()) {
            $this->error = $this->conn->errorInfo()[2];
            return false;
        }
        return $handle->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    /**
     * Create a new user
     *
     * @param $module_name string The modules's name
     * @param $root_url string The URL of the module's code
     * @param $external string Should the module open in a new tab
     * @param $defaultAccess int Default access level
     * @param $icon_url string The URL of the module's icon
     * @param $levelNames string The different level names
     * @return bool
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
        try {
            $api_key = Uuid::uuid4()->toString();
        } catch (Exception $e) {
            $this->error = "Unable to generate API key";
            return false;
        }

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
     * @param $module_id int The modules's DB ID
     * @param $root_url string The URL of the module's code
     * @param $external string If the module should open in a new tab or not
     * @param $icon_url string The URL of the module's icon
     * @param $levelNames string The different level names
     * @return bool
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
     * @param $module_id int The modules's DB ID
     * @return bool
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
        $pem_name = $handle->fetchAll(PDO::FETCH_ASSOC)[0]['pem_name'];

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
    * Retrieve all the dynamic configurations
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
        $results = $handle->fetchAll(PDO::FETCH_ASSOC);

        // Format it
        $output = array();
        foreach ($results as $r) {
            $output[$r['key']] = $r['value'];
        }

        return $output;
    }

    /**
     * Retrieve all the dynamic configurations
     *
     * EX: The home page message
     *
     * @param $key string The config dictionary key
     * @param $value string The config dictionary value
     * @return bool
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
