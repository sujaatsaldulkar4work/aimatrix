<?php
session_start();

if(isset($_SESSION["user_id"])){
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User Login</title>

<style>

body{
    font-family:Arial,sans-serif;
    background:#f4f6f9;
}

.container{
    width:450px;
    margin:60px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
}

input{
    width:100%;
    padding:12px;
    margin:12px 0;
    font-size:15px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    width:100%;
    padding:12px;
    background:#16a34a;
    color:white;
    border:none;
    border-radius:6px;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#15803d;
}

.success{
    color:green;
    margin-bottom:15px;
}

.error{
    color:red;
    margin-bottom:15px;
}

a{
    text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>User Login</h2>

<?php

if(isset($_GET["registered"])){

echo "<p class='success'>Registration Successful. Please Login.</p>";

}

if(isset($_GET["error"])){

echo "<p class='error'>Invalid Email or Password.</p>";

}

?>

<form action="login_action.php" method="POST">

<input
type="email"
name="email"
placeholder="Email Address"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button type="submit">

Login

</button>

</form>

<br>

Don't have an account?

<a href="register.php">

Register

</a>

</div>

</body>

</html>
