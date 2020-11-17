<?php

include '../init.php';

$lh = new LoginHelper($config);

// Setup curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Curl back to Slack using the returned code to get an access token
curl_setopt($ch, CURLOPT_URL, "https://slack.com/api/oauth.access?client_id=" . $config['slack_id'] . "&client_secret=" . $config['slack_secret'] . "&code=" . $_REQUEST['code']);
$output = json_decode(curl_exec($ch));
$user_id = $output->user_id;
if (empty($user_id)) {
    $_SESSION['error'][0] = "Error getting the access token";
    header("Location: index.php");
    die();
}
curl_close($ch);

// Check if the user ID is already in the DB
if ($lh->validateLogin($user_id)) {
    if ($lh->canLogin($user_id)) {
        header("Location: dashboard.php");
        setcookie('broskies_portal', $user_id, time() + (86400 * 30), "/"); // 86400 = 1 day
    } else {
        $_SESSION['error'][0] = "Incorrect credentials";
        header("Location: index.php");
    }
} else {
    $fullName = $output->user->name;
    $names = explode(" ", $fullName);

    if ($lh->newUser($names[1] . ", " . $names[0], $user_id)) {
        header("Location: dashboard.php");
        setcookie('broskies_portal', $user_id, time() + (86400 * 30), "/"); // 86400 = 1 day
    } else {
        $_SESSION['error'][0] = "Account not found";
        header("Location: index.php");
    }
}
