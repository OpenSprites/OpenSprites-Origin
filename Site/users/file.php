<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    $file = json_decode(file_get_contents('../site-api/asset.php?userid=' . $_GET['id'] . '&hash=' . $_GET['file']));
    if(count($file) == 0) {
        header('Location: /');
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        echo file_get_contents('../Header.html'); //Imports the metadata and information that will go in the <head> of every page
    ?>
    
    <link href='http://<?php
        echo $_SERVER['SERVER_NAME']; // Imports styling
    ?>/uploads/style.css' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php
        include "../navbar.php"; // Imports navigation bar
    ?>
    
    <script>
        OpenSprites.view = {};
        OpenSprites.view.file = <?php echo json_encode($file); ?>;
    </script>
    
    <!-- Main wrapper -->
    <div id='dark-overlay'><div id='overlay-inner'>
        <div id="user-pane-right">
            <div id='username'>
                <?php
                    if(!isset($file->name)) {
                        $file->name = 'untitled';
                    }
                    echo $file->name;
                ?>
            </div>
            <div id='description' onclick="window.location = '../users/<?php echo $file->uploaded_by->id; ?>';">
                By <?php echo $file->uploaded_by->name; ?>
            </div>
            <div id='follow' onclick="var win = window.open('download.php?name=<?php echo $file->custom_name; ?>&file=<?php echo $file->name; ?>', 'mywindow');setTimeout(function() {win.close();}, 1000);">
                <?php echo 'Download this ' . $file->type . '!'; ?>
            </div>
            <?php if($logged_in_userid == $file->uploaded_by->id) { ?>
            <div id='report' onclick="window.location = 'delete.php?file=<?php echo $file->name; ?>';">
                Delete
            </div>
            <?php } else {
                if ($is_admin == true) { ?>
                    <div id='report' onclick="window.location = 'admindelete.php?file=<?php echo $file->name; ?>';">
                        Delete (Admin)
                    </div>
                <?php }
            }?>

        </div>
        <div id="user-pane-left">
            <?php if($file->type == 'image') { ?>
            <img class="user-avatar x100" src="uploaded/<?php echo $file->name; ?>">
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
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
