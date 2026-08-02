<?php

header("Content-Type: application/json");

require_once("../config/database.php");

if(!isset($_GET["tool_id"])){

    echo json_encode([
        "success"=>false,
        "message"=>"Tool ID missing"
    ]);

    exit();

}

$tool_id = (int)$_GET["tool_id"];

/* Average Rating */

$avg = $conn->prepare("
SELECT
ROUND(AVG(rating),1) AS average_rating,
COUNT(*) AS total_reviews
FROM feedback
WHERE tool_id=:tool_id
");

$avg->execute([
    ":tool_id"=>$tool_id
]);

$stats = $avg->fetch(PDO::FETCH_ASSOC);

/* Reviews */

$reviews = $conn->prepare("
SELECT

users.name,
feedback.rating,
feedback.review,
feedback.created_at

FROM feedback

INNER JOIN users
ON users.id = feedback.user_id

WHERE feedback.tool_id=:tool_id

ORDER BY feedback.created_at DESC
");

$reviews->execute([
    ":tool_id"=>$tool_id
]);

$data = $reviews->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([

    "success"=>true,

    "average_rating"=>

        $stats["average_rating"] ?
        (float)$stats["average_rating"] :
        0,

    "total_reviews"=>

        (int)$stats["total_reviews"],

    "reviews"=>$data

]);

?>
