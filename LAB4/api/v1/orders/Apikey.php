<?php
header('Content-type: application/json');
include "User.php";
include 'DBConnector.php';
session_start();
/**
 * This file will implement
 * 1. CSPRNG) API through  random_bytes ( int $length ) : string
 * 2. Blocking of Non POST requests
 *
 * $result = [];
 * $result['error'] = '';
 * $result['result'] = 1;
 * $result['crud'] = 'E';
 * $message = json_encode($result);
 * echo $message;
 */

function generateAPIKey($bytelength = 32)
{
    $bytes = random_bytes($bytelength);
    return bin2hex($bytes);
}

function userHasAPIKey($username)
{
    $con = new DBConnector;
    $found = false;
    $query = mysqli_query($con->conn, 'SELECT COUNT(*) as count FROM user as u JOIN api_keys AS b ON b.user_id = u.id WHERE u.username = "' . $username . '"') or die("Error");

    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        if ($row['count'] > 0) {
            $found = true;
        }
    }

    $con->closeDatabase();

    return $found;
}

function saveAPIKey($key)
{
    $username = $_SESSION['username'];
    $con = new DBConnector;
    $userID = NULL;

    $hasKey = userHasAPIKey($username);

    if ($hasKey) {
        $query = mysqli_query($con->conn, 'UPDATE api_keys SET api_key="' . $key . '" WHERE user_id=(SELECT id FROM user WHERE username = "' . $username . '")') or die("Error");
    } else {
        $query = mysqli_query($con->conn, 'INSERT INTO api_keys(user_id, api_key) VALUES ((SELECT id FROM user WHERE username = "' . $username . '"), "' . $key . '")') or die("Error");
    }

    return $query;

}

function generateResponse()
{
    $key = generateAPIKey();
    $res = saveAPIKey($key);

    if ($res) {
        return [
            'message' => 'success',
            'key' => $key,
        ];
    }
    return [
        'message' => $res
    ];
}

try {
    if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
        throw new Exception('Method Not Allowed', 405);
    } elseif ($_SERVER['REQUEST_METHOD'] !== "POST") {
        throw new Exception('Method Not Allowed', 405);
    } elseif ((!isset($_SESSION['username'])) && empty($_SESSION['username'])) {
        throw new Exception('Forbidden', 403);
    } else {
        http_response_code(200);
        exit(json_encode(
            generateResponse()
        ));
    }
} catch (Exception $e) {
    http_response_code($e->getCode());
    echo json_encode(
        array(
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        )
    );
    exit;
}