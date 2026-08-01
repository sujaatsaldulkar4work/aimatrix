<?php
session_start();

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit();
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

$query = "SELECT * FROM admins WHERE email = :email LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bindParam(":email", $email);
$stmt->execute();

$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin["password"])) {

    $_SESSION["admin_id"] = $admin["id"];
    $_SESSION["admin_name"] = $admin["name"];
    $_SESSION["admin_email"] = $admin["email"];

    header("Location: dashboard.php");
    exit();

} else {

    header("Location: login.php?error=1");
    exit();

}
?>
