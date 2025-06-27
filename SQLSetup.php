<?php
try {
    // PDO Setup
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Database Creation
    $pdo->exec("DROP DATABASE IF EXISTS proj3;");
    $pdo->exec("CREATE DATABASE proj3");
    $pdo->exec("USE proj3");

    // Pokedex Data File
    $fName = "pokemon_data.csv";
    $file = fopen($fName, "r") or die("pokemon_data.csv not found!");

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
    $valStr = "?" . str_repeat(",?", 11);
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
                    VALUES ($valStr)";
            $pdo->prepare($sql)->execute($dataArr);
        }
    }

    // Type Bonus Data File
    $fName = "type_bonus.csv";
    $file = fopen($fName, "r") or die("type_bonus.csv not found!");

    // Type Bonus Table Creation
    $pdo->exec("CREATE TABLE typeBonus(type VARCHAR(20) PRIMARY KEY);");
    $typeArr = explode(",", fgets($file));
    foreach ($typeArr as $type) {
        $sql = "ALTER TABLE typeBonus ADD $type DECIMAL(2,1);";
        $pdo->exec($sql);
    }

    // Load Type Bonus Data
    $colStr = "type, " . implode(", ", $typeArr);
    $valStr = "?" . str_repeat(",?", count($typeArr));
    while(!feof($file)) {
        $dataArr = explode(",", fgets($file));
        $sql = "INSERT INTO typeBonus ($colStr) VALUES ($valStr)";
        $pdo->prepare($sql)->execute($dataArr);
    }

    echo "Database Created!";
}
catch (PDOException $e) {
    die($e->getMessage());
}
?>