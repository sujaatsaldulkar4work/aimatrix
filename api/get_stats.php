<?php

header("Content-Type: application/json");

require_once("../config/database.php");

$tools = $conn->query("SELECT COUNT(*) FROM tools")->fetchColumn();

$categories = $conn->query("SELECT COUNT(*) FROM categories")->fetchColumn();

$users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

echo json_encode([

    "success" => true,

    "tools" => (int)$tools,

    "categories" => (int)$categories,

    "users" => (int)$users

]);

?>
