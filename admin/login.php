<?php
session_start();

if(isset($_SESSION['admin_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>AI Matrix Admin Login</title>

<link rel="stylesheet" href="../css/style.css">

<style>

body{

background:#f4f6f9;
font-family:Arial;
display:flex;
justify-content:center;
align-items:center;
height:100vh;

}

.login-box{

background:white;
padding:40px;
width:420px;
border-radius:12px;
box-shadow:0 0 20px rgba(0,0,0,.15);

}

.login-box h2{

text-align:center;
margin-bottom:30px;

}

input{

width:100%;
padding:12px;
margin:10px 0;
border:1px solid #ccc;
border-radius:8px;

}

button{

width:100%;
padding:12px;
background:#2563eb;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
font-size:16px;

}

button:hover{

background:#1d4ed8;

}

.error{

color:red;
margin-bottom:15px;

}

</style>

</head>

<body>

<div class="login-box">

<h2>AI Matrix Admin</h2>

<?php

if(isset($_GET['error'])){

echo "<p class='error'>Invalid Email or Password</p>";

}

?>

<form action="auth.php" method="POST">

<input
type="email"
name="email"
placeholder="Enter Email"
required>

<input
type="password"
name="password"
placeholder="Enter Password"
required>

<button type="submit">

Login

</button>

</form>

</div>

</body>

</html>
