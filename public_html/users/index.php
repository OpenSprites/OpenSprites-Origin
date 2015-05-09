<?php
    require "../assets/includes/connect.php";
    
    error_reporting(0);
    
    if(is_numeric($_GET['id'])) {
    	$id = $_GET['id'];
    } else {
    	connectForumDatabase();
    	try {
    		$id = forumQuery("SELECT * FROM `$forum_member_table` WHERE `username`=?", array($_GET['id']))[0]['memberId'];
    	} catch(Exception $e) {
    		include '../404.php';
        	die();
    	}
    }
    
    $raw_json = file_get_contents("http://opensprites.gwiddle.co.uk/site-api/user.php?userid=" . $id);
    if(!isset(json_decode($raw_json, true)['userid'])) {
        include '../404.php';
        die();
    } else {
        $user_exist = true;
        $user = json_decode($raw_json, true);
        $username = $user['username'];
    }

    $profiledata = getUserInfo(intval($id));

    function unescape($inp) { 
        if(is_array($inp)) 
            return array_map(__METHOD__, $inp); 

        if(!empty($inp) && is_string($inp)) { 
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
    
    <link href='/users/user_style.css' rel='stylesheet' type='text/css'>
    <link href='/assets/js/spectrum/spectrum.css' rel='stylesheet' type='text/css'>
    <script src='/assets/js/spectrum/spectrum.js'></script>
    <style>textarea {resize: none; width: 99%; height: 90px;} #location {width: 99%;} .buttons-container {bottom: 15px;}</style>
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
		OpenSprites.view.user.profile = {};
		OpenSprites.view.user.profile.about = <?php echo json_encode($profiledata['about']); ?>
		OpenSprites.view.user.profile.location = <?php echo json_encode($profiledata['location']); ?>
		OpenSprites.view.user.profile.bgcolor = <?php echo json_encode($profileSettings['bgcolor']); ?>
    </script>
    
    <!-- Main wrapper -->
    <?php
	if($profileSettings['bgcolor'] == "avatar"){
    ?>
    <canvas id='background-img'></canvas>
    <?php } else { ?>
    <div id='background-img' style='background:<?php echo json_decode($editdata)->bgcolor; ?>;'></div>
    <?php } ?>
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
            <p class='about-section desc'>
				Loading...
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
    <script src="/assets/lib/marked/marked.js"></script>
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
		
		var desc = <?php echo json_encode($user['about']); ?>;
		
		function warnGoingAway(where){
			$(".modal.leaving .btn.blue").off();
			$(".modal.leaving .btn.blue").on("click", function(){
				$(".modal-overlay, .modal.leaving").fadeOut();
			});
		
			$(".modal.leaving .btn.red").off();
			$(".leaving-url").text(where);
			$(".modal-overlay, .modal.leaving").fadeIn();
			(function(where){
				$(".modal.leaving .btn.red").on("click", function(){
					window.open(where);
					$(".modal-overlay, .modal.leaving").fadeOut();
				});
			})(where);
		}
		
		function parseDesc(desc){
			//sad that we have to disallow HTML, but I can't find a good way to sanitize it DX
            var xx = marked(desc, {sanitize: true});
			// Instead of hax we should teach people to use 2 newlines
			$(".desc").html(xx);
			
			$(".desc a").each(function(){
				$(this).attr("target", "_blank");
				if($(this).attr("href").toLowerCase().startsWith("javascript")){
					$(this).attr("href", "https://www.youtube.com/watch?v=oHg5SJYRHA0"); // haha get rekt :P
				}
			});
			
			$(".desc a").click(function(e){
				var rawLink = $(this).get(0);
				var hostName = rawLink.hostname;
				if(!OpenSprites.etc.isHostSafe(hostName)){
					warnGoingAway($(this).attr("href"));
					e.preventDefault();
					return false;
				}
			});
		}
		
		parseDesc(desc);
    </script>
	<script src='/assets/lib/stackblur/stackblur.js'></script>
	
	<?php if($username==$logged_in_user) { ?>
    
	<!-- modal -->
    <div class="modal-overlay"></div>
    <div class="modal edit-profile">
		<div class="modal-content">
			<h1>Profile Settings</h1>
            <p class="error"></p>
            <p><i>Profile Background</i><br>You can set a color for your background on this profile page, or simply just use your avatar image.</p>
            <input type="checkbox" id='bg'>Use my avatar image<br>
            <span id='bg_true'><input type="text" name="bgcolor" id="bgcolor" value="rgb(101, 149, 147)"></span><br>
            
            <p><i>About Me</i><br>Write something about yourself that doesn't have your phone number, address, or anything else that is against the <a href='/tos/'>Terms Of Service</a>. Supports <a href='https://help.github.com/articles/github-flavored-markdown/'>Markdown</a>.</p>
            <textarea id='aboutme' maxlength='500'>Loading...</textarea><br>
            
            <p><i>Location</i><br>If you want to let people know which country you live in, you can tell them. Be warned - don't give away your exact location!</p>
            <input type='text' id='location' maxlength='30' value='Loading...'><br>
     
			<div class="buttons-container">
				<button class='btn red'>Cancel</button>
				<button class='btn blue'>OK</button>
			</div>
		</div>
	</div>
	
	<div class="modal leaving">
		<div class="modal-content">
			<h1>You are leaving OpenSprites!</h1>
			<p class="leaving-desc">
				[Insert some swaggy visual here]<br/><br/>
				This about section is taking you to <span class="leaving-url"></span><br/><br/>
				Sites that aren't OpenSprites have the potential to be dangerous, or could have unwanted content.<br/><br/>
				Proceed only if you recognize the site or understand the risk involved.
			</p>
			<div class="buttons-container">
				<button class='btn blue'>Stay here!</button>
				<button class='btn red'>Proceed</button>
			</div>
		</div>
	</div>
    
    <div class="modal leaving">
		<div class="modal-content">
			<h1>You are leaving OpenSprites!</h1>
			<p class="leaving-desc">
				[Insert some swaggy visual here]<br/><br/>
				This about section is taking you to <span class="leaving-url"></span><br/><br/>
				Sites that aren't OpenSprites have the potential to be dangerous, or could have unwanted content.<br/><br/>
				Proceed only if you recognize the site or understand the risk involved.
			</p>
			<div class="buttons-container">
				<button class='btn blue'>Stay here!</button>
				<button class='btn red'>Proceed</button>
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
