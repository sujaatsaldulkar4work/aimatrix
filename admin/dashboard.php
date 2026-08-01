<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Matrix Admin Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f6f9;
}

.header{

    background:#0f766e;
    color:white;
    padding:20px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;

}

.header h2{
    font-size:28px;
}

.container{

    width:90%;
    margin:40px auto;

}

.card{

    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,.1);

}

.card h3{

    margin-bottom:15px;

}

.card p{

    margin-bottom:25px;
    font-size:18px;

}

.buttons{

    display:flex;
    gap:20px;
    flex-wrap:wrap;

}

.btn{

    padding:14px 24px;
    text-decoration:none;
    color:white;
    border-radius:8px;
    font-size:17px;

}

.add{
    background:#16a34a;
}

.edit{
    background:#2563eb;
}

.delete{
    background:#dc2626;
}

.logout{

    background:#374151;

}

</style>

</head>

<body>

<div class="header">

<h2>AI Matrix Admin Panel</h2>

<div>

Welcome,
<b><?php echo htmlspecialchars($_SESSION["admin_name"]); ?></b>

</div>

</div>

<div class="container">

<div class="card">

<h3>Dashboard</h3>

<p>

You are successfully logged in as Administrator.

</p>

<div class="buttons">

<a href="addtools.php" class="btn add">
    ➕ Add Tool
</a>

<a href="#" class="btn edit">
✏ Edit Tool
</a>

<a href="#" class="btn delete">
🗑 Delete Tool
</a>

<a href="logout.php" class="btn logout">
Logout
</a>

</div>

</div>

</div>

</body>

</html>
