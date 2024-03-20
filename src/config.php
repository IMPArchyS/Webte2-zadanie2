<?php

$hostname = "mysql";
$username = "root";
$password = "root";
$dbname = "webte2zadanie2";

define("HOSTNAME", "mysql");
define("USERNAME", "root");
define("PASSWORD", "root");
define("DBNAME", "webte2zadanie2");

$dbconfig = array(
    'hostname' => 'mysql',
    'username' => 'root',
    'password' => 'root',
    'dbname' => 'webte2zadanie2',
);

$conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}