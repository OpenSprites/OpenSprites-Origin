<?php
    require "../assets/includes/connect.php"; //Connect - includes session_start(); require "../assets/includes/avatar.php";
    
    if($logged_in_user == 'not logged in' or $user_banned) {
        header('Location: /');
        die;
    }
	
	$errors = array(
		"toobig" => "That file is too big for our servers to handle! Try a file smaller than 8MB."
	);
?>
<!DOCTYPE html>
<html>

<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php echo file_get_contents( '../Header.html'); ?>

    <!--Imports styling-->
    <link href='style.css' rel='stylesheet' type='text/css'>
</head>

<body>
    <script>
        function selectMethod(type) {
            window.location = '?method=' + type; // timeout made it look bad
            // if you really want it to look nice, you should use ajax
        }
    </script>
    <!--Imports navigation bar-->
    <?php include "../navbar.php"; ?>

    <div class="container main" style="text-align:center;">
        <div class="main-inner">

			<?php if(isset($_REQUEST['error'])){ ?>
			<h1>Uh oh! Looks like something went wrong!</h1>
			<p><?php echo $errors[$_REQUEST['error']]; ?></p>
			<p><a href='/upload/'>Try again</a></p>
			
			<?php } else { ?>
			
            <?php if(!isset($_GET[ 'method'])) { ?>
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Choose an upload method</h1>
            <div id='upload-method-select'>
                <img src="local" class="method" onclick="selectMethod('local');">
                <img src="scratch" class="method" onclick="selectMethod('scratch');">
                <img src="url" class="method" onclick="selectMethod('url');">
            </div>
            <script>
                $('img[src=local]').attr('src', '/assets/images/upload/fromLocal' + localStorage['os-theme'] + '.png');
                $('img[src=scratch]').attr('src', '/assets/images/upload/fromScratch' + localStorage['os-theme'] + '.png');
                $('img[src=url]').attr('src', '/assets/images/upload/fromUrl' + localStorage['os-theme'] + '.png');
            </script>
            <?php } else if($_GET[ 'method']=='local' ) { ?>

            <!-- From Local File -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Select a file</h1>
            <div id='upload-method-select'>
                <form id='uploadform' enctype="multipart/form-data" action="upload.php" method="POST">
            		<input type="hidden" name="MAX_FILE_SIZE" value="8388608">
            		<input name="uploadedfile[]" type="file" required multiple><br>
                    <input name="name" type="text" placeholder="Pick a name..." required><br>
            		<input type="submit" value="Okay">
					<button type='button' onclick="$('form#uploadform').attr('action', '_matu_upload.php');this.innerText='Using';">Use MegaApuTurkUltra's upload script</button>
            	</form>
            </div>
            <?php } else if($_GET[ 'method']=='scratch' ) { ?>

            <!-- From Scratch -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Select a Scratch project</h1>

            <?php } else if($_GET[ 'method']=='url' ) { ?>

            <!-- From URL -->
            <h1 style="font-size:4em;margin-top:50px;">Upload</h1>
            <h1 style="font-size:3em;margin-top:10px;">Enter a URL</h1>

            <?php } else { header( 'location: /upload/'); } ?>

            <?php if(isset($_GET[ 'method'])){ ?>

            <p><a href="/upload/">Back</a>
            </p>

            <?php }} ?>

        </div>
    </div>

    <!-- footer -->
    <?php echo file_get_contents( '../footer.html'); ?>
</body>

</html>
