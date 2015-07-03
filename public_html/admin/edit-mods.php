<?php
require "../assets/includes/connect.php";

if(!$is_admin) {
    // user is not admin, display 404
    include '../404.php';
    die();
}
$message ="";

if(isset($_POST['action'])){
	if($_POST['action'] == "add"){
		$userid = intval($_POST['mod_id']);
		$groups = getUserGroups($userid);
		if(!in_array(1, $groups)) array_push($groups, 1);
		setAccountTypeById($userid, "administrator");
		setUserGroups($userid, $groups);
		$message = "Moderator added";
	} else if($_POST['action'] == "remove"){
		$userid = intval($_POST['mod_id']);
		$groups = getUserGroups($userid);
		if(in_array(1, $groups)){
			$key = array_search(1, $groups);
			unset($groups[$key]);
		}
		setAccountTypeById($userid, "member");
		setUserGroups($userid, $groups);
		$message = "Moderator removed";
	} else {
		$message = "Unknown action";
	}
}
?>

<head>
    <title>
        OpenSprites Admin - Add / Remove Mods
    </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
    <link href="style.css" rel="stylesheet" type="text/css">
	<style>
		input[type=hidden] {
			display: none; /* just in case */
		}
	</style>
</head>
<body>
    <div id='container'>
        <h1>Admin - Add / Remove Mods</h1>
		
		<p><?php echo $message; ?></p>
        
		<h3>Add a new mod</h3>
		<form action="edit-mods.php" method="post">
			<input type="hidden" name="action" value="add" />
			<label for="new-mod-id">User id: </label><input type="number" id="new-mod-id" name="mod_id" /><br/>
			<button type="submit" />
		</form>
		
		<h3>Current mods</h3>
		<?php
			$mods = forumQuery("SELECT * FROM `et_member` WHERE `account`=\"administrator\"", array());
			$mods2 = forumQuery("SELECT * FROM `et_member_group` WHERE `groupId`=1", array());
			for($i=1;$i<sizeof($mods2);$i++){
				for($j=0;$j<sizeof($mods);$j++){
					if($mods[$j]['memberId'] == $mods2[$i]['memberId']) continue 2;
				}
				
				$res = forumQuery("SELECT * FROM `et_member` WHERE `memberId`=?", array($mods2[$i]['memberId']))[0];
				array_push($mods, $res);
			}
			
			for($i=0;$i<sizeof($mods);$i++){
				echo "<div>";
				echo $mods[$i]['username'];
				echo "&nbsp;&nbsp;";
				echo "<form action=\"edit-mods.php\" method=\"post\">";
				echo "<input type=\"hidden\" name=\"action\" value=\"remove\" />";
				echo "<input type=\"hidden\" name=\"mod_id\" value=\"" . $mods[$i]['memberId'] . "\" />";
				echo "<button type='submit'>Remove Mod</button>";
				echo "</form></div>";
			}
		?>
    </div>
	
	<script>
		$("form").on("submit", function(e){
			if(!confirm("Are you sure?")){
				e.preventDefault();
				return false;
			}
		});
	</script>
</body>
