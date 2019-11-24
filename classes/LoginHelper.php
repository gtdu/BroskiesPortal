<?php

use Ramsey\Uuid\Uuid;

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

        $handle = $this->conn->prepare('SELECT id, password FROM users WHERE email = ? LIMIT 1');
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
}
