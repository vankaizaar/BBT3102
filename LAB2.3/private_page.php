<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Private Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>This is a private page</h1>
    <p>We want to protect it.</p>
    <br>
    <br>
    <br>
    <a href="logout.php" class="btn">Logout</a>
</div>
</body>
</html>