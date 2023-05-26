<?php
$db = 'mysql:host=localhost;dbname=onlinenot';
$username = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
);

try {
    $pdo = new PDO($db, $username, $password, $options);
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}



?>