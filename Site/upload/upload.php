<?php

require "../assets/includes/connect.php";

if($logged_in_user == 'not logged in' or $user_banned) {
    header('Location: /');
    die;
}

if((!empty($_FILES["uploaded_file"])) and ($_FILES['uploaded_file']['error'] == 0)) {
    // something is wrong with the uploaded file (i.e. it is empty)
    header('Location: /upload/?method=local&error=empty');
    die;
}

$filename = basename($_FILES['uploadedfile']['name']);
$ext = substr($filename, strrpos($filename, '.') + 1);
$target_path = "../uploads/uploaded/" . $logged_in_userid . "-" . $filename;
$target_path = substr($target_path, 20);

$filetype = 'bad';
if($ext == 'jpg' or $ext == 'gif' or $ext == 'png' or $ext == 'jpeg' or $ext == 'svg') {
    $filetype = 'image';
}

if($ext == 'sprite2') {
    $filetype = 'sprite';
}

if($ext == 'wav' or $ext == 'mp3') {
    $filetype = 'sound';
}

if($filetype == 'bad') {
    // filetype isn't valid
    header('Location: /upload/?method=local&error=bad');
    die;
}

// find the biggest numbered file (aka the newest)
$files = [];
foreach(glob('../uploads/uploaded/' . $logged_in_userid . '-*.*') as $thefile) {
    array_push($files, substr($thefile, 20));
}
if($files == []) {
    $great_file = $logged_in_userid . '-0';
} else {
    sort($files, SORT_NUMERIC);
    $great_file = reset($files);
}

// add 1 the that file
$filename_to_upload = $logged_in_userid . '-' . strval(intval(substr($great_file, strlen($logged_in_userid) + 1)) + 1) . '.' . $ext;

// upload file!
$filename_b4_upload = $filename_to_upload;
$filename_to_upload = '../uploads/uploaded/' . $filename_to_upload;
move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $filename_to_upload);

// create json file which has the details of the uploaded file in it
$jsoncontents = array('name' => $filename_b4_upload, 'uploaded_by' => array('name' => $logged_in_user, 'id' => intval($logged_in_userid)), 'original_filename' => $filename, 'type' => $filetype, 'custom_name' => $_POST['name']);

file_put_contents($filename_to_upload . '.json', json_encode($jsoncontents));

// redirect to the "profile page" of the file
header('Location: /uploads/' . $filename_b4_upload);

?>
