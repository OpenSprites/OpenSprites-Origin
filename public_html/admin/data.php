<?php
    require "../assets/includes/connect.php";
        
    if(!$is_admin) {
        // user is not admin, display 404
        include '../404.php';
        die();
    }
    
    header("Access-Control-Allow-Origin: *");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: application/json");
    
    connectDatabase();
    $raw = forumQuery('SELECT * FROM ' . $forum_member_table, []);
    //$raw = imagesQuery('SELECT * FROM ' . getAssetsTableName(), []);
    
    var_dump($raw);
?>
