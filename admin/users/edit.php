<?php
$errors_fields = array();

//connect to database:
$conn = mysqli_connect("localhost", "root", "password", "blog");
if (!$conn) {
    mysqli_connect_error();
    exit;
}

//select the user:
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$select = "SELECT * FROM `users` WHERE `users`.`id`=" . $id . " LIMIT 1";
$result = mysqli_query($conn, $select);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //validation:
    if (!(isset($_POST['name']) && !empty($_POST['name']))) {
        $errors_fields[] = "name";
    }

    if (!(isset($_POST['email']) && filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
        $errors_fields[] = "email";
    }

    if (!$errors_fields) {
        //escape any special characters to avoid SQL Injection:
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $name = mysqli_escape_string($conn, $_POST['name']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = (!empty($_POST['password'])) ? sha1($_POST['password']) : $row['password'];
        $admin = (isset($_POST['admin'])) ? 1 : 0;

        //update the data
        $query = "UPDATE `users` SET `name` = '" . $name . "', `email` = '" . $email . "', `password` = '" . $password . "', `admin` = " . $admin . " WHERE `users`.`id` = " . $id;
        if (mysqli_query($conn, $query)) {
            header("Location: list.php");
            exit;
        } else {
            echo mysqli_error($conn);
        }
    }
}

//close the connection:
mysqli_free_result($result);
mysqli_close($conn);

?>


<html>

<head>
    <title>Admin :: Edit User</title>
</head>

<body>
    <form method="post">
        <label for="name">Name</label>
        <input type="hidden" name="id" id="id" value="<?= (isset($row['id'])) ? $row['id'] : '' ?>" />

        <input type="text" name="name" id="name" value="<?= (isset($row['name'])) ? $row['name'] : '' ?>" />
        <?php
        if (in_array("name", $errors_fields))
            echo "* Please enter your name";
        ?>
        <br />

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= (isset($row['email'])) ? $row['email'] : '' ?>" />
        <?php
        if (in_array("email", $errors_fields))
            echo "* Please enter a valid email";
        ?>
        <br />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
        <?php
        if (in_array("password", $errors_fields))
            echo "* Please enter a password not less than 6 characters";
        ?>
        <br />

        <input type="checkbox" name="admin" <?= ($row['admin']) ? 'checked' : '' ?> />Admin
        <br />

        <input type="submit" name="submit" value="Edit User" />
    </form>
</body>

</html>