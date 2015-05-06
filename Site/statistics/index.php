<?php
    require $_SERVER['DOCUMENT_ROOT']."/assets/includes/connect.php";
    
    connectDatabase();
    $forum = forumQuery('SELECT * FROM ' . $forum_member_table, []);
    $assets = imagesQuery('SELECT * FROM ' . getAssetsTableName(), []);
    
    $stat_users = count($raw);
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        include $_SERVER['DOCUMENT_ROOT'].'/Header.html';
    ?>
    
    <style>
    .stats {
        margin: 0 0 1em 2em;
        border: 0;
        margin: 0;
        padding: 0;
        margin-top: -10px;
    }
    
    .stats li {
        list-style-type: none;
        font-size: 1.4923em;
        line-height: 1.1818em;
        color: #1aa0d8;
    }
    
    .stats .value {
        color: #F9A739;
    }
    </style>
</head>
<body>
    <link href='../main-style.css' rel='stylesheet' type='text/css'>
    
    <?php
        include $_SERVER['DOCUMENT_ROOT']."/navbar.php";
    ?>
    
    <div class="container main" style="height:400px;">
        <div class="main-inner">
            <h1>Statistics</h1>
            <ul class="stats">
                <li class="data"><span class="value"><?php echo 'todo'; ?></span> assets uploaded,</li>
                <li class="data"><span class="value"><?php echo $stat_users; ?></span> users registered,</li>
                <li>…and growing!</li>
            </ul>
        </div>
    </div>
    
    <!-- footer -->
    <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/footer.html'); ?>
</body>
</html>
