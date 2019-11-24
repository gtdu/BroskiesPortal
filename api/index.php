<?php

include_once("../init.php");

$helper = new PermissionHelper($config);
$helper->getData($_REQUEST['api_key'], $_REQUEST['session_token']);
