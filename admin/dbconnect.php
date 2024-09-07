<?php

$db_name = "mysql:host=localhost;dbname=furni;";
$user_name = 'root';
$user_password = '';

try
{
    $conn = new PDO($db_name, $user_name, $user_password);
    
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

?>