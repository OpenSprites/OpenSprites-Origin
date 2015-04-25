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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <div id='container'>
        <h1>Admin</h1>
        <h3>Bulk Delete</h3>
        View all the uploads of a user and click to delete them.<br>
        <input type='number' inputmode='numeric' id='bulkdelete' value='0' min='0' placeholder='userid'><br>
        <a id='bulkdelete'>Go</a>
    </div>
    
    <script>
        $('input').change(function() {
            $('a#' + $(this).attr('id')).attr('href', $(this).val());
        });
    </script>
</body>
