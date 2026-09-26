<?php
$host = "localhost";
$db = "SistLivr";
$password = "";
$username = "root";
$dbc = null;

try{
    $dbc = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){die("Connection failed " . $e->getMessage());}

function getPDO(): ?PDO{
    global $dbc;
    return $dbc;
}
?>