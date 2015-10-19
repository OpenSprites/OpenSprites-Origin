<?php
require "../assets/includes/connect.php";
require "../assets/includes/validate.php";

error_reporting(0);

if (is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    try {
        $id = forumQuery("SELECT * FROM `$forum_member_table` WHERE `username`=?", array($_GET['id']))[0]['memberId'];
    } catch (Exception $e) {
        include '../404.php';
        die();
    }
}

$user = getUserInfo(intval($id));
if (!isset($user['userid'])) {
    include '../404.php';
    die();
} else {
    $user_exist = true;
    $username = $user['username'];
}

//          Let's not have people viewing potentially dangerous suspended profiles
// we don't want to have a system to delete users because we should accept appeals
// no matter how big the offence was. Suspending a user is basically deleting them
if ($user['usertype'] == "suspended" && !$is_admin) {
    include '../404.php';
    die();
}

// check if avatar exists
// TODO: make this less hacky, maybe add a default avatar on account creation
$user['avatar'] = "http://opensprites.org/forums/uploads/avatars/" . $user['userid'] . ".png?_=" . time();
$handle = curl_init($user['avatar']);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($handle);
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
if ($httpCode == 404) {
    $user['avatar'] = "//opensprites.org/assets/images/defaultuser.png";
}
curl_close($handle);
// --------------------

function unescape($inp)
{
    if (is_array($inp))
        return array_map(__METHOD__, $inp);

    if (!empty($inp) && is_string($inp)) {
        return str_replace(array('\\\\', '\\0', '/n', '\\r', "\\'", '\\"', '\\Z', '$hashtag$'), array('\\', "\0", "&#13;", "\r", "'", '"', "\x1a", '#'), $inp);
    }

    return $inp;
}

$profileSettings = getProfileSettings($user['userid']);
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>

    <link href='user-style.css' rel='stylesheet' type='text/css'>
    <link href='/assets/js/spectrum/spectrum.css' rel='stylesheet' type='text/css'>
    <script src='/assets/js/spectrum/spectrum.js'></script>
    <script src='/assets/lib/multiple-select/jquery.multiple.select.js'></script>
    <link rel='stylesheet' type='text/css' href='/assets/lib/multiple-select/multiple-select.css'/>
    <style>textarea {
            resize: none;
            width: 99%;
            height: 250px;
        }

        #location {
            width: 99%;
        }

        .buttons-container {
            bottom: 15px;
        }</style>
</head>
<body>
<?php include "../navbar.php"; ?>

</body>
</html>
