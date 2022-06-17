<?php


$host = 'localhost';
$db = 'notebook';
$user = 'root';
$pass = 'root';
$dsn = "mysql:host={$host};dbname={$db};charset=utf8";
$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];


$connect = new PDO($dsn, $user, $pass, $opt);
    if (!$connect) {
        die('Connect error');
    }