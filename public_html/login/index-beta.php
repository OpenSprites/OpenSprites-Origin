<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    if($logged_in_user !== 'not logged in') {
        header('Location: /');
    }
	
	$return = "//" . $_SERVER['HTTP_HOST'] . (isset($_GET['return']) ? $_GET['return'] : '/');
	et_regenerateToken();
	$token = $_SESSION['token'];
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include '../Header.html'; ?>
	<script>
		OpenSprites.view = <?php echo json_encode(array("return" => $return, "token" => $token)); ?>;
	</script>
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:440px;">
        <div class="main-inner">
            <form enctype="multipart/form-data">
				<p id="login-message"></p>
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<div class="sheetBody">
					<div class="section">
						<ul class="form">
							<li><label>Username or Email</label>
								<div class="fieldGroup">
									<input type="text" name="username" id="os-user" placeholder="Username or email" autofocus />
								</div>
							</li>
							<li><label>Password <small><a href="/forums/?p=user/forgot" class="link-forgot" tabindex="-1">Forgot?</a></small></label>
								<div class="fieldGroup">
									<input type="password" name="password" id="os-pass" placeholder="Password" />
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="buttons">
					<input type="button" name="login" value="Log In" class="big submit button" />
					<input type="button" name="cancel" value="Cancel" class="big cancel button" />
				</div>
			</form>
        </div>
    </div>
	
	<script>
		var allowed = true;
		var waitSecs = 0;
		var interval = 0;
	
		$(".button.cancel").click(function(){
			location.href = OpenSprites.view.return;
		});
		
		$(".button.submit").click(function(){
			if(!allowed) return;
			$.post("/site-api/login.php", {token: OpenSprites.view.token, username: $("#os-user").val(), password: $("#os-pass").val()}, function(data){
				if(data.status == "success"){
					location.href = OpenSprites.view.return;
				} else {
					$("#login-message").text(data.message);
					if(data.hasOwnProperty("wait")){
						allowed = false;
						clearInterval(interval);
						waitSecs = data.wait;
						interval = setInterval(function(){
							waitSecs--;
							$("#login-message").text("Wrong username or password, wait " + waitSecs + " seconds before trying again");
							if(waitSecs <= 0){
								$("#login-message").text("Wrong username or password");
								allowed = true;
								clearInterval(interval);
							}
						}, 1000);
					}
				}
			}).fail(function(){
				$("#login-message").text("Whoops! We couldn't get a response from OpenSprites servers. Try again later.");
			});
		});
	</script>
    
    <!-- footer -->
    <?php include "../footer.html"; ?>
</body>
</html>
