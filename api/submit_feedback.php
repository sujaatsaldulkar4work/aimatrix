<?php

session_start();

header("Content-Type: application/json");

require_once("../config/database.php");

/* User must be logged in */

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit();

}

$user_id = $_SESSION["user_id"];

/* Read JSON */

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data["tool_id"]) ||
    !isset($data["rating"])
) {

    echo json_encode([
        "success" => false,
        "message" => "Missing required fields."
    ]);

    exit();

}

$tool_id = (int)$data["tool_id"];
$rating = (int)$data["rating"];
$review = trim($data["review"] ?? "");

/* Validate rating */

if ($rating < 1 || $rating > 5) {

    echo json_encode([
        "success" => false,
        "message" => "Rating must be between 1 and 5."
    ]);

    exit();

}

/*
One user can rate one tool only once.
If already exists → UPDATE
Else → INSERT
*/

$check = $conn->prepare("
SELECT id
FROM feedback
WHERE user_id = :user_id
AND tool_id = :tool_id
LIMIT 1
");

$check->execute([
    ":user_id" => $user_id,
    ":tool_id" => $tool_id
]);

if ($check->fetch()) {

    $update = $conn->prepare("
    UPDATE feedback
    SET
        rating = :rating,
        review = :review,
        created_at = CURRENT_TIMESTAMP
    WHERE
        user_id = :user_id
    AND
        tool_id = :tool_id
    ");

    $update->execute([
        ":rating" => $rating,
        ":review" => $review,
        ":user_id" => $user_id,
        ":tool_id" => $tool_id
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Feedback updated successfully."
    ]);

} else {

    $insert = $conn->prepare("
    INSERT INTO feedback
    (
        user_id,
        tool_id,
        rating,
        review
    )
    VALUES
    (
        :user_id,
        :tool_id,
        :rating,
        :review
    )
    ");

    $insert->execute([
        ":user_id" => $user_id,
        ":tool_id" => $tool_id,
        ":rating" => $rating,
        ":review" => $review
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Feedback submitted successfully."
    ]);

}

?>
