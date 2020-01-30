<?php

/**
* This is the overarching site class that manages rendering everything
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
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

    /**
    * Setup the site
    *
    * @param pageTitle String for HTML <title> tags
    */
    public function __construct($pageTitle)
    {
        $this->headers = array();
        $this->footers = array();
        $this->title = $pageTitle;
    }

    /**
    * Begin rendering the site (headers, meta, content, etc.)
    *
    */
    public function render()
    {
        // Render all the headers
        foreach ($this->headers as $header) {
            include $header;
        }

        // Set the page title
        echo '<title>' . $this->title . '</title>';

        // Render any success or error banners
        $this->renderErrors();
        $this->renderSuccess();

        // Render page body
        $this->page->render();

        // Render all the footers
        foreach ($this->footers as $footer) {
            include $footer;
        }
    }

    /**
    * Public setter for headers
    */
    public function addHeader($file)
    {
        $this->headers[] = $file;
    }

    /**
    * Public setter for footers
    */
    public function addFooter($file)
    {
        $this->footers[] = $file;
    }

    /**
    * Public setter for page content
    */
    public function setPage(page $page)
    {
        $this->page = $page;

        // TODO: Fix this breach in best practices
        global $config;

        // Validate the user session if the page requires auth
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

            // Invalid session token
            if (empty($result)) {
                // Return them to login page
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

    /**
    * Loop through each error and create a javascript alert
    *
    */
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

    /**
    * Check if the action was successful and render a javascript alert
    *
    */
    private function renderSuccess()
    {
        if ($_SESSION['success']) {
            echo '<script>alert("Success!")</script>';
            unset($_SESSION['success']);
        }
    }
}
