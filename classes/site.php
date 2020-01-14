<?php

class site
{
    private $headers;
    private $footers;
    private $page;
    private $title;
    private $errors;
    private $success;

    public function __construct($pageTitle)
    {
        $this->headers = array();
        $this->footers = array();
        $this->title = $pageTitle;
    }

    public function render()
    {
        if ($this->page->requiresAuth && empty($_SESSION['token'])) {
            header("Location: index.php");
            die();
        }

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
    }

    private function renderErrors()
    {
        $errorOutput = "";
        if (empty($_SESSION['errors'])) {
            return;
        }
        foreach ($this->errors as $error) {
            echo '<script>alert("';
            echo $error;
            echo '")</script>';
        }

        unset($_SESSION['errors']);
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
