<?php

class page
{
    private $content;
    public $requiresAuth;

    public function __construct($loginNeeded = false)
    {
        $this->requiresAuth = $loginNeeded;
    }

    public function __destruct()
    {
        // clean up here
    }

    public function render()
    {
        echo $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}
