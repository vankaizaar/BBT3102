<?php
include_once 'User.php';
include_once 'DBConnector.php';

if (isset($_POST['btn-save']) && !empty($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['city_name'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $city = $_POST['city_name'];

    //Creating new user
    $user = new User($first_name, $last_name, $city);

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
    <title>Lab 1</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
<div>
<h1>Create user</h1>
<form method="post">
        <table>
            <tr>
                <td>
                    <input value=""  placeholder="First Name" type="text"  name="first_name">
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
                    <button type="submit" name="btn-save">SAVE</button>
                </td>
            </tr>
        </table>
    </form>
</div>
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
</body>

</html>