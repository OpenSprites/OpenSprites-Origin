<?php
    require "../assets/includes/connect.php";
    
    if(is_numeric($_GET['id'])) {
    	$id = $_GET['id'];
    } else {
    	connectForumDatabase();
    	$id = forumQuery("SELECT * FROM `$forum_group_member_table` WHERE `username`=?", array($_GET['username'])[0]['memberId'];
    }
    
    error_reporting(0);
    $raw_json = file_get_contents("http://opensprites.gwiddle.co.uk/site-api/user.php?userid=" . $id);
    if(!isset(json_decode($raw_json, true)['userid'])) {
        include '../404.php';
        die();
    } else {
        $user_exist = true;
        $user = json_decode($raw_json, true);
        $username = $user['username'];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='/users/user_style.css' rel='stylesheet' type='text/css'>
    <link href='/assets/js/spectrum/spectrum.css' rel='stylesheet' type='text/css'>
    <script src='/assets/js/spectrum/spectrum.js'></script>
</head>
<body>
    <?php
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <?php if($user_exist && ($raw_json['usertype'] != "suspended" || $is_admin)) {?>
    
    <script>
	var OpenSprites = OpenSprites || {};
        OpenSprites.view = {user: <?php echo json_encode($user); ?>};
        OpenSprites.view.user.id = <?php echo json_encode($user['userid']); ?>;
        OpenSprites.view.user.name = <?php echo json_encode($user['username']); ?>;
    </script>
    
    <!-- Main wrapper -->
    <canvas id='background-img'></canvas>
    <div id='dark-overlay'><div id='overlay-inner'>
        <div id="user-pane-right">
            <?php if($user_exist) { ?>
            <div id='username'>
                <?php
                if($username==$logged_in_user) {echo 'You';} else {echo htmlspecialchars($username);}
                ?>
            </div>
            <div id='description'>
                <?php
                    echo ucwords($user['usertype']);
                ?>
				<br/>
				<div id="location">
					<?php
						echo htmlspecialchars($user['location']);
					?>
				</div>
            </div>
			<div id="actions-container">
				<div id='follow'>
					<a href="https://scratch.mit.edu/users/<?php echo urlencode($username); ?>" target="blank">View Scratch Page</a>
				</div>
				<div id='report'>
					Report
				</div>
					<?php
						if($is_admin == true and $username !== $logged_in_user) {
						if($user['usertype'] == 'member'){
					?>
							<div id='adminban'>Suspend (Admin)</div>
					<?php
							} else if($user['usertype'] == 'suspended'){ ?>
							<div id='adminunban'>Unsuspend (Admin)</div>
					<?php
							}
						} ?>
					
					<?php if($username == $logged_in_user){ ?>
						<div id='settings'><a>Profile Settings</a></div>
					<?php } ?>
				</div>
			<?php } else { ?>
            <div id='username'>
                User not found!
            </div>
            <?php } ?>
        </div>
        <div id="user-pane-left">
            <?php
                if($user_exist) {
                    echo '<img id="source-avatar" class="user-avatar x100" src="' . $user['avatar'] . '">';
                    if($username == $logged_in_user) {
                        echo '<div id="change-image">Change...</div>';
                    }
                }
            ?>
        </div>
    </div></div>

    <?php if($user_exist) { ?>
    <div class="container main" id="about">
        <div class="main-inner">
            <h1>About Me</h1>
            <p>
				<?php echo nl2br(htmlspecialchars($user['about'])); ?>
			</p>
        </div>
    </div>
    
    <div class="container main" id="collections">
        <div class="main-inner">
            <h1 class='heading'>Loading...</h1>
            <div class='content assets-list'></div>
        </div>
    </div>
    <?php } ?>
    
    <?php } else {?>
    <div class="container main" style='margin-top: 40px;'>
        <div class="main-inner">
            <h1 id="opensprites-heading">Our server is a little confused...</h1>
            <div id="about">
                <img src='/assets/images/404.png' style='position: absolute; margin: auto; left: 0; right: 0;'>
                <div style='width: 100%; height: 470px;'>&nbsp;</div>
                <p style='position: absolute; margin: auto; top: 480px; left: 0; right: 0; width: 50%; text-align: center; font-size: 18px;'>We couldn't find the user you're looking for.<br>You may want to <a href='/'>go back to the main page</a>.</p>
            </div>
        </div>
    </div>
    <?php }?>
    
    <script>
        $('#change-image').click(function() {
            // display modal for changing profile pic
            $('#img-modal').fadeIn(200);
        });
		
		//////////// TODO: ajax-ify. Also fix server scripts
        $('#adminban').click(function() {
            if(confirm('Are you SURE you want to suspend ' + OpenSprites.view.user.name + '?')) {
                window.location = "/users/adminban.php?type=ban&username=" + OpenSprites.view.user.name;
            }
        });
		
		$('#adminunban').click(function() {
            if(confirm('Are you SURE you want to un-suspend ' + OpenSprites.view.user.name + '?')) {
                window.location = "/users/adminban.php?type=unban&username=" + OpenSprites.view.user.name;
            }
        });
		
		
		// let's only suspend, not delete
        /*$('#admindelete').click(function() {
            if(confirm('Are you SURE you want to pernamently DELETE ' + OpenSprites.view.user.name + '!?')) {
                window.location = "/users/admindelete.php?username=" + OpenSprites.view.user.name;
            }
        });*/
    </script>
	<script src='/assets/lib/stackblur/stackblur.js'></script>
	
	<?php if($username==$logged_in_user) { ?>
    
	<!-- modal -->
    <div class="modal-overlay"></div>
    <div class="modal">
		<div class="modal-content">
			<h1>Profile Settings</h1>
            
            <p><i>Profile Background</i><br>You can set a color for your background on this profile page, or simply just use your avatar image.</p>
            <input type="checkbox" id='bg'>Use my avatar image<br>
            <span id='bg_true'><input type="text" name="bgcolor" id="bgcolor" value="rgb(101, 149, 147)"></span>
            
			<div class="buttons-container">
				<button class='btn red'>Cancel</button>
				<button class='btn blue'>OK</button>
			</div>
		</div>
	</div>
    
	<!-- profile picture upload -->
	<form id='avatar_upload' style="display:none;" enctype="multipart/form-data" action="http://opensprites.gwiddle.co.uk/user-avatar.php?id=<?php echo $logged_in_userid; ?>" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
        <input name="uploadedfile" type="file" accept="image/*">
        <input type="submit">
    </form>
    
    <script>
        $('#change-image').click(function() {
            $('#avatar_upload input[name=uploadedfile]').click();
        });
        
        $('input[name=uploadedfile]').change(function() {
            $('#avatar_upload').submit();
        });
    </script>
	<?php } ?>
	
	<script src='../user.js'></script>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
