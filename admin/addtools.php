<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

require_once("../config/database.php");

$query = "SELECT id, name FROM categories ORDER BY name";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add AI Tool</title>

<style>

body{
    font-family:Arial,sans-serif;
    background:#f4f6f9;
}

.container{
    width:700px;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
}

h2{
    margin-bottom:25px;
}

label{
    font-weight:bold;
}

input,
textarea,
select{

    width:100%;
    padding:12px;
    margin-top:8px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:15px;

}

button{

    background:#16a34a;
    color:white;
    border:none;
    padding:14px 22px;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;

}

button:hover{

    background:#15803d;

}

.back{

    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;

}

</style>

</head>

<body>

<div class="container">

<a class="back" href="dashboard.php">⬅ Back to Dashboard</a>

<h2>Add New AI Tool</h2>

<form action="../api/add_tool.php" method="POST">

<label>Tool Name</label>

<input
type="text"
name="name"
required>

<label>Description</label>

<textarea
name="description"
rows="5"
required></textarea>

<label>Website URL</label>

<input
type="url"
name="website_url">

<label>Logo URL</label>

<input
type="text"
name="logo_url">

<label>Pricing</label>

<select name="pricing">

<option value="Free">Free</option>
<option value="Freemium">Freemium</option>
<option value="Paid">Paid</option>

</select>

<label>Category</label>

<select name="category_id">

<?php
foreach($categories as $category){
?>

<option value="<?php echo $category['id']; ?>">

<?php echo htmlspecialchars($category['name']); ?>

</option>

<?php
}
?>

</select>

<label>

<input
type="checkbox"
name="featured"
value="1">

Featured Tool

</label>

<br><br>

<button type="submit">

Save Tool

</button>

</form>

</div>

</body>

</html>
