<?php
try {
    $conStr = "mysql:host=localhost;dbname=proj3";
    $user = "test";
    $pass = "test";

    $pdo = new PDO($conStr, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE TABLE pokedex(
            id INT AUTO_INCREMENT PRIMARY KEY,
            Name VARCHAR(20),
            type1 VARCHAR(20),
            type2 VARCHAR(20),
            total INT,
            hp INT,
            att INT,
            def INT,
            spAtt INT,
            spDef INT,
            speed INT,
            legend BIT);";
    $pdo->exec($sql);
}
catch (PDOException $e) {
    die($e->getMessage());
}
?>