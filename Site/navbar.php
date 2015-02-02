<head>
	<LINK REL=StyleSheet HREF="navbar.css" TYPE="text/css" MEDIA=screen>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
</head>
<body>
	<div id="main">
		<ul>
		    <a href=""><li class="one">OpenSprites</li></a>
		    <!-- Just seems somewhat redundant -->
			<!-- <a href="http://opensprites.x10.mx/"><li class="two">Home</li></a> -->
			<a href="http://opensprites.x10.mx/"><li class="two">Upload</li></a>
			<a href="http://opensprites.x10.mx/"><li class="two">Resources</li></a>
			<a href="http://opensprites.x10.mx/"><li class="two">My account</li></a>
			<a href="https://github.com/OpenSprites/"><li class="two">Development</li></a>
			<a href="http://opensprites.x10.mx/"><li class="two">About</li></a>
			<?php
			  if(!isset($_SESSION["username"])){
			?>
            <a href="#"><li class="two">
              Create a free account
              <img src="/assets/images/hamburger.png" width="10" height="10">
            </li></a>
            <?php
              }else{
            ?>
              <a>
                <li class="two">
                  <?php echo $_SESSION["username"]; ?>
                  <!-- Image here maybe? -->
                </li>
              </a>
            <?php
              }
            ?>
        </ul>
	</div>