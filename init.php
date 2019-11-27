<?php

require "vendor/autoload.php";
include_once("stdlib.php");

error_reporting(0);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

$ini = parse_ini_file("config.ini", true)["du"];

try {
    $pdo = new PDO(
        'mysql:host=' . $ini['db_host'] . ';dbname=' . $ini['db_name'] . ';charset=utf8mb4',
                        $ini['db_username'],
                        $ini['db_password'],
                        array(
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
                            PDO::ATTR_PERSISTENT => false
                        )
                    );
} catch (Exception $e) {
    die($e);
}

$config = array(
    'dbo' => $pdo,
    'email_password' => $ini['email_password']
);

$errors = array();

// Start session if not already created
if (session_status() == PHP_SESSION_NONE) {
    session_name("du");
    session_start();
}

date_default_timezone_set('America/New_York');
