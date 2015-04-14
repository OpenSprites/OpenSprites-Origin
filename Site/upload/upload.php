if (!is_dir('/uploads/' . $logged_in_userid)) {
    // create userid folder in /uploads/ if it doesn't exist
    mkdir('/uploads/' . $logged_in_userid, 0777, true);
}

if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
    // something is wrong with the uploaded file (i.e. it is empty)
    header('Location: /uploads/?method=local&error=true');
}

$filename = basename($_FILES['uploaded_file']['name']);
$ext = substr($filename, strrpos($filename, '.') + 1);
$target_path = "/uploads/" . $logged_in_userid . "/" . $filename;

$filetype = 'bad';
if($ext == 'jpg' || $ext == 'gif' || $ext == 'png' || $ext == 'jpeg') {
    $filetype = 'image';
}

if($ext == 'sprite2') {
    $filetype = 'sprite';
}

if($ext == 'wav' || $ext == 'mp3') {
    $filetype = 'sound';
}

if($filetype == 'bad') {
    // filetype isn't valid
    header('Location: /uploads/?method=local&error=true');
}

echo $ext . '<br>';
echo $filetype;
