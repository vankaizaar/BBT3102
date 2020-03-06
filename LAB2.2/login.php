<?php
include_once 'DBConnector.php';
include_once 'User.php';

//Create new db connection
$con = new DBConnector;

if (isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    //Statically create a new user
    $instance = User::create();
    //Set username & password to static instance
    $instance->setPassword($password);
    $instance->setUsername($username);
    //Validate Data
    if ($instance->isPasswordCorrect()) {
        $instance->login();
        $con->closeDatabase();
        $instance->createUserSession();
    } else {
        $con->closeDatabase();
        header("Location:login.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <table>
            <tr>
                <td>
                    <input placeholder="Username" type="text" name="username" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input placeholder="Password" type="password" name="password" required>
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" name="btn-login">LOGIN</button>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>