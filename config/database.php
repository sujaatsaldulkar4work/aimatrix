<?php

$host = "tier-dbinstance.czuoqwycqx64.ap-south-1.rds.amazonaws.com";
$port = "5432";
$dbname = "aimatrixdb";
$user = "postgres";
$password = "To_GetAJob26";

try {

    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die("Database Connection Failed: " . $e->getMessage());

}

?>
