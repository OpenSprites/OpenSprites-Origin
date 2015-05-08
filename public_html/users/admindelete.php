<?php
require "../assets/includes/connect.php";
$con = mysqli_connect("localhost","user","pass","db");

// Check connection
if (mysqli_connect_errno())
{
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

$username = mysqli_real_escape_string($con, $_GET["username"]);

if($is_admin == false) {
    echo '403 - Permission Denied';
    die();
}

$query = mysqli_query($con, "SELECT * FROM et_member WHERE username='$username'");
$numrows = mysqli_num_rows($query);

if($numrows == 0){
    die("Failed: No such user!");
} else {
    mysqli_query($con, "DELETE FROM et_member WHERE username='$username'");
    header('Location: /');
}

?>