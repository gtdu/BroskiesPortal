<?php

function init_site(site $site)
{
    $site->addHeader("../includes/header.php");
    $site->addFooter("../includes/footer.php");
}

function logMessage($message)
{
    global $config;

    print($message);
}

function devEnv()
{
    return gethostname() == "Ryans-MBP";
}

function currentPage($name)
{
    echo $name;
    echo basename(__FILE__, '.php') == $name ? "active" : "";
}

function removeNonAlphaNumeric($str)
{
    return preg_replace("/[^A-Za-z0-9 ]/", "", $str);
}

function str_replace_last($search, $replace, $str)
{
    if (($pos = strrpos($str, $search)) !== false) {
        $search_length  = strlen($search);
        $str    = substr_replace($str, $replace, $pos, $search_length);
    }
    return $str;
}
