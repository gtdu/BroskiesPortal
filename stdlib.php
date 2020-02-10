<?php

function init_site(site $site)
{
    $site->addHeader("../includes/header.php");
    $site->addFooter("../includes/footer.php");
}

function logMessage($message)
{
    global $config;
    // TODO Implement a better loggin system
    print($message);
}

function getCurrentPermissions($config)
{
    $handle = $config['dbo']->prepare('SELECT * FROM users WHERE session_token = ? LIMIT 1');
    $handle->bindValue(1, $_SESSION['token']);
    $handle->execute();
    return $handle->fetchAll(\PDO::FETCH_ASSOC)[0];
}

function devEnv()
{
    return gethostname() == "Ryans-MBP";
}

function removeNonAlphaNumeric($str)
{
    return preg_replace("/[^A-Za-z0-9 ]/", "", $str);
}
