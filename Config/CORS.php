<?php
header("Access-Control-Allow-Origin: http://localhost:4200"); // Allow all origins
header("Access-Control-Allow-Headers: Content-Type,Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Enable exceptions

$input = json_decode(file_get_contents('php://input'), true);

?>