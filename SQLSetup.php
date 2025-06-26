<?php
try {
    // Pokedex Data File
    $fName = "pokemon_data.csv";
    $file = fopen($fName, "r") or die("pokemon_data.csv not found!");

    // PDO Setup
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Database Creation
    $pdo->exec("DROP DATABASE IF EXISTS proj3;");
    $pdo->exec("CREATE DATABASE proj3");
    $pdo->exec("USE proj3");

    // Pokedex Table Creation
    $pdo->exec("CREATE TABLE pokedex(
            id INT PRIMARY KEY,
            name VARCHAR(20),
            type1 VARCHAR(20),
            type2 VARCHAR(20),
            total INT,
            hp INT,
            att INT,
            def INT,
            spAtt INT,
            spDef INT,
            speed INT,
            legend BIT);");

    // Load Pokedex Data
    while(!feof($file)) {
        $dataArr = explode(",", fgets($file));
        if ($dataArr[11] != 1) {  // Only want Gen1
            break;
        }
        if (!str_contains($dataArr[1], "Mega")) {  // Do not want Mega evolutions
            array_splice($dataArr, 11, 1);  // Remove Gen data
            if (!strcmp(trim($dataArr[11]), "FALSE")) {
                $dataArr[11] = False;
            }
            else {
                $dataArr[11] = True;
            }
            $sql = "INSERT INTO pokedex 
                    (id, name, type1, type2, total, hp, att, def, spAtt, spDef, speed, legend) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $pdo->prepare($sql)->execute($dataArr);
        }
    }
}
catch (PDOException $e) {
    die($e->getMessage());
}
?>