<?php

class PermissionHelper
{
    private $config;
    private $conn;

    public function __construct($input)
    {
        $this->config = $input;
        $this->conn = $input['dbo'];
    }

    public function getData($api_key, $session_token)
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

                echo json_encode($data);
            } else {
                http_response_code(401);
                die();
            }
        } else {
            http_response_code(403);
            die();
        }
    }
}
