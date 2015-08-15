<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbName = "forum";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$userId = $_POST['userId'];

while (true) {
    $sql = "SELECT * FROM notification WHERE for_id_user = {$userId} AND seen = 0";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_array($result)) {
        return json_encode('new');
    } else {
        sleep(5);
    }
}