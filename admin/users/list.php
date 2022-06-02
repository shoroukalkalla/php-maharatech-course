<?php
//start session:
session_start();
if (isset($_SESSION['id'])) {
    echo '<p> Welcome ' . $_SESSION['email'] . ' <a href="/Maharatech/logout.php">Logout</a></p>'; // No.
} else {
    header("Location /login.php");
    exit;
}

//open connection:
$conn = mysqli_connect("localhost", "root", "password", "blog");
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//select all users:
$query = "SELECT * FROM `users`";

//search by the name or the email:
if (isset($_GET['search'])) {
    $search = mysqli_escape_string($conn, $_GET['search']);
    $query .= " WHERE `users`.`name` LIKE '%" . $search . "%' OR `users`.`email` LIKE '%" . $search . "%'";
}

$result = mysqli_query($conn, $query);
?>

<!-- =================================================== -->

<html>

<head>
    <title>Admin :: List Users</title>
</head>

<body>
    <h1>List users</h1>
    <form method="get">
        <input type="text" name="search" placeholder="Enter {Name} or {Email} to search" />
        <input type="submit" value="search" />
    </form>
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php
            //Loop on the rowset:
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= ($row['admin']) ? 'Yes' : 'No' ?></td>
                    <td><a href="edit.php?id=<?= $row['id'] ?>">Edit</a> | <a href="delete.php?id=<?= $row['id'] ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="2" style="text-align:center"><?= mysqli_num_rows($result) ?> users</td>

                <td colspan="2" style="text-align:center"><a href="add.php">Add Users</a></td>
            </tr>
        </tfoot>
    </table>
</body>


<?php
//close connection:
mysqli_free_result($result);
mysqli_close($conn);
?>

