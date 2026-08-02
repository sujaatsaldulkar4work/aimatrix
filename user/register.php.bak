<?php
<?php
session_start();
session_start();


if(isset($_SESSION['user_id'])){
if(isset($_SESSION['user_id'])){
    header("Location: profile.php");
    header("Location: profile.php");
    exit();
    exit();
}
}
?>
?>


<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<html lang="en">


<head>
<head>


<meta charset="UTF-8">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>User Registration</title>
<title>User Registration</title>


<style>
<style>


body{
body{
    font-family:Arial,sans-serif;
    font-family:Arial,sans-serif;
    background:#f4f6f9;
    background:#f4f6f9;
}
}


.container{
.container{
    width:450px;
    width:450px;
    margin:60px auto;
    margin:60px auto;
    background:white;
    background:white;
    padding:30px;
    padding:30px;
    border-radius:10px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
    box-shadow:0 0 15px rgba(0,0,0,.1);
}
}


input{
input{


width:100%;
width:100%;
padding:12px;
padding:12px;
margin:12px 0;
margin:12px 0;
font-size:15px;
font-size:15px;
border:1px solid #ccc;
border:1px solid #ccc;
border-radius:6px;
border-radius:6px;


}
}


button{
button{


width:100%;
width:100%;
padding:12px;
padding:12px;
background:#2563eb;
background:#2563eb;
color:white;
color:white;
border:none;
border:none;
border-radius:6px;
border-radius:6px;
font-size:16px;
font-size:16px;
cursor:pointer;
cursor:pointer;


}
}


button:hover{
button:hover{


background:#1d4ed8;
background:#1d4ed8;


}
}


a{
a{


text-decoration:none;
text-decoration:none;


}
}


</style>
</style>


</head>
</head>


<body>
<body>


<div class="container">
<div class="container">


<h2>Create Account</h2>
<h2>Create Account</h2>


<form action="register_action.php" method="POST">
<form action="register_action.php" method="POST">


<input
<input
type="text"
type="text"
name="name"
name="name"
placeholder="Full Name"
placeholder="Full Name"
required>
required>


<input
<input
type="email"
type="email"
name="email"
name="email"
placeholder="Email"
placeholder="Email"
required>
required>


<input
<input
type="password"
type="password"
name="password"
name="password"
placeholder="Password"
placeholder="Password"
required>
required>


<input
<input
type="password"
type="password"
name="confirm_password"
name="confirm_password"
placeholder="Confirm Password"
placeholder="Confirm Password"
required>
required>


<button type="submit">
<button type="submit">


Register
Register


</button>
</button>


</form>
</form>


<br>
<br>


Already have an account?
Already have an account?


<a href="login.php">
<a href="login.php">


Login
Login


</a>
</a>


</div>
</div>


</body>
</body>


</html>
</html>
