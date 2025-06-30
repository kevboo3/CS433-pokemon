<?php
require "utils.php";

// setup()
// Checks for database and creates it if needed
function setup() {
    conLog("Checking Database");
    try {
        // PDO Init
        $pdo = new PDO("mysql:host=localhost", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Database Check/Creation
        if (!$pdo->exec("CREATE DATABASE IF NOT EXISTS Proj3")) {
            $pdo->exec("USE Proj3");
            conLog("Database Ready");
            return;
        }

        // If new database was created
        conLog("Database Setup Required");
        $pdo->exec("USE Proj3");

        // Type Bonus Table Creation
        $file = fopen(FPATH . TYPEDATA, "r") or die(conLog("ERROR - " . TYPEDATA . " not found!"));
        $colArr = explode(",", fgets($file));  // List of column names
        $pdo->exec("CREATE TABLE TypeBonus($colArr[0] VARCHAR(20) PRIMARY KEY);");
        for ($i = 1; $i < count($colArr); $i++) {
            $pdo->exec("ALTER TABLE TypeBonus ADD $colArr[$i] DECIMAL(2,1);");
        }
        conLog("Type Bonus Table Created!");

        // Load Type Bonus Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        while(!feof($file)) {  // Read type_bonus.csv
            $dataArr = explode(",", fgets($file));
            $sql = "INSERT INTO TypeBonus ($colStr) VALUES ($valStr)";
            $pdo->prepare($sql)->execute($dataArr);
        }
        conLog("Type Bonus Data Loaded");

        // Pokedex Table Creation
        $file = fopen(FPATH . POKEDATA, "r") or die(conLog("ERROR - " . POKEDATA . " not found!"));
        $colArr = explode(",", fgets($file));  // List of column names
        $pdo->exec("CREATE TABLE Pokedex($colArr[0] INT PRIMARY KEY);");  // Column: Id
        for ($i = 1; $i < count($colArr); $i++) {
            $sql = "ALTER TABLE Pokedex ADD $colArr[$i] ";
            if ($i < 4) {  // Columns: Name, Type1, Type2
                $sql .= "VARCHAR(20);";
            }
            else if ($i < 11) {  // Columns: Total, Hp, Att, Def, SpAtt, SpDef, Speed
                $sql .= "INT;";
            }
            else {  // Column: Legendary
                $sql .= "BIT;";
            }
            $pdo->exec($sql);
        }
        for ($i = 2; $i < 4; $i++) {  // Links Type1 and Type2 to TypeBonus(Type)
            $pdo->exec("ALTER TABLE Pokedex ADD FOREIGN KEY ($colArr[$i]) REFERENCES TypeBonus(Type);");
        }
        conLog("Pokedex Table Created");

        // Load Pokedex Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        while(!feof($file)) {                                // Read pokemon_data.csv
            $dataArr = explode(",", fgets($file));
            if ($dataArr[11] != 1) {                         // Break at Gen2
                break;
            }
            if (!str_contains($dataArr[1], "Mega")) {        // Do not want Mega evolutions
                array_splice($dataArr, 11, 1);               // Remove Gen data
                if ($dataArr[3] == "") {
                    $dataArr[3] = NULL;
                }
                if (!strcmp(trim($dataArr[11]), "FALSE")) {  // Set Legendary bit
                    $dataArr[11] = False;
                }
                else {
                    $dataArr[11] = True;
                }
                $sql = "INSERT INTO Pokedex ($colStr) VALUES ($valStr)";
                $pdo->prepare($sql)->execute($dataArr);
            }
        }
        conLog("Pokedex Data Loaded");

        // Moves Table Creation
        $file = fopen(FPATH . MOVEDATA, "r") or die(conLog("ERROR - " . MOVEDATA . " not found!"));
        $colArr = explode(",", fgets($file));  // List of column names
        $pdo->exec("CREATE TABLE Moves($colArr[0] VARCHAR(20) PRIMARY KEY);");  // Column: Name
        for ($i = 1; $i < count($colArr); $i++) {
            $sql = "ALTER TABLE Moves ADD $colArr[$i] ";
            if ($i == 1 or $i == 2) {  // Columns: Type, Category
                $sql .= "VARCHAR(20);";
            }
            else if ($i > 5) {         // Column: Effect
                $sql .= "VARCHAR(255);";
            }
            else {                     // Columns: Power, Accuracy, PP
                $sql .= "INT;";
            }
            $pdo->exec($sql);
        }
        $pdo->exec("ALTER TABLE Moves ADD FOREIGN KEY ($colArr[1]) REFERENCES TypeBonus(Type);"); // Links Type to TypeBonus(Type)
        conLog("Moves Table Created");
        
        // Load Move Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        $sql = "INSERT INTO Moves ($colStr) VALUES ($valStr)";
        while(!feof($file)) {                                    // Read move_data.csv
            $dataArr = explode(",", fgets($file));
            $dataArr[2] = trim($dataArr[2]);                     // Remove whitespace
            $dataArr[6] = str_replace("$", ",", trim(trim($dataArr[6]), '"')) ?: NULL;  // Remove whitespace and quotes
            $pdo->prepare($sql)->execute($dataArr);
        }
        conLog("Moves Data Loaded");

        // Learn Table Creation
        $colArr = ["Name", "Level", "Hm", "Tm"];
        
        $pdo->exec("CREATE TABLE Learn($colArr[0] VARCHAR(20) PRIMARY KEY);");
        for ($i = 1; $i < count($colArr); $i++) {
            $pdo->exec("ALTER TABLE Learn ADD $colArr[$i] VARCHAR(512);");
        }
        conLog("Learn Table Created");

        // Load Learn Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        $file = fopen(FPATH . LEARNDATA, "r") or die(conLog("ERROR - " . LEARNDATA . " not found!"));
        $jsonStr = "";
        while(!feof($file)) {
            $jsonStr .= fgets($file);
        }
        $jsonArr = json_decode($jsonStr, True);
        $dataArr = ["", "", "", ""];
        $sql = "INSERT INTO Learn ($colStr) VALUES ($valStr)";
        for ($i = 0; $i < count($jsonArr); $i++) {
            $dataArr[0] = $jsonArr[$i]["pokemon"];
            $dataArr[1] = implode(",", array_unique($jsonArr[$i]["level up moves"]));
            $dataArr[2] = implode(",", $jsonArr[$i]["hm moves"]) ?: NULL;
            $dataArr[3] = implode(",", $jsonArr[$i]["tm moves"]) ?: NULL;
            $pdo->prepare($sql)->execute($dataArr);
        }
        $pdo->exec("ALTER TABLE Learn ADD COLUMN Id INT FIRST;");
        $pdo->exec("ALTER TABLE Learn ADD FOREIGN KEY (Id) REFERENCES Pokedex(Id);");
        $pdo->exec("UPDATE Learn INNER JOIN Pokedex ON Learn.Name = Pokedex.Name Set Learn.Id = Pokedex.Id");
        conLog("Learn Data Loaded");
        conLog("Database Ready");
    }
    catch (PDOException $e) {
        die(conLog("ERROR - " . $e->getMessage()));
    }
}
?>