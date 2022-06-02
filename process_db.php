<?php

//validation:
$error_fields = array();
if (!(isset($_POST['name']) && !empty($_POST['name']))) {
    $error_fields[] = "name";
}

if (!(isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
    $error_fields[] = "email";
}

if (!(isset($_POST['password']) && strlen($_POST['password']) > 5)) {
    $error_fields[] = "password";
}

if ($error_fields) {
    header("Location: form.php?error_fields=" . implode(",", $error_fields));
    exit;
}

//connect to DB:
$conn = mysqli_connect("localhost", "root", "password", "blog");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//escape any special characters to avoid SQL Injection:
$name = mysqli_escape_string($conn, $_POST['name']);
$email = mysqli_escape_string($conn, $_POST['email']);
$password = mysqli_escape_string($conn, $_POST['password']);

//insert the data:
$query = "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('" . $name . "', '" . $email . "', '" . $password . "')";
if (mysqli_query($conn, $query)) {
    echo "Thank you!, your information has been saved";
} else {
    echo mysqli_error($conn);
}

//close connection:
mysqli_close($conn);
