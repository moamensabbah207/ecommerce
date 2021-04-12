<?php
    $dsn = "mysql:host=localhost;dbname=ecommerce";
    $username ="root";
    $password = "";

    try{
        $con = new PDO($dsn, $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        // echo "connect";
    }catch(PDOException $error){
        echo $error->getMessage();

    }
?>