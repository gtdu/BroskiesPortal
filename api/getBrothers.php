<?php

/**
* This file serves as the API endpoint for external modules to communicate with the core system.
* If the user is an officer then this will allow the external module to get all the brotherhood data
*
* @param $api_key string This is the modules API Key generated on creation
* @param $session_token string This is the user's session token that was passed when the module was loaded
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/

include_once("../init.php");

$helper = new APIHelper($config);

if ($helper->performAuth($_REQUEST['api_key'], $_REQUEST['session_token'])) {
    if ($helper->getLevel() > 1) {
        echo json_encode($helper->getAllData());
    } else {
        http_response_code(401);
    }
} else {
    http_response_code($helper->getErrorCode());
}
