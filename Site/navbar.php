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
            <li class="last" id='login'><a href="javascript:void(0);">Log In</a>
                <div id='login-popup' style="display:none;">
					<iframe src="/live/alpha/login.php" width="400" height="400">Could not load login.</iframe>
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
