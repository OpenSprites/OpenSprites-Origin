<?php
    require "../assets/includes/connect.php";  //Connect - includes session_start();
    $file = json_decode(file_get_contents('uploaded/' . $_GET['file'] . '.json'));
    if(isset($file->deleted) or !file_exists('uploaded/' . $_GET['file'] . '.json')) {
        header('Location: ../404');
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
                    if(!isset($file->custom_name)) {
                        $file->custom_name = 'untitled';
                    }
                    echo $file->custom_name;
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
            <audio style="width: 100%;" controls loop preload='metadata' src='uploaded/<?php echo $file->name; ?>';></audio>
            <?php } ?>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents('../footer.html'); ?>
</body>
</html>
