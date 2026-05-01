<?php
try{
    $pdo=new PDO('mysql:host=localhost:3301;dbname=ecommerce','root','');
}
catch(PDOException $e){
    die("Error : ".$e->getMessage());
}

?>