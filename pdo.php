<?php
try{
    $dbhost="localhost";
    $dbname="blogs";
    $dbuser="root";
    $dbpass="";
$pdo=new PDO("mysql:host=$dbhost;dbName=$dbname",$dbuser,$dbpass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
    echo "<p style='position:fixed;
    top:50%;  z-index: 200;'>";
    echo $e->getMessage();
    echo "</p>";
    return ;
}


?>