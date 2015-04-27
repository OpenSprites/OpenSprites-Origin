<?php
    require 'html_dom_parser.php';
	require_once 'database.php';
	
	/*function attemptLogoutBugFix(){
		$cookies = explode("; ", getallheaders()['Cookie']);
		$duplicate_search = "OpenSprites_Forum_session";
		$found = FALSE;
		foreach($cookies as $key=>$value){
			$cookie = explode("=", $value);
			$name = $cookie[0];
			$value = $cookie[1];
			if($name == $duplicate_search){
				if($found){
					setcookie($name, "", time() - 3600);
					setcookie($name, "", time() - 3600, "/");
					setcookie($name, "", time() - 3600, "/", ".opensprites.gwiddle.co.uk");
					return TRUE;
				} else {
					$found = TRUE;
				}
			}
		}
		return FALSE;
	}
	
	if(attemptLogoutBugFix()){
		header("Location: " . $_SERVER['REQUEST_URI']);
		die();
	}*/

    session_name("OpenSprites_Forum_session");
    session_set_cookie_params(0, '/', '.opensprites.gwiddle.co.uk');
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
		
		connectForumDatabase();
		$userInfo = getUserInfo(intval($_SESSION["userId"]));
		$logged_in_userid = $userInfo['userid'];
		$logged_in_user = $userInfo['username'];
		$user_group = ucwords($userInfo['usertype']);
		$user_banned = $user_group == 'Suspended';
		$is_admin = $user_group == 'administrator' || in_array("Moderator", $userInfo['groups']);
		if(!$is_admin){
			error_reporting(0);
		}
    } else {
        $logged_in_userid = 0;
        $user = 'not logged in';
        $logged_in_user = 'not logged in';
        $user_group = 'N/A';
        $user_banned = false;
    }
