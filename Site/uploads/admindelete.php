<?php
    require "../assets/includes/connect.php";

    if($is_admin == false) {
        echo '403 - Permission Denied';
        die();
    }
    
    // doesn't actually delete the file- just doesn't ever show it on site
    $json = array('name' => $_GET['file'], 'deleted' => true);
    file_put_contents("uploaded/" . $_GET['file'] . '.json', json_encode($json));

    header('Location: /');
?>
