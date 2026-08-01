<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../admin/login.php");
    exit();
}

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../admin/add_tool.php");
    exit();
}

$name = trim($_POST["name"]);
$description = trim($_POST["description"]);
$website_url = trim($_POST["website_url"]);
$logo_url = trim($_POST["logo_url"]);
$pricing = trim($_POST["pricing"]);
$category_id = (int)$_POST["category_id"];
$featured = isset($_POST["featured"]) ? true : false;

/* Basic Validation */

if ($name == "" || $category_id == 0) {
    die("Tool Name and Category are required.");
}

$sql = "INSERT INTO tools
(name, description, website_url, logo_url, pricing, category_id, featured)
VALUES
(:name, :description, :website_url, :logo_url, :pricing, :category_id, :featured)";

$stmt = $conn->prepare($sql);

$stmt->bindValue(":name", $name);
$stmt->bindValue(":description", $description);
$stmt->bindValue(":website_url", $website_url);
$stmt->bindValue(":logo_url", $logo_url);
$stmt->bindValue(":pricing", $pricing);
$stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
$stmt->bindValue(":featured", $featured, PDO::PARAM_BOOL);

$stmt->execute();

header("Location: ../admin/dashboard.php?success=1");
exit();
?>
