<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    //$file = json_decode(file_get_contents('../site-api/asset.php?userid=' . $_GET['id'] . '&hash=' . $_GET['file']));
	connectDatabase();
	$id = -1;
	$file = "";
	if(isset($_GET['id'])) $id = intval($_GET['id']);
	if(isset($_GET['file'])) $file = $_GET['file'];
	
	$asset = imageExists($id, $file);
	$filename = NULL;
	if(sizeof($asset) > 0){
		$filename = $asset[0]['name'];
	}
	if($filename == NULL){
		include "../404.php";
		die;
	}
	
	$raw = $asset[0];
	$obj = array(
		"name" => $raw['customName'],
		"type" => $raw['assetType'],
		"url" => "/uploads/uploaded/" . $raw['name'],
		"filename" =>  $raw['name'],
		"md5" => $raw['hash'],
	  	"upload_time" => $raw['date'],
	  	"uploaded_by" => array(
	  		"name" => $raw["user"],
	  		"id" => $raw["userid"]
	  	),
		"downloads" => array(
			"this_week" => intval($raw['downloadsThisWeek']),
			"total" => intval($raw['downloadCount'])
		)
	);
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='//uploads/style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <script>
        OpenSprites.view = {type: "file"};
        OpenSprites.view.file = <?php echo json_encode($obj); ?>;
    </script>
    
    <!-- Main wrapper -->
    <div id='dark-overlay'><div id='overlay-inner'>
        <div id="user-pane-right">
            <div id='username'>
                <?php
                    echo $obj['name'];
                ?>
            </div>
            <div id='description'>
                <a href='/users/<?php echo $obj->uploaded_by->id; ?>/'>By <?php echo $obj->uploaded_by->name; ?></a>
            </div>
            <div id='follow'>
                <a href="/uploads/download.php?id=<?php echo obj->uploaded_by->id; ?>&file=<?php echo obj['md5']; ?>" target="_blank"><?php echo 'Download this ' . $obj->type; ?></a>
            </div>
            <?php if($logged_in_userid == $file->uploaded_by->id) { ?>
            <div id='report'>
                <a class="file_delete" href="/uploads/delete.php?file=<?php echo obj['md5']; ?>">Delete</a>
            </div>
            <?php } else {
                if ($is_admin == true) { ?>
                    <div id='report'>
                        <a class="file_delete" href="/uploads/admindelete.php?id=<?php echo obj->uploaded_by->id; ?>&file=<?php echo obj['md5']; ?>">Delete (Admin)</a>
                    </div>
                <?php }
            }?>

        </div>
        <div id="user-pane-left">
            <?php if($file->type == 'image') { ?>
            <img class="user-avatar x100" src="<?php echo $file->url; ?>">
            <?php } ?>
        </div>
    </div></div>

    <div class="container main" id="collections">
        <div class="main-inner">
            <?php if($file->type == 'sound') { ?>
            <audio style="width: 100%;" controls loop preload='metadata' src='<?php echo json_encode($file->url); ?>';></audio>
            <?php } ?>
        </div>
    </div>
	
	<script>
		$(".file_delete").click(function(e){
			e.preventDefault();
			if(confirm("Are you sure you want to delete this file?")) location.href = $(this).attr("href");
		});
	</script>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
