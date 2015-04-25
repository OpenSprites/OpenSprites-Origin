<?php
require "../assets/includes/connect.php";

if(!$is_admin) {
    // user is not admin, display 404
    include '../404.php';
    die();
}
?>

<head>
    <title>
        OpenSprites Admin
    </title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
    <link href="admin.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='container'>
        <h1>Admin</h1>
        <h3>Bulk Delete</h3>
    </div>
</body>
