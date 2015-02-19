<div class="header">
    <div class="container">	<a class="scratch" href="/live/alpha/"></a><a href="/live/alpha/">
    <span class="logo-name"><b>OPEN</b>SPRITES</span></a>

        <ul class="left">
            <li><a href="">Sprites</a>
            </li>
            <li><a href="">Scripts</a>
            </li>
            <li class="last"><a href="">Media</a>
            </li>
        </ul>
		
		<ul class="right">
        <?php if(!isset($_SESSION['username'])) { ?>
            <li><a href="/live/alpha/register">Join OpenSprites!</a>
            </li>
            <li class="last" id='login'><span>Log In</span></li>
            <div id='login-popup'>
            	<div class="arrow"></div>
				<form method='POST' action='/live/alpha/assets/includes/login.php'>
					<span>Username</span><br><input type='text' name='username'></input>
					<span>Password</span><br><input type='password' name='password'></input>
					<input type='submit' value='Log In'></input>
				</form>
			</div>
            
        <?php } else  { ?>  <!-- display login info/username/etc -->
			<div id='login-display'>Welcome, <?= $_SESSION['username'] ?></div>
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
