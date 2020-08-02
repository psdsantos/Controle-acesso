<?php

try{
    //pedro
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=tcc', 'root', 'wrongpassword123');
} catch (Exception $e) {
    //fernando
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=tcc', 'root', '152603');
}
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);