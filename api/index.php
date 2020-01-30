<?php

/**
* This file serves as the API endpoing for external modules to communicate with the core system
*
* @param api_key This is the modules API Key generated on creation
* @param session_token This is the user's session token that was passed when the module was loaded
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/

include_once("../init.php");

$helper = new APIHelper($config);

if ($helper->performAuth($_REQUEST['api_key'], $_REQUEST['session_token'])) {
    echo json_encode($helper->getData());
} else {
    http_response_code($helper->getErrorCode());
}
