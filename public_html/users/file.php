<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    //$file = json_decode(file_get_contents('../site-api/asset.php?userid=' . $_GET['id'] . '&hash=' . $_GET['file']));
	connectDatabase();
	$id = -1;
	$file = "";
	if(isset($_GET['id'])) {
		if(is_numeric($_GET['id'])) {
	    	$id = intval($_GET['id']);
	    } else {
	    	connectForumDatabase();
	    	try {
	    		$id = intval(forumQuery("SELECT * FROM `$forum_member_table` WHERE `username`=?", array($_GET['id']))[0]['memberId']);
	    	} catch(Exception $e) {
	    		include '../404.php';
	        	die();
	    	}
	    }
	}
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
	
	$userData = getUserInfo($raw["userid"]);
	if($userData['usertype'] == "suspended"){
		include "../404.php";
		die();
	}
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
    <?php if($obj['type'] == 'sound') { ?>
        <div id='overlay-img'></div>
    	<canvas id='background-img'></canvas>
		<canvas id="vis-canvas"></canvas>
    <?php } else { ?>
        <div id='overlay-img'></div>
    	<canvas id='background-img'></canvas>
    <?php } ?>
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
				<strong>Downloads:</strong> <?php echo $obj['downloads']['total']; ?><hr/>
            </div>
            <div id='follow'>
                <a <?php if($obj['type'] != 'script') { ?>href="/uploads/download.php?id=<?php echo $obj['uploaded_by']['id']; ?>&file=<?php echo $obj['md5']; ?>"<?php } ?> target="_blank"><?php echo 'Download this ' . $obj['type']; ?></a>
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
			
			<?php if($logged_in_userid == $obj['uploaded_by']['id'] || $is_admin) { ?>
				<div id="rename"><a class="file_rename" href="javascript:void(0)">Edit title or description<?php if($is_admin && $logged_in_userid !== $obj['uploaded_by']['id']){ echo " (Admin)"; } ?></a></div>
			<?php } ?>
        </div>
        <div id="user-pane-left">
			<?php if($obj['type'] != "script"){ ?>
				<a href="#img1">
					<img class="img-preview" src="/uploads/thumbnail.php?file=<?php echo $obj['filename']; ?>">
					<script>
						OpenSprites.etc = OpenSprites.etc || {};
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
				<audio style="width: 100%;" controls preload='metadata' src='<?php echo $obj['url'] ?>';></audio><br/><br/>
            <?php } ?>
			<h1>Description</h1>
			<p class='desc'></p>
			<?php if($obj['type'] == 'image'){ ?>
				<h2>Direct links</h2>
				<p>Use this link to embed this image on websites.</p>
				<input type="text" value="http://opensprites.gwiddle.co.uk/uploads/uploaded/<?php echo urlencode($obj['filename']); ?>" class="image-url" onfocus="$(this).select();" />
				<p>Copy and paste this BBCode to embed the image on forums such as the Scratch forums.</p>
				<input type="text" value="[img]http://opensprites.gwiddle.co.uk/uploads/uploaded/<?php echo urlencode($obj['filename']); ?>[/img]" class="image-url" onfocus="$(this).select();" />
			<?php } ?>
			<?php if($obj['type'] == 'script') { ?>
        	    <h2>Script</h2>
        	    <div id='script_preview'></div>
        	    <script>
        	        var scriptPrv = OpenSprites.models.ScriptPreview($("#script_preview"));
					$.get(OpenSprites.view.file.url, function(data) {
						scriptPrv.loadJson(data);
						$("#script_preview").attr('style', 'background: white; padding: 10px; border-radius: 20px;');
						$("#script_preview pre:first-child").css('display', 'block');
					});
        	    </script>
        	<?php } ?>
        </div>
    </div>
	
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
            try {
                var context = canvas.getContext("2d");
                var img = new Image();
                img.onload = function() {
                    drawImageProp(context, img);
                    stackBlurCanvasRGB(canvas, 0, 0, canvas.width, canvas.height, 10);
                }
                if(typeof OpenSprites.etc.bgSrc !== 'undefined'){
                    img.src = OpenSprites.etc.bgSrc;
                }
            } catch(e) {}
		}
		
		drawBg();
		$(window).resize(drawBg);
	</script>
    
	<?php if($logged_in_userid == $obj['uploaded_by']['id'] || $is_admin) { ?>
	
	<!-- modal -->
    <div class="modal-overlay"></div>
    <div class="modal edit-asset">
		<div class="modal-content">
			<h1>Edit title or description</h1>
			<p class='input-error' style='display:none;'>Sample Text</p>
			<input type="text" id="file-name" maxlength='32' value="<?php echo htmlspecialchars($obj['name']); ?>"/><br/><br/>
			<textarea id="file-desc" maxlength='500' value="<?php echo htmlspecialchars($obj['description']); ?>"></textarea><br/>
			<p>Descriptions support <acronym title="A simple text-formatting system">Markdown</acronym>. Click <a href="http://markdowntutorial.com/" target="_blank">here</a> to learn more about Markdown.</p>
			<div class="buttons-container">
				<button class='btn red'>Cancel</button>
				<button class='btn blue'>OK</button>
			</div>
		</div>
	</div>
	
	<?php } ?>
    
    <script src='/assets/lib/marked/marked.js'></script>
    <script>
        var desc = <?php echo json_encode(htmlspecialchars($obj['description'])); ?>;
		
		var descModel = OpenSprites.models.MdSection($(".about-section.desc"));
		descModel.updateMarkdown(desc);
    </script>
    
    <?php if($obj['type'] == "sound"){ ?>
		<!-- background colors! -->
		<script src="/assets/lib/please/please.js"></script>
		<?php if(!isset($_GET['vis']) || $_GET['vis'] === "default"){ //default ?>
		<!-- Circle visualizer -->
		<script>$('#overlay-img').css('transition', 'none');</script>
		<script src="/assets/js/dankswag/bass_vis.js"></script>
		<?php } else if ($_GET['vis'] === "bars") { ?>
		<!-- Bars -->
		<script src='/assets/js/dankswag/bars.js'></script>
		<?php } else { // none ?>
		<!-- Y U no want visualizer??? -->
		<?php } ?>
	
    <script>
        var j = Please.make_color({format: 'hsv'});
        var c = Please.make_scheme({
            h: j.h,
            s: j.s,
            v: j.v
        },
        {
            scheme_type: 'complement'
        });
        $('#overlay-img').css('background', c[0]);
        setInterval(function() {
            j = Please.make_color({format: 'hsv'});
            c = Please.make_scheme({
                h: j.h,
                s: j.s,
                v: j.v
            },
            {
                scheme_type: 'complement'
            });
            if(!document.getElementsByTagName('audio')[0].paused)
                $('#overlay-img').css('background', c[0]);
        }, 2000);
		
		var audioPlayer = $("audio");
		if(audioPlayer.length > 0){
			$(audioPlayer).on("play", function(){
				$("#overlay-img").fadeIn();
				$("#background-img").fadeOut();
			});

			$(audioPlayer).on("pause", function(){
				$("#overlay-img").fadeOut();
				$("#background-img").fadeIn();
			});
		}
    </script>
    
    <?php } ?>
	
	<script src="/uploads/edit.js"></script>
	
	<?php if($obj['type'] == 'script') { ?>
	<script src='/assets/js/jszip.min.js'></script>
	<script>
		var input = '<?php echo file_get_contents('http://opensprites.gwiddle.co.uk'.$obj['url']); ?>';
		var name = <?php echo json_encode($obj['name']); ?>
	
		$('#follow a').click(function() {
			var sprite = {
				"objName": name,
				"scripts": [[10, 10, JSON.parse(input)]],
				"sounds": [],
				"costumes": [{
				"costumeName": "costume1",
				"baseLayerID": 0,
				"baseLayerMD5": "f9a1c175dbe2e5dee472858dd30d16bb.svg",
				"bitmapResolution": 1,
				"rotationCenterX": 47,
				"rotationCenterY": 55
			}],
				"currentCostumeIndex": 0,
				"scratchX": 0,
				"scratchY": 0,
				"scale": 1,
				"direction": 90,
				"rotationStyle": "normal",
				"isDraggable": false,
				"indexInLibrary": 100000,
				"visible": true,
				"spriteInfo": {}
			};
			
			var zip = new JSZip();
			zip.file("sprite.json", JSON.stringify(sprite));
			zip.file("0.svg", '<?xml version="1.0" encoding="UTF-8" standalone="no"?> <!-- Created with Inkscape (http://www.inkscape.org/) --> <svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="120.71323" height="120.71323" id="svg5033" version="1.1" inkscape:version="0.48.4 r9939" sodipodi:docname="os-logo-3.svg"> <defs id="defs5035" /> <sodipodi:namedview id="base" pagecolor="#ffffff" bordercolor="#666666" borderopacity="1.0" inkscape:pageopacity="0.0" inkscape:pageshadow="2" inkscape:zoom="0.9899495" inkscape:cx="221.20706" inkscape:cy="45.666117" inkscape:document-units="px" inkscape:current-layer="g3023" showgrid="false" inkscape:window-width="1280" inkscape:window-height="1000" inkscape:window-x="0" inkscape:window-y="24" inkscape:window-maximized="1" fit-margin-top="0" fit-margin-left="0" fit-margin-right="0" fit-margin-bottom="0" /> <metadata id="metadata5038"> <rdf:RDF> <cc:Work rdf:about=""> <dc:format>image/svg+xml</dc:format> <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage" /> <dc:title></dc:title> </cc:Work> </rdf:RDF> </metadata> <g inkscape:label="Layer 1" inkscape:groupmode="layer" id="layer1" transform="translate(-341.53763,-221.84322)"> <g id="g5095"> <g id="g3023"> <rect style="fill:#659593;fill-opacity:1;stroke:none" id="rect7497" width="120.71323" height="120.71323" x="341.53763" y="221.84322" /> <g transform="matrix(1.7755226,0,0,1.7755226,-288.4612,-534.5169)" id="g5082"> <g inkscape:export-ydpi="90" inkscape:export-xdpi="90" inkscape:export-filename="/home/ryan/Pictures/open sprites/os3.png" style="fill:#ffffff" transform="translate(-998.81912,382.93647)" id="g9212"> <g style="fill:#ffffff" transform="matrix(2.7299333,0,0,2.7299333,-1820.3411,-7.0306086)" id="g9220"> <g style="fill:#ffffff" id="g9222" transform="matrix(0.62436546,0,0,0.62436546,438.48624,4.5611745)"> <path transform="matrix(0.31100482,0,0,0.31100482,720.87177,37.79203)" style="fill:#ffffff;fill-opacity:1;stroke:none" d="m 1495.8929,-10.941391 c 0,10.30602838 -8.3547,18.6607151 -18.6607,18.6607151 -10.3061,0 -18.6607,-8.35468672 -18.6607,-18.6607151 0,-10.306028 8.3546,-18.660715 18.6607,-18.660715 10.306,0 18.6607,8.354687 18.6607,18.660715 z" id="path9224" inkscape:connector-curvature="0" /> <path style="fill:#ffffff;fill-opacity:1;stroke:none" d="m 1180.1783,28.585651 5.9978,0 0,11.581086 -5.9978,0 z" id="rect9226" inkscape:connector-curvature="0" /> </g> <g style="fill:#ffffff" id="g9228" transform="matrix(-0.62436546,0,0,-0.62436546,1911.7385,57.037993)"> <path transform="matrix(0.31100482,0,0,0.31100482,720.87177,37.79203)" style="fill:#ffffff;fill-opacity:1;stroke:none" d="m 1495.8929,-10.941391 c 0,10.30602838 -8.3547,18.6607151 -18.6607,18.6607151 -10.3061,0 -18.6607,-8.35468672 -18.6607,-18.6607151 0,-10.306028 8.3546,-18.660715 18.6607,-18.660715 10.306,0 18.6607,8.354687 18.6607,18.660715 z" id="path9230" inkscape:connector-curvature="0" /> <path style="fill:#ffffff;fill-opacity:1;stroke:none" d="m 1180.1783,28.585651 5.9978,0 0,11.581086 -5.9978,0 z" id="rect9232" inkscape:connector-curvature="0" /> </g> </g> </g> </g> </g> </g> </g> </svg> ');
			var content = zip.generate({type:"blob"});
			saveAs(content, name+".sprite2");
		});
	</script>
	<?php } ?>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
