<?php
session_start();

header("Content-Type: application/json");

require_once("../config/database.php");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Login required"
    ]);
    exit();
}

$user_id = $_SESSION["user_id"];

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input["tool_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Tool ID missing"
    ]);
    exit();
}

$tool_id = (int)$input["tool_id"];

/* Check if bookmark exists */

$check = $conn->prepare("
SELECT id
FROM bookmarks
WHERE user_id=:user_id
AND tool_id=:tool_id
LIMIT 1
");

$check->execute([
    ":user_id"=>$user_id,
    ":tool_id"=>$tool_id
]);

if($check->fetch()){

    $delete=$conn->prepare("
    DELETE FROM bookmarks
    WHERE user_id=:user_id
    AND tool_id=:tool_id
    ");

    $delete->execute([
        ":user_id"=>$user_id,
        ":tool_id"=>$tool_id
    ]);

    echo json_encode([
        "success"=>true,
        "bookmarked"=>false
    ]);

}else{

    $insert=$conn->prepare("
    INSERT INTO bookmarks(user_id,tool_id)
    VALUES(:user_id,:tool_id)
    ");

    $insert->execute([
        ":user_id"=>$user_id,
        ":tool_id"=>$tool_id
    ]);

    echo json_encode([
        "success"=>true,
        "bookmarked"=>true
    ]);

}
?>
