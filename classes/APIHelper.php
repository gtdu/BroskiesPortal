<?php

/**
* Class to manage all the API related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class APIHelper extends Helper
{
    private $data;
    private $errorCode = 0;
    private $level = 0;

    /**
     * Check if the credentials provided are valid and save the resulting query or error info
     *
     *
     * @param $api_key string This is the modules API Key generated on creation
     * @param $session_token string This is the user's session token that was passed when the module was loaded
     * @return bool
     */
    public function performAuth($api_key, $session_token)
    {
        if (empty($api_key) || empty($session_token)) {
            $this->error = "All fields are required";
            return false;
        }

        $handle = $this->conn->prepare('SELECT pem_name FROM modules WHERE api_token = ? LIMIT 1');
        $handle->bindValue(1, $api_key);
        $handle->execute();
        $result = $handle->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $handle = $this->conn->prepare('SELECT `' . $result[0]['pem_name'] . '`, name, slack_id, id, core FROM users WHERE session_token = ? LIMIT 1');
            $handle->bindValue(1, $session_token);
            $handle->execute();
            $result2 = $handle->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result2)) {
                $data = array(
                    'name' => $result2[0]['name'],
                    'slack_id' => $result2[0]['slack_id'],
                    'level' => $result2[0][$result[0]['pem_name']]
                );

                $this->level = $result2[0]['core'];

                $this->data = $data;
                return true;
            } else {
                $this->errorCode = 401;
                return false;
            }
        } else {
            $this->errorCode = 403;
            return false;
        }
    }

    /**
    * Return the data queries in performAuth
    *
    * @return array The data object or NULL depending on if query was successful
    */
    public function getData()
    {
        return $this->data;
    }

    /**
    * Return the error code that results from performAuth
    *
    * @return Integer The error code
    */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
    * Return the data queries in performAuth
    *
    * @return Integer The user's core level
    */
    public function getLevel()
    {
        return $this->level;
    }

    /**
    * Return the data queries in performAuth
    *
    * @return array The data of all the brothers
    */
    public function getAllData()
    {
        $handle = $this->conn->prepare('SELECT id, name, slack_id FROM users WHERE core > 0 ORDER BY name');
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }
}
