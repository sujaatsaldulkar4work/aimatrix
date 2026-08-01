<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>User Profile</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:600px;
    margin:60px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}

.btn{
    display:inline-block;
    margin-top:20px;
    background:#dc2626;
    color:white;
    text-decoration:none;
    padding:10px 20px;
    border-radius:6px;
}

</style>

</head>

<body>

<div class="container">

<h2>Welcome <?php echo htmlspecialchars($_SESSION["user_name"]); ?> 👋</h2>

<p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION["user_name"]); ?></p>

<p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["user_email"]); ?></p>

<a href="logout.php" class="btn">Logout</a>

</div>

</body>

</html>
