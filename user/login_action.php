<?php
session_start();

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

/* Find User */

$sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bindValue(":email", $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php?error=1");
    exit();
}

/* Verify Password */

if (!password_verify($password, $user["password"])) {
    header("Location: login.php?error=1");
    exit();
}

/* Create Session */

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];
$_SESSION["user_email"] = $user["email"];

/* Redirect */

header("Location: /aimatrix/index.php");
exit;
exit();

?>
