<?php
$con = mysqli_connect("localhost","opensprites","swagmaster123","opensprites");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to the database. Please try again later.";
    die();
}
?>