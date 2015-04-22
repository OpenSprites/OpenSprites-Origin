<?php
require "../assets/includes/connect.php";  //Connect - includes session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <!--Imports the metadata and information that will go in the <head> of every page-->
    <?php include '../Header.html'; ?>
</head>
<body>
<!--Imports site-wide main styling-->
<link href='../main-style.css' rel='stylesheet' type='text/css'>

<!--Imports navigation bar-->
<?php include "../navbar.php"; ?>

<!-- Main wrapper -->
<div class="container main">
    <div class="main-inner">
        <div id="top-sprites">
            <div class="box" style="width: 700px;">
                <h1>Sprites</h1>
                <div class="box-content">
                    <?php
                    $select_image = mysqli_query($con, "SELECT * FROM os_assets WHERE assetType='image'") or die(mysql_error());
                    while($row = mysqli_fetch_array($select_image))
                        $rows[] = $row;

                    foreach($rows as $row) {
                        ?>
                        <div class="content">
                            <img style="width: 240px; min-height: 90px;" src="http://dev.opensprites.gwiddle.co.uk/uploads/uploaded/<?php echo $row['name'];?>"/>
                           <div id="text" style="width: 405px; float: right;">
                               <h3 style="line-height: 16px; text-overflow: ellipsis; display: block; white-space: nowrap; height: 16px;"><a href="http://dev.opensprites.gwiddle.co.uk/uploads/<?php echo $row['name'];?>"><?php echo $row['customName'] . "</br>"; ?></a></h3>
                            <div class="metadata">

                                Creator: <?php echo $row['user']; ?> - Downloads: <?php echo $row['downloadCount'];?>
                            </div>
                            <span style="display: block; color: #666; font-size: 11px;">Created: <?php echo $row['date'];?></span>
                               </div>
                            <hr>

                        </div>
                    <?php
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- footer -->
<?php echo file_get_contents('../footer.html'); ?>
</body>
</html>

