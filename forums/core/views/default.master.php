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
<title><?php echo sanitizeHTML($data["pageTitle"]); ?></title>
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

<div id='wrapper'>

<!-- HEADER -->
<div id='hdr'>
<div id='hdr-content'>

<div id='hdr-inner'>

<?php if ($data["backButton"]): ?>
<a href='<?php echo $data["backButton"]["url"]; ?>' id='backButton' title='<?php echo T("Back to {$data["backButton"]["type"]}"); ?>'><i class="icon-chevron-left"></i></a>
<?php endif; ?>

<h1 id='forumTitle'><a href='<?php echo URL(""); ?>'><?php echo $data["forumTitle"]; ?></a></h1>

<ul id='mainMenu' class='menu'>
<?php if (!empty($data["mainMenuItems"])) echo $data["mainMenuItems"]; ?>
</ul>

<ul id='userMenu' class='menu'>
<?php echo $data["userMenuItems"]; ?>
<li><a href='<?php echo URL("conversation/start"); ?>' class='link-newConversation button'><?php echo T("New Conversation"); ?></a></li>
</ul>

</div>
</div>
</div>

<!-- BODY -->
<div id='body'>
<div id='body-content'>
<?php echo $data["content"]; ?>
</div>
</div>

<!-- FOOTER -->
<div class="footer">
	<a href="http://dev.opensprites.gwiddle.co.uk/about/">About Us</a> - <a href="mailto:cheese.eater@mail.com">Contact Us</a> - <a href="http://dev.opensprites.gwiddle.co.uk/tos/">Terms of Service</a> - <a href="http://dev.opensprites.gwiddle.co.uk/privacy/">Privacy</a>
	<br>
	OpenSprites is a resources site developed by the <a href="https://github.com/OpenSprites/OpenSprites/blob/master/README.md">OpenSprites team</a>.<br>
	We are not endorsed or affiliated with the Scratch Team.<br>
	Unless where otherwise stated, this site is licensed under a MIT license 2015.<br>
	Scratch is a project of the Lifelong Kindergarten Group at the MIT Media Lab.<br>
</div>
<?php $this->trigger("pageEnd"); ?>

</div>

</body>
</html>
