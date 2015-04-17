<?php
    require "../assets/includes/connect.php";

    if(substr($_GET['file'], 0, strlen($logged_in_userid)) !== $logged_in_userid) {
        echo '403 - Permission Denied';
        die();
    }
    
    // doesn't actually delete the file- just doesn't ever show it on site
    $json = array('name' => $_GET['file'], 'deleted' => true);
    file_put_contents("uploaded/" . $_GET['file'] . '.json', json_encode($json));

    header('Location: /');
?>
