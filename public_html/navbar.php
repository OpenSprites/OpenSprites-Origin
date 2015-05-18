<script>
    // @deprecated
    var loggedInUser = "deprecated: Use 'OpenSprites.user.name'";
    var loggedInUserId = "deprecated: Use 'OpenSprites.user.id'";

    var OpenSprites = OpenSprites || {};
    OpenSprites.$SESSION = <?php echo json_encode($_SESSION); ?>;
    OpenSprites.user = <?php echo json_encode(array("name"=>$logged_in_user, "id"=>$logged_in_userid, "group"=>$user_group, "banned"=>$user_banned)); ?>;
</script>
<div class="header">

<!-- MOBILE NAV -->
<div class="mobile-nav" style="display:none;width:100%;">
	<center>
		<a href="/"><img src="http://opensprites.org/assets/images/os-logotype.svg" style="width: 150px;height: 35px;"></a>
		<select class='mobile-nav' style="float:right;margin-top:5px; margin-right:10px;" onchange="window.location.href=this.value">
			<option class='mobile-nav' disabled selected>☰</option>
			<option class='mobile-nav' value="/mobile/browse">Browse</option>
			<option class='mobile-nav' value="/blog">Blog</option>
			<option class='mobile-nav' value="/forums">Forums</option>
			<option class='mobile-nav' value="/search">Search</option>
			<option class='mobile-nav' value="/upload">Upload</option>
			<option class='mobile-nav last' value="/mobile/userpanel">User Panel</option>
		</select>
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
				<input type='text' id="search-input" placeholder='Search' />
				<div class="search-modal-overlay"></div>
				<div class="search-popup">
					<span class="symbol loading search-link"></span>
					<p class="search-message"></p>
					<div class="search-content"></div>
					<p class='search-links'>
						<a href="javascript:void(0);" class="all-results search-link">See all results + Search options</a> | <a href="/about/search/" class="search-help search-link" target="_blank">Search Help</a>
					</p>
				</div>
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

<!-- Search -->
<script>
	var timeoutKey = -1;
	$("#search-input").keyup(function(e){
		clearTimeout(timeoutKey);
		if(e.which == 13) doSearch();
		else timeoutKey = setTimeout(doSearch, 500);
	});
	$(".search-modal-overlay").click(function(){
		$(".search-popup").slideUp();
		$(".search-modal-overlay").hide();
	});
	$("#search-input").focus(function(){
		doSearch();
	});
	function doSearch(){
		var query = $("#search-input").val();
		if(query == null || query == "" || typeof query == "undefined") return;
		$('.all-results.search-link').attr('href', '/search/'+query);
		$(".search-popup").slideDown();
		$(".search-modal-overlay").show();
		$(".search-popup .search-content").html("");
		$(".search-popup .search-message").html("");
		$(".search-popup .symbol.loading").show();
		$.get("/site-api/search.php", {query: query, limit: 5}, function(data){
			$(".search-popup .symbol.loading").hide();
			$(".search-popup .search-content").html("");
			$(".search-popup .search-message").text(data.message + (data.num_results > 5 ? " total, showing top 5" : ""));
			if(data.warning.length > 0){
				for(var i=0;i<data.warning.length;i++){
					$(".search-popup .search-message").append("<br/>").append($("<span>").addClass("search-link").text(data.warning[i]));
				}
			}
			for(var i = 0; i < Math.min(5, data.results.length); i++){
				var result = data.results[i];
				var resultRow = $("<p>").addClass("result");
				resultRow.append($("<a>").addClass("search-link").attr("href", "/users/" + result.uploaded_by.id + "/" + result.md5 + "/").text(result.name));
				resultRow.append(" By ");
				resultRow.append($("<a>").addClass("search-link").attr("href", "/users/" + result.uploaded_by.id).text(result.uploaded_by.name));
				$(".search-popup .search-content").append(resultRow);
			}
		});
	}
</script>
