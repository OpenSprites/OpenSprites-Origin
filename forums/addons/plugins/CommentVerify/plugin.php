<?php

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["CommentVerify"] = array(
	"name" => "CommentVerify",
	"description" => "Custom plugin made for OpenSprites that checks the comments of the scratch verification project when logging in.",
	"version" => "1.0.0",
	"author" => "GrannyCookies",
	"authorEmail" => "16batesa@gmail.com",
	"authorURL" => "http://stefanbates.com",
	"license" => "MIT",
);


class ETPlugin_CommentVerify extends ETPlugin {

	public function setup()
	{
		// Don't enable this plugin if we are not running PHP >= 5.3.0.
		if (version_compare(PHP_VERSION, '5.3.0') < 0) {
			return "PHP >= 5.3.0 is required to enable this plugin.<br />However, you are running PHP ".PHP_VERSION;
		} else {
			return true;
		}
	}

	public function init()
	{
		// Include the Simple HTML DOM.
		require_once (PATH_PLUGINS."/CommentVerify/lib/simplehtmldom.php");
	}

	// Hook into the join function to include the Scratch form.
	public function handler_userController_initJoin($controller, $form)
	{
        
        // Add the Scratch section.
        $form->addSection("Scratch", 'Verify Scratch account');

        // Add the Scratch field.
        $form->addField("Scratch", "Scratch", array($this, "renderScratchField"), array($this, "processScratchField"));
	}

	function renderScratchField($form)
	{
		// Format the reCAPTCHA form with some JavaScript and HTML
		// retrieved from the Google reCAPTCHA library.
	    	return "
            <div style='width: 302px;'>
                <strong>Step One: </strong>Go to <a href='https://scratch.mit.edu/projects/47606468/' target='_BLANK'>this project.</a><br>
                <strong>Step Two: </strong>Comment this: <i>\"" . $_SESSION['token'] . "\"</i>.<br>
                <strong>Step Three: </strong>You're done!
            </div>".$form->getError("Scratch");
	}

	function processScratchField($form, $key, &$data)
	{
        // process stuff
        $url = 'http://dev.opensprites.gwiddle.co.uk/register/comments.php?user=' . $data["username"] . '&key=' . $_SESSION['token'];
        $resp = file_get_contents($url);
        
		if($resp == 'false') {
			$form->error("Scratch", 'We couldn\'t find the comment.');
		}
	}

	public function settings($sender)
	{
	}
}
