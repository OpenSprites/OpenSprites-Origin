<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Default master view. Displays a HTML template with a header and footer.
 *
 * @package esoTalk
 */
?>
<!DOCTYPE html>
<html>
<head>
<base target="_top">
<meta charset='<?php echo T("charset", "utf-8"); ?>'>
<title>OpenSprites Forums</title>
<link href='/navbar.css' rel='stylesheet'>
<style>
    .header .search input {
        padding: 1px !important;
        padding-left: 0.4em !important;
        padding-right: 0 !important;
    }
    
    .footer {
        margin-top: 2em !important;
        height: 125px !important;
    }
    
    #loadingOverlay-conversations {
        display: none !important;
    }
</style>
<?php echo $data["head"]; ?>
</head>

<body class='<?php echo $data["bodyClass"]; ?>'>
<?php $this->trigger("pageStart"); ?>

<div id='messages'>
<?php foreach ($data["messages"] as $message): ?>
<div class='messageWrapper'>
<div class='message <?php echo $message["className"]; ?>' data-id='<?php echo @$message["id"]; ?>'><?php echo $message["message"]; ?></div>
</div>
<?php endforeach; ?>
</div>

<!-- HEADER (sorry for the iframe, but database.php etc cannot be used!) 
<iframe id="navbar" src="/nav.php" style="
    width: 100%;
    overflow: hidden;
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    border: none;
    height: auto;
    background: transparent;
    z-index: -100;
" scrolling="no" seamless="seamless"></iframe>
-->
<?php require('../navbar.php'); ?>
    
<div id='wrapper' style='z-index:-1000;'>

<!-- BODY -->
<div id='body'>
<div id='body-content' style='z-index:999;'>
<?php echo $data["content"]; ?>
</div>
</div>

<!-- FOOTER -->
<?php require('../footer.html'); ?>
<?php $this->trigger("pageEnd"); ?>

</div>

</body>
</html>
