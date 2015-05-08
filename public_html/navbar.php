<script>
    // @deprecated
    var loggedInUser = "deprecated: Use 'OpenSprites.user.name'";
    var loggedInUserId = "deprecated: Use 'OpenSprites.user.id'";

    var OpenSprites = OpenSprites || {};
    OpenSprites.$SESSION = <?php echo json_encode($_SESSION); ?>;
    OpenSprites.user = <?php echo json_encode(array("name"=>$logged_in_user, "id"=>$logged_in_userid, "group"=>$user_group, "banned"=>$user_banned)); ?>;
</script>
<div class="header">
    <div class="container">
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
            <li class="last" id='login' onclick="window.location = '/login/';"><span>Log In</span>
            </li>
            <?php } else { ?>
            <!-- display login info/username/etc -->
            <li>
                <a class='logged-in-user' style = 'padding: 0;padding-left: 10px;padding-right: 10px;max-width: 150px;text-overflow: ellipsis;overflow: hidden;'
						href="/users/<?php echo $logged_in_user . '/'; ?>">
                    <?php echo $logged_in_user; ?>
                </a>
            </li>
            <li class="last" onclick="window.location = '/logout.php?return=<?php echo $_SERVER['REQUEST_URI']; ?>';"><span>Log Out</span>
            </li>
            <?php } ?>
        </ul>
    </div>
</div>
