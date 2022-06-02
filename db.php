<?php

//open the connection
$conn = mysqli_connect("localhost", "root", "password", "blog");
//check if the db doesn't exist:
if (!$conn) {
    echo mysqli_connect_error();
    exit;
}

//do the operation("select, insert, ....")
$query = "SELECT * FROM `users`";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    echo "Id: " . $row['id'] . "<br />";
    echo "name: " . $row['name'] . "<br />";
    echo "email: " . $row['email'] . "<br />";
    echo str_repeat('-', 50) . "<br/>";
}

//close the connection
mysqli_free_result($result);
mysqli_close($conn);
