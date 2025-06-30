<?php
// Start the session
session_start();
header('Content-Type: application/json');

// $pokemonList = [
//     [
//         "img" => "./proj3_images/1st_Generation/007Squirtle.png",
//         "name" => "Squirtle",
//         "types" => ["Water"],
//         "abilities" => ["Torrent", "Rain Dish"],
//         "stats" => [44, 48, 65, 50, 64, 43, 314]

//     ],
//     [
//         "img" => "./proj3_images/1st Generation/004Charmander.png",
//         "name" => "Charmander",
//         "types" => ["Fire"],
//         "abilities" => ["Blaze", "Solar Power"],
//         "stats" => [39, 52, 43, 60, 50, 65, 309]
//     ],
//     [
//         "img" => "./proj3_images/1st Generation/001Bulbasaur.png",
//         "name" => "Bulbasaur",
//         "types" => ["Grass", "Poison"],
//         "abilities" => ["Overgrow", "Chlorophyll"],
//         "stats" => [45, 49, 49, 65, 65, 45, 318]
//     ],
//     [
//         "img" => "./proj3_images/1st Generation/025Pikachu.png",
//         "name" => "Pikachu",
//         "types" => ["Electric"],
//         "abilities" => ["Static", "Lightning Rod"],
//         "stats" => [35, 55, 40, 50, 50, 90, 320]
//     ],
//     [
//         "img" => "./proj3_images/1st Generation/143Snorlax.png",
//         "name" => "Snorlax",
//         "types" => ["Normal"],
//         "abilities" => ["Immunity", "Thick Fat"],
//         "stats" => [160, 110, 65, 65, 110, 30, 540]
//     ],
//     [
//         "img" => "./proj3_images/1st Generation/150Mewtwo.png",
//         "name" => "Mewtwo",
//         "types" => ["Psychic"],
//         "abilities" => ["Pressure", "Unnerve"],
//         "stats" => [106, 110, 90, 154, 90, 130, 680]
//     ]
// ];

// $_SESSION["pokemonList"] = $pokemonList;

echo json_encode($_SESSION);

// var_dump($_SESSION);
