<?php
    require 'html_dom_parser.php';
	require_once 'database.php';

    session_name("OpenSprites_Forum_session");
    session_set_cookie_params(0, '/', '.' . $_SERVER['HTTP_HOST']);
    session_start();
    
    $is_admin = false;
    if(isset($_SESSION["userId"])) {
        /*$user = json_decode(file_get_contents('http://dev.opensprites.gwiddle.co.uk/site-api/user.php'));
        
        $logged_in_userid = $_SESSION["userId"];
        $html = file_get_html('http://opensprites.gwiddle.co.uk/forums/?p=member/' . $logged_in_userid);
        $logged_in_user = $html->find('h1#memberName', 0)->innertext;
        $user_group = $html->find('p#memberGroup', 0)->plaintext;
        $user_banned = $user_group == 'Suspended';

        if($user_group == "Moderator" or $user_group == "Administrator") {
            $is_admin = true;
        }

        if($user_group == "Suspended"){
            header( 'Location: http://dev.opensprites.gwiddle.co.uk/suspended.php' ) ;
        }*/
		
		error_reporting(0);
		connectForumDatabase();
		$userInfo = getUserInfo(intval($_SESSION["userId"]));
		$logged_in_userid = $userInfo['userid'];
		$logged_in_user = $userInfo['username'];
		$user_group = $userInfo['usertype'];
		$user_banned = $user_group == 'suspended';
		$user_admin = $user_group == 'administrator' || in_array($userInfo['groups'], "Moderator");
    } else {
        $logged_in_userid = 0;
        $user = 'not logged in';
        $logged_in_user = 'not logged in';
        $user_group = 'N/A';
        $user_banned = false;
    }
