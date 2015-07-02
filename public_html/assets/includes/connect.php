<?php
    if(!isset($dont_load_htmldom)) {
        require_once 'html_dom_parser.php';
    }
	require_once 'database.php';
	
	function et_regenerateToken(){
		session_regenerate_id(true);
		$_SESSION["token"] = substr(md5(uniqid(rand())), 0, 13);
		$_SESSION["userAgent"] = md5($_SERVER["HTTP_USER_AGENT"]);
	}
	
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
					setcookie($name, "", time() - 3600, "/", ".opensprites.org");
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
    session_set_cookie_params(0, '/', '.opensprites.org');
    session_start();
	
	connectForumDatabase();
	connectDatabase();
    
    $is_admin = false;
	$logged_in_userid = 0;
    $user = 'not logged in';
    $logged_in_user = 'not logged in';
    $user_group = 'N/A';
    $user_banned = false;
    if(isset($_SESSION["userId"])) {
		$userInfo = getUserInfo(intval($_SESSION["userId"]));
		$logged_in_userid = $userInfo['userid'];
		$logged_in_user = $userInfo['username'];
		$user = $logged_in_user;
		$user_group = ucwords($userInfo['usertype']);
		$user_banned = ($user_group == 'suspended');
		$is_admin = $user_group === 'administrator' || in_array("Moderator", $userInfo['groups']);
		if(!$is_admin){
			error_reporting(0);
		}
        if($user_group === "suspended"){
            header( 'Location: http://opensprites.org/suspended.php' ) ;
        }
    }
