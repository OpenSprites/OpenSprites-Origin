<?php
// Copyright 2015 Tristan van Bokkem

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["reCAPTCHA"] = array(
	"name" => "reCAPTCHA",
	"description" => "Protect your forum from spam and abuse while letting real people pass through with ease.",
	"version" => "1.2.5",
	"author" => "Tristan van Bokkem",
	"authorEmail" => "tristanvanbokkem@gmail.com",
	"authorURL" => "http://esotalk.org",
	"license" => "GPLv2",
	"dependencies" => array(
		"esoTalk" => "1.0.0g4"
	)
);


class ETPlugin_reCAPTCHA extends ETPlugin {

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
		// Include the Google reCAPTCHA library.
		require_once (PATH_PLUGINS."/reCAPTCHA/lib/recaptchalib.php");

		// Define default settings text.
        	ET::define("message.reCAPTCHA.settings", "Enter your reCAPTCHA Keys (<a href='https://www.google.com/recaptcha/admin#whyrecaptcha' target='_blank'>Don't have any keys yet? Get them here!</a>)");

        	ET::define("message.invalidCAPTCHA", "The CAPTCHA is invalid. Please try again.");
	}

	// Hook into the join function to include the reCAPTCHA form.
	public function handler_userController_initJoin($controller, $form)
	{
		if(C('plugin.reCAPTCHA.secretkey') && C('plugin.reCAPTCHA.sitekey')) {

			// Add the reCAPTCHA section.
			$form->addSection("recaptcha", T("Are you human?"));

			// Add the reCAPTCHA field.
			$form->addField("recaptcha", "recaptcha", array($this, "renderRecaptchaField"), array($this, "processRecaptchaField"));
		}
	}

	function renderRecaptchaField($form)
	{
		// Format the reCAPTCHA form with some JavaScript and HTML
		// retrieved from the Google reCAPTCHA library.
	    	return "<script type='text/javascript' src='https://www.google.com/recaptcha/api.js?hl=".C('plugin.reCAPTCHA.language')."' async defer></script>
			<div class='g-recaptcha' data-sitekey='".C('plugin.reCAPTCHA.sitekey')."'></div>
			<noscript>
  				<div style='width: 302px; height: 352px;'>
				<div style='width: 302px; height: 352px; position: relative;'>
				<div style='width: 302px; height: 352px; position: absolute;'>
					<iframe src='
						https://www.google.com/recaptcha/api/fallback?k='".C('plugin.reCAPTCHA.sitekey')."'
						frameborder='0'
						scrolling='no'
						style='width: 302px; height:352px; border-style: none;'>
					</iframe>
      				</div>
 				<div style='width: 250px; height: 80px; position: absolute; border-style: none; bottom: 21px; left: 25px; margin: 0px; padding: 0px; right: 25px;'>
				        <textarea id='g-recaptcha-response'
						name='g-recaptcha-response'
						class='g-recaptcha-response'
						style='width: 250px; height: 80px; border: 1px solid #c1c1c1; margin: 0px; padding: 0px; resize: none;'
						value=''>
        				</textarea>
				</div>
				</div>
				</div>
			</noscript>".$form->getError("recaptcha");
	}

	function processRecaptchaField($form, $key, &$data)
	{
		// The response from reCAPTCHA
		$resp = null;

		// Check for reCaptcha.
		$reCaptcha = new ReCaptcha(C('plugin.reCAPTCHA.secretkey'));
		$resp = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);

		// If no valid words are entered, show them an error.
		if ($resp === null || !$resp->success) {
			$form->error("recaptcha", T("message.invalidCAPTCHA"));
		}
	}

	public function settings($sender)
	{
		// Set up the settings form.
		$form = ETFactory::make("form");
		$form->action = URL("admin/plugins/settings/reCAPTCHA");

		$form->setValue("secretkey", C("plugin.reCAPTCHA.secretkey"));
		$form->setValue("sitekey", C("plugin.reCAPTCHA.sitekey"));
		$form->setValue("language", C("plugin.reCAPTCHA.language"));
		$form->setValue("language", C("plugin.reCAPTCHA.language", "en"));

		// If the form was submitted...
		if ($form->validPostBack()) {

			// Construct an array of config options to write.
			$config = array();
			$config["plugin.reCAPTCHA.secretkey"] = $form->getValue("secretkey");
			$config["plugin.reCAPTCHA.sitekey"] = $form->getValue("sitekey");
			$config["plugin.reCAPTCHA.language"] = $form->getValue("language");

			// Write the config file.
			ET::writeConfig($config);

			$sender->message(T("message.changesSaved"), "success autoDismiss");
			$sender->redirect(URL("admin/plugins"));
		}

		$sender->data("reCAPTCHASettingsForm", $form);
		return $this->view("settings");
	}
}
