<?php

include '../init.php';

$lh = new LoginHelper($config);

// Setup curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Curl back to Slack using the returned code to get an access token
curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/oauth.access?client_id=" . $config['slack_id'] . "&client_secret=" . $config['slack_secret'] . "&code=" . $_REQUEST['code']);
$output = json_decode(curl_exec($ch));
$access_token = $output->user_id;
if (empty($access_token)) {
    $_SESSION['error'][0] = "Error getting the access token";
    header("Location: index.php");
    die();
}

// Check if the user ID is already in the DB
if ($lh->validateLogin($access_token)) {
    header("Location: dashboard.php");
} else {
    $_SESSION['error'][0] = "Incorrect credentials";
    header("Location: index.php");
}

curl_close($ch);

?>
