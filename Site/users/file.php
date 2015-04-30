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
		),
		"description" => $raw['description']
	);
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='/uploads/style.css' rel='stylesheet' type='text/css'>
</head>
<body>
	<?php if($obj['type'] == "image"){ ?>
	<a href="#_" class="lightbox" id="img1">
		<img src="<?php echo $obj['url']; ?>">
	</a>
    <?php
		}
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <script>
        OpenSprites.view = {type: "file"};
        OpenSprites.view.file = <?php echo json_encode($obj); ?>;
    </script>
    
    <!-- Main wrapper -->
	<canvas id='background-img'></canvas>
    <div id='dark-overlay'><div id='overlay-inner'>
		    <div id='username' class='asset-name'>
                <?php
                    echo htmlspecialchars($obj['name']);
                ?>
            </div>
        <div id="user-pane-right">
            <div id='description'>
                <strong>By:</strong> <a href='/users/<?php echo $obj['uploaded_by']['id']; ?>/'><?php echo htmlspecialchars($obj['uploaded_by']['name']); ?></a><br/>
				<strong>Uploaded on:</strong> <?php
				sscanf($obj['upload_time'], "%u-%u-%u %u:%u:%u", $year, $month, $day, $hour, $min, $sec);
				$month = date('F', mktime(0, 0, 0, $month, 10));
				echo $day.' '.$month.' '.$year;
				?><br/>
				<strong>Downloads:</strong> <?php echo $obj['downloads']['this_week']; ?> this week; <?php echo $obj['downloads']['total']; ?> total<hr/>
            </div>
            <div id='follow'>
                <a href="/uploads/download.php?id=<?php echo $obj['uploaded_by']['id']; ?>&file=<?php echo $obj['md5']; ?>" target="_blank"><?php echo 'Download this ' . $obj['type']; ?></a>
            </div>
            <?php if($logged_in_userid == $obj['uploaded_by']['id']) { ?>
            <div id='delete'>
                <a class="file_delete" href="/uploads/delete.php?file=<?php echo $obj['md5']; ?>">Delete</a>
            </div>
            <?php } else {
                if ($is_admin == true) { ?>
                    <div id='delete'>
                        <a class="file_delete" href="/uploads/admindelete.php?id=<?php echo $obj['uploaded_by']['id']; ?>&file=<?php echo $obj['md5']; ?>">Delete (Admin)</a>
                    </div>
                <?php }
            }?>
			
			<?php if($logged_in_userid == $obj['uploaded_by']['id']) { ?>
				<div id="rename"><a class="file_rename" href="javascript:void(0)">Edit title or description</a></div>
				<div class="modal-bg"></div>
				<div id="file-rename-dialog">
					<div class="dialog-content">
						<h1>Change name or description</h1>
						<p class='input-error'>Sample Text</p>
						<input type="text" id="file-name" placeholder="Enter a descriptive name here" /><br/><br/>
						<textarea id="file-desc" placeholder="Describe your media or script here"></textarea><br/>
						<div class="buttons-container">
							<button class='dialog-button cancel'>Cancel</button>
							<button class='dialog-button primary-button ok'>OK</button>
						</div>
					</div>
					<div class="dialog-overlay">
						<p class="rename-status"></p>
					</div>
				</div>
			<?php } ?>
        </div>
        <div id="user-pane-left">
			<?php if($obj['type'] != "script"){ ?>
				<a href="#img1">
					<img class="img-preview" src="/uploads/thumbnail.php?file=<?php echo $obj['filename']; ?>">
					<script>
						OpenSprites.etc = {};
						OpenSprites.etc.bgSrc = "/uploads/thumbnail.php?file=" + <?php echo json_encode($obj['filename']); ?>;
					</script>
				</a>
			<?php } else { ?>
				<div class="img-preview"></div>
				<script>
					OpenSprites.etc = {};
					var model = OpenSprites.models.ScriptPreview($(".img-preview"));
					$.get(OpenSprites.view.file.url, function(data){
						model.loadJson(data);
					});
				</script>
			<?php } ?>
        </div>
    </div></div>

    <div class="container main" id="collections">
        <div class="main-inner">
            <?php if($obj['type'] == 'sound') { ?>
            <audio style="width: 100%;" controls preload='metadata' src='<?php echo $obj['url'] ?>';></audio><br/>
            <?php } ?>
			<h1>Description</h1>
			<p class='desc'><?php echo nl2br(htmlspecialchars($obj['description'])); ?></p>
        </div>
    </div>
	<script src="/uploads/edit.js"></script>
	<script src='/assets/lib/stackblur/stackblur.js'></script>
	<script>
		$(".file_delete").click(function(e){
			e.preventDefault();
			if(confirm("Are you sure you want to delete this file?")) location.href = $(this).attr("href");
		});
		
		// blurred background

		/**
		* By Ken Fyrstenberg
		*
		* drawImageProp(context, image [, x, y, width, height [,offsetX, offsetY]])
		*
		* If image and context are only arguments rectangle will equal canvas
		*/
		function drawImageProp(ctx, img, x, y, w, h, offsetX, offsetY) {
			if (arguments.length === 2) {
			x = y = 0;
				w = ctx.canvas.width;
				h = ctx.canvas.height;
			}
		
			// default offset is center
			offsetX = typeof offsetX === "number" ? offsetX : 0.5;
			offsetY = typeof offsetY === "number" ? offsetY : 0.5;
			
			// keep bounds [0.0, 1.0]
			if (offsetX < 0) offsetX = 0;
			if (offsetY < 0) offsetY = 0;
			if (offsetX > 1) offsetX = 1;
			if (offsetY > 1) offsetY = 1;
		
			var iw = img.width,
				ih = img.height,
				r = Math.min(w / iw, h / ih),
				nw = iw * r,   // new prop. width
				nh = ih * r,   // new prop. height
				cx, cy, cw, ch, ar = 1;
		
			// decide which gap to fill    
			if (nw < w) ar = w / nw;
			if (nh < h) ar = h / nh;
			nw *= ar;
			nh *= ar;
		
			// calc source rectangle
			cw = iw / (nw / w);
			ch = ih / (nh / h);
		
			cx = (iw - cw) * offsetX;
			cy = (ih - ch) * offsetY;
		
			// make sure source rectangle is valid
			if (cx < 0) cx = 0;
			if (cy < 0) cy = 0;
			if (cw > iw) cw = iw;
			if (ch > ih) ch = ih;

			// fill image in dest. rectangle
			ctx.drawImage(img, cx, cy, cw, ch,  x, y, w, h);
		}
		function drawBg(){
			var canvasId = "background-img";
			var canvas = document.getElementById(canvasId);
			var context = canvas.getContext("2d");
			var img = new Image();
			img.onload = function() {
				drawImageProp(context, img);
				stackBlurCanvasRGB(canvas, 0, 0, canvas.width, canvas.height, 10);
			}
			if(typeof OpenSprites.etc.bgSrc !== 'undefined'){
				img.src = OpenSprites.etc.bgSrc;
			}
		}
		
		drawBg();
		$(window).resize(drawBg);
	</script>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
