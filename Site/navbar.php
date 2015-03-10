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
            <li class="last"><a href="/upload/">Upload</a>
            </li>
			<li class="last"><a href="http://opensprites.x10.mx/forums/">Forums</a>
            </li>
        </ul>
		
		<ul class="right">
        <?php if($logged_in_user == 'not logged in') { ?>
            <li><a href="register">Join OpenSprites!</a>
            </li>
            <li class="last" id='login' onlick="window.location = 'http://opensprites.x10.mx/forums/?p=user/login&return=' + window.location.href;"><span>Log In</span></li>
            <!--<div id='login-popup'>
            	<div class="arrow"></div>
				<form method='POST' action='assets/includes/login.php'>
					<span>Username</span><br><input type='text' name='username'></input>
					<span>Password</span><br><input type='password' name='password'></input>
					<input type='submit' value='Log In'></input>
				</form>
			</div>-->
            
        <?php } else  { ?>  <!-- display login info/username/etc -->
			<div id='login-display'><?php echo $logged_in_user; ?></div>
			<div id='login-menu'>
				<ul>
					<li>option1</li>
					<li>option2</li>
				</ul>
			</div>
        <?php } ?>
        </ul>
    </div>
</div>
