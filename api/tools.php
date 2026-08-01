<?php

header('Content-Type: application/json');

require '../config/database.php';

try {
    $query = "
        SELECT
            tools.id,
            tools.name,
            tools.description,
            tools.website_url,
            tools.logo_url,
            tools.pricing,
            tools.featured,
            categories.name AS category
        FROM tools
        JOIN categories
            ON tools.category_id = categories.id
        ORDER BY tools.id
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $tools = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'tools' => $tools
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to load AI tools'
    ]);
}
