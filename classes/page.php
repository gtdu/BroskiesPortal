<?php

/**
* This is the overarching page class that manages page content
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class page
{
    private $content;
    public $requiresAuth;

    public function __construct($loginNeeded = false)
    {
        $this->requiresAuth = $loginNeeded;
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
