<?php
session_start();

require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php");
    exit();
}

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$password = $_POST["password"];
$confirm_password = $_POST["confirm_password"];

/* Basic Validation */

if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
    die("All fields are required.");
}

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

/* Check if email already exists */

$check = $conn->prepare("SELECT id FROM users WHERE email = :email");
$check->bindValue(":email", $email);
$check->execute();

if ($check->fetch()) {
    die("Email already registered.");
}

/* Hash Password */

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* Insert User */

$sql = "INSERT INTO users (name, email, password)
        VALUES (:name, :email, :password)";

$stmt = $conn->prepare($sql);

$stmt->bindValue(":name", $name);
$stmt->bindValue(":email", $email);
$stmt->bindValue(":password", $hashedPassword);

if ($stmt->execute()) {

    header("Location: login.php?registered=1");
    exit();

} else {

    die("Registration failed.");

}
?>
