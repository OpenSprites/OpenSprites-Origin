<?php
if((!empty($_FILES["uploaded_file"])) and ($_FILES['uploaded_file']['error'] == 0)) {
    // something is wrong with the uploaded file (i.e. it is empty)
    header('Location: /');
    die();
}

$filename = basename($_FILES['uploadedfile']['name']);
$ext = substr($filename, strrpos($filename, '.') + 1);
$target_path = "../uploads/uploaded/" . $logged_in_userid . "-" . $filename;
$target_path = substr($target_path, 20);

$filetype = 'bad';
if($ext == 'jpg' or $ext == 'gif' or $ext == 'png' or $ext == 'jpeg' or $ext == 'svg') {
    $filetype = 'image';
}

if($filetype == 'bad') {
    // filetype isn't valid
    header('Location: /');
    die();
}

// delete old file
array_map('unlink', glob('forums/uploads/avatars/'.$_GET['id'].'.*'));

// upload file!
$upload_to = 'forums/uploads/avatars/'.$_GET['id'].'.'.$ext;
move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $upload_to);

// redirect to the profile page of the user
header('Location: http://opensprites.gwiddle.co.uk/users/' . $_GET['id'] . '/');

?>
