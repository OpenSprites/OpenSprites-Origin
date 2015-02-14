<!--<head>
	<LINK REL=StyleSheet HREF="navbar.css" TYPE="text/css" MEDIA=screen>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <style>
        </style>
</head>
<body>
	<div class="main">
		<ul>
		    <a href="index.php">
			<img id="oslogo" src="assets/images/os-logo-2.svg" alt="OpenSprites logo"></img>
			<a href="upload.php"><li class="one">Upload</li></a>
			<a href="./"><li class="two">Resources</li></a>
			<a href="https://github.com/OpenSprites/"><li class="two">Development</li></a>
			<a href="./"><li class="two">About</li></a>
			<?php
			  if(!isset($_SESSION["username"])){
			?>
            <a href="login.php"><li class="two">
              Login
            </li></a>
            <a href="register.php"><li class="two">
              Create a free account
              <img src="assets/images/Hamburger.png" width="10" height="10">
            </li></a>
            <?php
              }else{
            ?>
              <a>
                <li class="two">
			      <a href="account.php"><li class="two">My account</li></a>
			      <?php echo $_SESSION["username"]; ?>
                </li>
              </a>
            <?php
              }
            ?>
        </ul>
	</div>
-->

<body>
	<div class="header">
		<div class="container">
			<a class="scratch" href=""></a>
			<ul class="left">
				<li><a href="">Sprites</a></li>
				<li><a href="">Scripts</a></li>
				<li class="last"><a href="">Media</a></li>
			</ul>
			<ul class="right">
				<li><a href="/live/alpha/register/">Sign Up</a></li>
				<li class="last"><a href="">Log In</a></li>
			</ul>
		</div>
	</div>
</body>
