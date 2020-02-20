<?php

include_once 'User.php';
include_once 'DBConnector.php';

if (isset($_POST['btn-save'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $city = $_POST['city_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    //Creating new user
    $user = new User($first_name, $last_name, $city,$username,$password);

        

    if(!$user->validateForm()){
        $user->createFormErrorSessions();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    if($user->isUserExist()){        
        $user->createUsernameErrorSessions();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    $res = $user->save();

    //Check if user is successfully created

    if ($res) {
        echo "Save operation was successful";
    } else {
        echo "An error occured!";
    }
}else if(isset($_POST['btn-view-all'])){    
        $displayTable = NULL;            
        $users = User::readAll(); 
        if ($users) {
            $displayTable = $users;
        } else {
            echo "An error occured!";
        }                      
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lab 2</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="validate.css">
</head>

<body>
<div class="container">
<div>
    <h1>Create user</h1>
    <form method="post" name="user_details" id="user_details" onsubmit="return ValidateForm()" action="<?php $_SERVER['PHP_SELF'] ?>">
        <table>
            <tr>
                <td>
                    <div id="form-errors">
                        <?php 
                           session_start();
                            if(!empty($_SESSION['form_errors'])){
                                echo "" . $_SESSION['form_errors'];
                                unset($_SESSION['form_errors']);
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input value=""  placeholder="First Name" type="text"  name="first_name" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input value="" placeholder="Last Name" type="text" name="last_name">
                </td>
            </tr>
            <tr>
                <td>
                    <input value="" placeholder="City name" type="text" name="city_name">
                </td>
            </tr>
            <tr>
                <td>
                    <input value="" placeholder="Username" type="text" name="username">
                </td>
            </tr>
            <tr>
                <td>
                    <input value="" placeholder="Password" type="password" name="password">
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" name="btn-save">SAVE</button>
                </td>
            </tr>
        </table>
    </form>
</div>
<hr>
<div>
    <h1>Login</h1>
    <a href="login.php" class="btn">Login</a>
</div>
<hr>
<div>
        <h1>Display Users</h1>
        <form method="post">
            <tr>
                <td><button type="submit" name="btn-view-all">View All Entries</button></td>
            </tr>
        </form>
        <?php
        if (isset($displayTable)) {
            echo $displayTable;
        }
        ?>
    </div>
</div>
<script src="validate.js"></script>
</body>

</html>