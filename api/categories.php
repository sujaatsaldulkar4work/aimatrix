<?php

header("Content-Type: application/json");

require_once "../config/database.php";

try {

    $query = "SELECT id, name FROM categories ORDER BY name";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "categories" => $categories
    ]);

} catch(PDOException $e){

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}
?>
