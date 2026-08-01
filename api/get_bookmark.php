<?php

session_start();

header("Content-Type: application/json");

require_once("../config/database.php");

/* User must be logged in */

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => true,
        "bookmarks" => []
    ]);

    exit();

}

$user_id = $_SESSION["user_id"];

/* Get all bookmarked tool IDs */

$sql = "
SELECT tool_id
FROM bookmarks
WHERE user_id = :user
ORDER BY created_at DESC
";

$stmt = $conn->prepare($sql);

$stmt->bindValue(":user", $user_id);

$stmt->execute();

$bookmarks = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    "success" => true,
    "bookmarks" => array_map("intval", $bookmarks)
]);

?>
