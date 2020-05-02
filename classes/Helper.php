<?php

class Helper {
    protected $config;
    protected $conn;
    protected $error;

    /**
    * Setup the helper
    *
    * @param $input array The config array (contains config info, DB object, etc.)
    */
    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
    }

    public function getErrorMessage() {
        return $this->error;
    }
}
