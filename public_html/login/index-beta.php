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
</head>
<body>
    <!--Imports site-wide main styling-->
    <link href='/main-style.css' rel='stylesheet' type='text/css'>
    
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>
    
    <!-- Main wrapper -->
    <div class="container main" style="height:440px;">
        <div class="main-inner">
            <form action="//opensprites.org/forums/?p=user/login" method="post" enctype="multipart/form-data">
				<input type="hidden" name="return" value="<?php echo $return; ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<div class="sheetBody">
					<div class="section">
						<ul class="form">
							<li><label>Username or Email</label>
								<div class="fieldGroup">
									<input type="text" name="username" value="" autofocus />
								</div>
							</li>
							<li><label>Password <small><a href="/forums/?p=user/forgot" class="link-forgot" tabindex="-1">Forgot?</a></small></label>
								<div class="fieldGroup">
									<input type="password" name="password" value="">
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="buttons">
					<input type="submit" name="login" value="Log In" class="big submit button" />
					<input type="button" name="cancel" value="Cancel" class="big cancel button" />
				</div>
			</form>
        </div>
    </div>
	
	<script>
		$(".button.cancel").click(function(){
			location.href = <?php echo json_encode($return); ?>;
		});
	</script>
    
    <!-- footer -->
    <?php include "../footer.html"; ?>
</body>
</html>
