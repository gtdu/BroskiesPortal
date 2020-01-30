<?php

/**
* Class to manage all the API related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class APIHelper
{
    private $config;
    private $conn;
    private $data;
    private $errorCode;

    /**
    * Setup the helper
    *
    * @param input The config array (contains config info, DB object, etc.)
    */
    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
        $this->errorCode = 0; // No error
    }

    /**
    * Check if the credentials provided are valid and save the resulting query or error info
    *
    *
    * @param api_key This is the modules API Key generated on creation
    * @param session_token This is the user's session token that was passed when the module was loaded
    */
    public function performAuth($api_key, $session_token)
    {
        $handle = $this->conn->prepare('SELECT pem_name FROM modules WHERE api_token = ? LIMIT 1');
        $handle->bindValue(1, $api_key);
        $handle->execute();
        $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($result)) {
            $handle = $this->conn->prepare('SELECT `' . $result[0]['pem_name'] . '`, name, email, id FROM users WHERE session_token = ? LIMIT 1');
            $handle->bindValue(1, $session_token);
            $handle->execute();
            $result2 = $handle->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($result2)) {
                $data = array(
                    'name' => $result2[0]['name'],
                    'email' => $result2[0]['email'],
                    'level' => $result2[0][$result[0]['pem_name']]
                );

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
    * @return Array The data object or NULL depending on if query was successful
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
}
