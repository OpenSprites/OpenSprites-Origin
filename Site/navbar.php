<div class="header">
    <div class="container">	<a class="scratch" href="/live/alpha/"></a>

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
            <li><a href="/live/alpha/register/">Sign Up</a>
            </li>
            <li class="last" id='login'><span>Log In</span>
                <div id='login-popup'>
					<form method='POST' action='/assets/includes/login.php'>
						<input type='text' name='username'></input>
						<input type='password' name='password'></input>
						<input type='submit' value='Submit'></input>
					</form>
				</div>
            </li>
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
