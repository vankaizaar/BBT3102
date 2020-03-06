<?php
date_default_timezone_set("UTC");
include_once 'User.php';
include_once 'FileUploader.php';
include_once 'DBConnector.php';
if (isset($_POST['btn-save'])) {
    #user details from form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $city = $_POST['city_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $time = time();
    $utc_timestamp = empty($_POST['utc_timestamp']) ? $time : $_POST['utc_timestamp'];;
    $offset = empty($_POST['time_zone_offset']) ? User::getTimezoneDifference() : User::getTimezoneDifference($_POST['time_zone_offset']);

    #file details from form
    $fileName = $_FILES['fileToUpload']['name'];
    $fileSize = $_FILES['fileToUpload']['size'];
    $fileTmpName = $_FILES['fileToUpload']['tmp_name'];
    $fileType = $_FILES['fileToUpload']['type'];
    $fileErrors = $_FILES['fileToUpload']['error'];
    $tmp = explode('.', $fileName);
    $fileExtension = strtolower(end($tmp));
    //Creating new user
    $user = new User($first_name, $last_name, $city, $username, $password, $utc_timestamp, $offset);
    //Creating new file upload instance
    $uploader = new FileUploader($fileName, $fileTmpName, $fileSize, $fileType, $fileExtension, $fileErrors);
    //validating user input
    if (!$user->validateForm()) {
        $user->createFormErrorSessions();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    //check if username already exists
    if ($user->isUserExist()) {
        $user->createUsernameErrorSessions();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    // Verifying file was selected
    if (!$uploader->fileWasSelected($uploader)) {
        $errorMessage = "No file was uploaded, Please select a file.";
        $uploader->createUploadFormErrorSessions($errorMessage);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    //check if file already exists
    if ($uploader->fileAlreadyExists($uploader)) {
        $errorMessage = "A file already exists by the chosen name. Select a different file or rename your file.";
        $uploader->createUploadFormErrorSessions($errorMessage);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    //check if file size is correct
    if (!$uploader->fileSizeIsCorrect($uploader)) {
        $errorMessage = "The maximum file size should be " . FileUploader::getFileSizeLimit() . " kB.";
        $uploader->createUploadFormErrorSessions($errorMessage);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    //check if file type is correct
    if (!$uploader->fileTypeIsCorrect($uploader)) {
        $errorMessage = "Only images are allowed as uploads. ";
        $uploader->createUploadFormErrorSessions($errorMessage);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }
    //Using Transactions to save data.
    //These two statement return arrays of values for the user and the file to be saved
    $res = $user->save();
    $file_upload_response = $uploader->uploadFile($uploader);

    try {
        $con = new DBConnector;
        $con->conn->autocommit(FALSE);

        $stmtUser = $con->conn->prepare("INSERT INTO user (`first_name`,`last_name`,`user_city`,`username`,`password`,`utc_timestamp`,`tzoffset`) VALUES (?,?,?,?,?,?,?)");
        $stmtUser->bind_param("ssssssi", $res["first_name"], $res["last_name"], $res["user_city"], $res["username"], $res["password"], $res["utc_timestamp"], $res["tzoffset"]);
        $stmtUser->execute();

        $user_id = $con->conn->insert_id;

        $stmtFile = $con->conn->prepare("INSERT INTO uploads (`file_name`,`user_id`,`file_size`) VALUES (?,?,?)");
        $stmtFile->bind_param("sii", $file_upload_response["file_name"], $user_id, $file_upload_response["file_size"]);
        $stmtFile->execute();

        $stmtUser->close();
        $stmtFile->close();

        $con->conn->autocommit(TRUE);

        echo "Save operation was successful";

    } catch (Exception $e) {
        $con->conn->rollback(); //remove all queries from queue if error (undo)
        throw $e;
    }

} else if (isset($_POST['btn-view-all'])) {
    $displayTable = NULL;
    $users = User::readAll();
    if ($users) {
        $displayTable = $users;
    } else {
        echo "An error occurred!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lab 3</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="validate.css">
</head>

<body>
<div class="container">
    <div>
        <h1>Create user</h1>
        <form method="post" name="user_details" id="user_details" onsubmit="return window.ValidateForm()"
              action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>
                        <div id="form-errors">
                            <?php
                            session_start();
                            if (!empty($_SESSION['form_errors'])) {
                                echo "" . $_SESSION['form_errors'];
                                unset($_SESSION['form_errors']);
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input value="" placeholder="First Name" type="text" name="first_name" required>
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
                        <input type="hidden" name="MAX_FILE_SIZE" value="50000">
                        <input placeholder="select file" type="file" name="fileToUpload">
                    </td>
                </tr>
                <input type="hidden" name="utc_timestamp" id="utc_timestamp">
                <input type="hidden" name="time_zone_offset" id="time_zone_offset">
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
                <td>
                    <button type="submit" name="btn-view-all">View All Entries</button>
                </td>
            </tr>
        </form>
        <?php
        if (isset($displayTable)) {
            echo $displayTable;
        }
        ?>
    </div>
</div>
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/validate.js"></script>
<script src="js/timezone.js"></script>
</body>

</html>