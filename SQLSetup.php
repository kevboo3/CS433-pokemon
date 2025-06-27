<?php
define("FPATH", "./dataFiles/");
define("POKEDATA", "pokemon_data.csv");
define("TYPEDATA", "type_bonus.csv");
define("MOVEDATA", "moves_data.csv");
define("LEARNDATA", "learn_data.json");

// conLog()
// Logs messages to javascript console
function conLog($str) {
    echo "<script>console.log('SQLSetup: " . $str . "' );</script>";
}

// setup()
// Checks for database and creates it if needed
function setup() {
    try {
        // PDO Setup
        $pdo = new PDO("mysql:host=localhost", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Database Check/Creation
        if (!$pdo->exec("CREATE DATABASE IF NOT EXISTS Proj3")) {
            return;
        }

        // If new database was created
        conLog("Database Setup Required");
        $pdo->exec("USE Proj3");

        // Type Bonus Table Creation
        $file = fopen(FPATH . TYPEDATA, "r") or die(conLog("ERROR - " . TYPEDATA . " not found!"));
        $colArr = explode(",", fgets($file));  // List of column names
        $pdo->exec("CREATE TABLE typeBonus($colArr[0] VARCHAR(20) PRIMARY KEY);");
        for ($i = 1; $i < (count($colArr)); $i++) {
            $pdo->exec("ALTER TABLE typeBonus ADD $colArr[$i] DECIMAL(2,1);");
        }
        conLog("Type Bonus Table Created!");

        // Load Type Bonus Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        while(!feof($file)) {  // Read type_bonus.csv
            $dataArr = explode(",", fgets($file));
            $sql = "INSERT INTO typeBonus ($colStr) VALUES ($valStr)";
            $pdo->prepare($sql)->execute($dataArr);
        }
        conLog("Type Bonus Data Loaded");

        // Pokedex Table Creation
        $file = fopen(FPATH . POKEDATA, "r") or die(conLog("ERROR - " . POKEDATA . " not found!"));
        $colArr = explode(",", fgets($file));  // List of column names
        $pdo->exec("CREATE TABLE Pokedex($colArr[0] INT PRIMARY KEY);");  // Column: Id
        for ($i = 1; $i < (count($colArr)); $i++) {
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
        for ($i = 2; $i < 4; $i++) {  // Links Type1 and Type2 to typeBonus(Type)
            $pdo->exec("ALTER TABLE Pokedex ADD FOREIGN KEY ($colArr[$i]) REFERENCES typeBonus(Type);");
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
        for ($i = 1; $i < (count($colArr)); $i++) {
            $sql = "ALTER TABLE Moves ADD $colArr[$i] ";
            if ($i == 1 or $i == 2) {  // Columns: Type, Category
                $sql .= "VARCHAR(20);";
            }
            else if ($i == 6) {         // Column: Effect
                $sql .= "VARCHAR(255);";
            }
            else {                     // Columns: Power, Accuracy, PP
                $sql .= "INT;";
            }
            $pdo->exec($sql);
        }
        $pdo->exec("ALTER TABLE Moves ADD FOREIGN KEY ($colArr[1]) REFERENCES typeBonus(Type);"); // Links Type to typeBonus(Type)
        conLog("Moves Table Created");
        
        // Load Move Data
        $colStr = implode(", ", $colArr);
        $valStr = "?" . str_repeat(",?", count($colArr) - 1);
        while(!feof($file)) {                                // Read move_data.csv
            $dataArr = explode(",", fgets($file), 7);
            $dataArr[2] = trim($dataArr[2]);                 // Remove whitespace
            $dataArr[6] = trim(trim($dataArr[6]), '"');      // Remove whitespace and quotes
            $sql = "INSERT INTO Moves ($colStr) VALUES ($valStr)";
            $pdo->prepare($sql)->execute($dataArr);
        }
        conLog("Moves Data Loaded");

        // Learn Table Creation
        $file = fopen(FPATH . LEARNDATA, "r") or die(conLog("ERROR - " . LEARNDATA . " not found!"));

    }
    catch (PDOException $e) {
        die(conLog("ERROR - " . $e->getMessage()));
    }
}
conLog("Checking Database");
setup();
conLog("Database Ready!");
?>