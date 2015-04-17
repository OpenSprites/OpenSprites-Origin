<?php
require_once("../assets/includes/connect.php");
require_once("../assets/includes/database.php");

function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

header("Content-Type: text/json");
$json = array("status"=>"error","message"=>"Unknown","debug"=>"","results"=>array());

try {
	connectDatabase();
} catch(Exception $e){
	$json['debug'] = print_r($e, TRUE);
	$json['message'] = "Whoops! There was a server-side database error.";
	die(json_encode($json));
}

/*$hacks = FALSE;
if(isset($_POST['verify1'])){
	if($_POST['verify1'] != ""){
		$hacks = TRUE;
	}
} else {
	$hacks = TRUE;
}
if(isset($_POST['verify2'])){
	if($_POST['verify2'] != "swag"){
		$hacks = TRUE;
	}
} else {
	$hacks = TRUE;
}
if(isset($_POST['token']) && isset($_SESSION['token'])){
	if($_POST['token'] != $_SESSION['token']){
		$hacks = TRUE;
	}
} else {
	$hacks = TRUE;
}
if($hacks){
	$json['message'] = "Sorry! An unknown error happened.";
	die(json_encode($json));
}*/

// add more spam protection here

if(isset($_FILES['uploadedfile'])){
	$basedir = "../uploads/uploaded/";
	
	// we don't want unwanted image test uploads in the repo, so make the images dir if it isn't already there
	if (!file_exists($basedir)) {
		mkdir($basedir, 0777, true);
	}
	
	$error = FALSE;
	
	foreach($_FILES['uploadedfile']['tmp_name'] as $i => $tmpName){
		$current_json = array("status"=>"error","message"=>"Unknown","image_url"=>"N/A","hash"=>"");
		if($_FILES['uploadedfile']['error'][$i] != 0){
			$current_json['message'] = "Sorry! Our servers encountered an error with your upload request. Maybe you didn't send us a file?";
		} else {
			$ext = ".wut";
			$type = exif_imagetype($tmpName);
			if($type==FALSE || $type==0) $type = "Unknown (yet)";
			$json['debug'] .= "Image type:$type\n";
			$proceed = FALSE;
			if($type == 1 || $type == 2 || $type == 3){
				$proceed = TRUE;
				if($type==1) $ext=".gif";
				if($type==2) $ext=".jpg";
				if($type==3) $ext=".png";
				// add more later
			} else {
				try {
					$doc = @simplexml_load_file($tmpName);
					if(is_object($doc) && $doc->getName() == "svg"){
						$json['debug'] .= "Image type: SVG?\n";
						$proceed = TRUE;
						$ext=".svg";
					} else throw new Exception("Not an SVG");
				} catch(Exception $e){
					$json['debug'] .= $e."\n";
					$current_json['message'] = "Whoops! Our servers didn't recognize this image's format."; 
					$current_json['hash'] = hash_file('md5', $tmpName);
				}
			}
			if($proceed){
				$hash = hash_file('md5', $tmpName);
				$existing = imageExists($hash);
				if(sizeof($existing) > 0){
					$current_json['status'] = "success";
					$current_json['message'] = "Your image has been uploaded before, so here's the original URL.";
					$current_json['image_url'] = $existing[0]['name'];
					$current_json['hash'] = $hash;
				} else {
					$name = "";
					do {
						$name = unique_id(8);
					} while(file_exists($basedir.$name.$ext));
					$json['debug'] .= $name."\n";
					if (move_uploaded_file($tmpName, $basedir.$name.$ext)) {
						try {
							addImageRow($name.$ext, $hash, $logged_in_user, $logged_in_userid);
							$current_json['status'] = "success";
							$current_json['message'] = "Your image was uploaded successfully";
							$current_json['image_url'] = $name.$ext;
							$current_json['hash'] = $hash;
						} catch(Exception $e){
							$json['debug'] .= "\n".$e;
							$current_json['message'] = "Whoops! There was a server-side database error.";
							$current_json['hash'] = $hash;
						}
					} else {
						$current_json['message'] = "Sorry! A server side error prevented us from uploading your file. Try again later.";
						$current_json['hash'] = $hash;
					}
				}
			}
		}
		$json['results'][$i] = $current_json;
	}
	if(!$error){
		$json['status'] = "success";
		$json['message'] = "All images uploaded successfully.";
	} else {
		$json['status'] = "partial";
		$json['message'] = "Some images not uploaded.";
	}
} else $json['message'] = "Whoops! It seems your browser sent an incomplete request. Are you sure you're not hacking?";
$json['var_dump'] = print_r($_FILES, true);
echo json_encode($json);
?>