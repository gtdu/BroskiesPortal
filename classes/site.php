<?php

class site
{
    private $headers;
    private $footers;
    private $page;
    private $title;
    private $errors;
    private $success;

    public $userEmail;
    public $userName;
    public $userCorePem;
    public $userID;

    public function __construct($pageTitle)
    {
        $this->headers = array();
        $this->footers = array();
        $this->title = $pageTitle;
    }

    public function render()
    {
        foreach ($this->headers as $header) {
            include $header;
        }
        echo '<title>' . $this->title . '</title>';

        $this->renderErrors();
        $this->renderSuccess();

        $this->page->render();

        foreach ($this->footers as $footer) {
            include $footer;
        }
    }

    public function addHeader($file)
    {
        $this->headers[] = $file;
    }

    public function addFooter($file)
    {
        $this->footers[] = $file;
    }

    public function setPage(page $page)
    {
        $this->page = $page;

        global $config;

        if ($this->page->requiresAuth) {
            // Check that a token is set
            if (empty($_SESSION['token'])) {
                session_destroy();
                header("Location: index.php");
                die();
            }

            // Validate session token
            $handle = $config['dbo']->prepare('SELECT id, name, email, core FROM users WHERE session_token = ?');
            $handle->bindValue(1, $_SESSION['token']);
            $handle->execute();
            $result = $handle->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($result)) {
                session_destroy();
                header("Location: index.php");
                die();
            }

            // Store user information
            $this->userName = $result[0]['name'];
            $this->userEmail = $result[0]['email'];
            $this->userID = $result[0]['id'];
            $this->userCorePem = $result[0]['core'];
        }
    }

    private function renderErrors()
    {
        $errorOutput = "";
        if (empty($_SESSION['error'])) {
            return;
        }
        foreach ($_SESSION['error'] as $error) {
            echo '<script>alert("';
            echo $error;
            echo '")</script>';
        }

        unset($_SESSION['error']);
    }

    private function renderSuccess()
    {
        if ($_SESSION['success']) {
            echo '<script>alert("Success!")</script>';
            unset($_SESSION['success']);
        }
    }

    public function getSQLError() {
        global $config;
        return $config['dbo']->errorInfo()[2];
    }
}
