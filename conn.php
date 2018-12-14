<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=if17_kellrasm;charset=utf8', 'if17', 'if17');
} catch (Exception $e) {
    die('Error : ' . $e->getMessage());
}

$serverHost = "localhost";
$serverUsername = "if17";
$serverPassword = "if17";
