<script>
    // @deprecated
    var loggedInUser = "deprecated: Use 'OpenSprites.user.name'";
    var loggedInUserId = "deprecated: Use 'OpenSprites.user.id'";

    var OpenSprites = OpenSprites || {};
    OpenSprites.$SESSION = <?php echo json_encode($_SESSION); ?>;
    OpenSprites.user = <?php echo json_encode(array("name"=>$logged_in_user, "id"=>$logged_in_userid, "group"=>$user_group, "banned"=>$user_banned)); ?>;
</script>
<div class="header">

<!-- MOBILE HEADER -->	
<div class="mobile-nav" style="display:none;width:100%;">
	<center>
		<a href="/"><img src="http://opensprites.org/assets/images/os-logotype.svg" style="width: 150px;height: 35px;"></a>
		<a style="color:white;float:right;text-decoration:none;margin-top:5px;margin-right:10px;cursor:pointer" class="menu">☰</a>
	</center>
</div>

    <div class="container" style='pointer-events:all;'>
        <a class="scratch" href="/"></a>

        <ul class="left">
			<li id="browse-li">
				<a href="javascript:void(0)" id="navbar-browse">Browse</a>
				<ul class='navbar-dropdown'>
				    <li>
						<a href="/media">Media</a>
					</li>
					<li>
						<a href="/scripts">Scripts</a>
					</li>
					<li>
						<a href="/collections">Collections</a>
					</li>
				</ul>
			<li>
                <a href="/blog">Blog</a>
            </li>
            <li class="last">
                <a href="/forums">Forums</a>
            </li>
			<li class='search'>
				<input type='text' placeholder='Search' />
			</li>
        </ul>

        <ul class="right">
            <?php if( $logged_in_user !=='not logged in' ) { ?>
            <li class='navbar-upload'>
                <a href="/upload/"><img class='upload-icon' src='/assets/images/upload.png' /> Upload</a>
            </li>
            <?php } if($logged_in_user == 'not logged in') { ?>
            <li><a href="/register/" style="width:initial;">Sign Up</a>
            </li>
            <li class="last" id='login'><a href='/login/?return=<?php echo $_SERVER['REQUEST_URI']; ?>'>Log In</a>
            </li>
            <?php } else { ?>
            <!-- display login info/username/etc -->
            <li>
                <a class='logged-in-user' style = 'padding: 0;padding-left: 10px;padding-right: 10px;max-width: 150px;text-overflow: ellipsis;overflow: hidden;'
						href="/users/<?php echo $logged_in_user . '/'; ?>">
                    <?php echo $logged_in_user; ?>
                </a>
            </li>
            <li class="last"><a href="/logout.php?return=<?php echo $_SERVER['REQUEST_URI']; ?>">Log Out</a>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
