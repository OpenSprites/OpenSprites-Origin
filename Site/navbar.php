<div class="header">
    <div class="container">	
    <a class="scratch" href="/"></a>

        <ul class="left">
            <li><a href="">Sprites</a>
            </li>
            <li><a href="">Scripts</a>
            </li>
            <li><a href="">Media</a>
            </li>
            <li><a href="/upload/">Upload</a>
            </li>
	    <li class="last"><a href="http://opensprites.x10.mx/forums/">Forums</a>
            </li>
        </ul>
		
		<ul class="right">
        <?php if($logged_in_user == 'not logged in') { ?>
            <li><a href="register">Sign Up</a>
            </li>
            <li class="last" id='login' onclick="window.location = 'http://opensprites.x10.mx/forums/?p=user/login&return=' + window.location.href;"><span>Log In</span></li>
            <!--<div id='login-popup'>
            	<div class="arrow"></div>
				<form method='POST' action='assets/includes/login.php'>
					<span>Username</span><br><input type='text' name='username'></input>
					<span>Password</span><br><input type='password' name='password'></input>
					<input type='submit' value='Log In'></input>
				</form>
			</div>-->
            
        <?php } else  { ?>  <!-- display login info/username/etc -->
			<li><a href="/users/<?php echo $logged_in_userid . '/'; ?>"><?php
			
			$raw_json = file_get_contents("http://scratch.mit.edu/site-api/users/all/" . $logged_in_user . "/");
			$user_arr = json_decode($raw_json, true);
			$user_avatar = $user_arr["thumbnail_url"];
			
			echo "<img class='user-avatar' style='width:32px;height:32px;' src='http:$user_avatar'>" . $logged_in_user;
			
			?></a></li>
			<li class="last" onclick="window.location = 'http://opensprites.x10.mx/forums/?p=user/logout&return=' + window.location.href;"><span>Log Out</span></li>
        <?php } ?>
        </ul>
    </div>
</div>
