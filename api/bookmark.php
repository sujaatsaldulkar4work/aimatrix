<?php

session_start();

header("Content-Type: application/json");

require_once("../config/database.php");

/* User must be logged in */

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Login required"
    ]);

    exit();

}

$user_id = $_SESSION["user_id"];

/* Read JSON body */

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["tool_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Tool ID missing"
    ]);

    exit();

}

$tool_id = (int)$data["tool_id"];

/* Check existing bookmark */

$check = $conn->prepare("
SELECT id
FROM bookmarks
WHERE user_id = :user
AND tool_id = :tool
");

$check->execute([
    ":user" => $user_id,
    ":tool" => $tool_id
]);

if ($check->fetch()) {

    /* Remove bookmark */

    $delete = $conn->prepare("
    DELETE FROM bookmarks
    WHERE user_id=:user
    AND tool_id=:tool
    ");

    $delete->execute([
        ":user"=>$user_id,
        ":tool"=>$tool_id
    ]);

    echo json_encode([
        "success"=>true,
        "bookmarked"=>false
    ]);

}
else{

    /* Add bookmark */

    $insert = $conn->prepare("
    INSERT INTO bookmarks(user_id,tool_id)
    VALUES(:user,:tool)
    ");

    $insert->execute([
        ":user"=>$user_id,
        ":tool"=>$tool_id
    ]);

    echo json_encode([
        "success"=>true,
        "bookmarked"=>true
    ]);

}
