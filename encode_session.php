<?php
// Start the session
session_start();
header('Content-Type: application/json');

echo json_encode($_SESSION);

// var_dump($_SESSION);
