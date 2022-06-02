<?php

//connect to database:
$conn = mysqli_connect("localhost", "root", "password", "blog");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//select the user:
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$query = "DELETE FROM `users` WHERE `users`.`id` = " . $id . " LIMIT 1";
if (mysqli_query($conn, $query)) {
    header("Location: list.php");
    exit;
} else {
    echo mysqli_error($conn);
}

//close the connection:
mysqli_close($conn);
