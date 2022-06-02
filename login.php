<?php
//we will use session for storing the signed in user data:
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //connect to DB:
    $conn = mysqli_connect("localhost", "root", "password", "blog");
    if (!$conn) {
        echo mysqli_connect_error();
        exit;
    }


    //escape any special character to avoid SQL Injection:
    $email = mysqli_escape_string($conn, $_POST['email']);
    $password = sha1($_POST['password']);

    //password_hash($password, PASSWORD_DEFAULT);
    //then check with password_verify()
    //The password column in db should be changed to char(60) if you decided to use password_verify

    //select from db:
    $query = "SELECT * FROM `users` WHERE `users`.`email` = '" . $email . "' and `users`.`password` = '" . $password . "' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        header("Location: admin/users/list.php");
        exit;
    } else {
        $error = 'Invalid email or password';
    }

    //close connection:
    mysqli_free_result($result);
    mysqli_close($conn);
}
?>


<!-- ======================================================= -->

<html>

<head>
    <title>Login</title>
</head>

<body>
    <form method="post">

        <?php if (isset($error)) echo $error ?>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= (isset($_POST['email'])) ? $_POST['email'] : '' ?>" />
        <br />

        <label for="password">Password</label>
        <input type="password" id="password" name="password" />
        <br />

        <input type="submit" name="submit" value="Login" />
    </form>
</body>

</html>

