<?php
include '../assets/includes/connect.php';
header("Content-Type: application/json");

function escape($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
}

if(!isset($_GET['userid'])) {
    $id = $logged_in_userid;
} else {
    $id = $_GET['userid'];
}

// only allow numbers
$chars = "0123456789";
$pattern = "/[^".preg_quote($chars, "/")."]/";
$id = preg_replace($pattern, "", $id);

if($id == 0 || $id == '') {
	die('false');
}

if(isset($_GET['bgcolor'])) {
    error_reporting(1);
    
    setProfileSettings($logged_in_userid, array("bgcolor" => escape($_GET['bgcolor'])));
    
    $stmt = $forum_dbh->prepare('UPDATE `et_profile_data` SET `data`=? WHERE `memberId`=? AND `fieldId`=1');
	$stmt->execute(array(escape($_GET['aboutme']), $id));
    
    $stmt = $forum_dbh->prepare('UPDATE `et_profile_data` SET `data`=? WHERE `memberId`=? AND `fieldId`=2');
	$stmt->execute(array(escape($_GET['location']), $id));
                       
    header('Location: '.$_SERVER['HTTP_REFERER']);
}

echo json_encode(getProfileSettings($id), JSON_PRETTY_PRINT);
?>
