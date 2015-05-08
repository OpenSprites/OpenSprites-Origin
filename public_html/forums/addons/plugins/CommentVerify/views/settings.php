<?php
// Copyright 2015 Tristan van Bokkem

if (!defined("IN_ESOTALK")) exit;

$form = $data["reCAPTCHASettingsForm"];
?>
<?php echo $form->open(); ?>

<div class='section'>

<ul class='form'>

<li>
    <label><strong><?php echo T("Site Key"); ?></strong></label>
    <?php echo $form->input("sitekey"); ?>
</li>

<li>
    <label><strong><?php echo T("Secret Key"); ?></strong></label>
    <?php echo $form->input("secretkey"); ?>
    <small><?php echo T("message.reCAPTCHA.settings"); ?></small>
</li>

<li>
    <label><?php echo T("Language"); ?></label>
    <?php echo $form->select("language", array(
	"ar" => "Arabic",
	"bg" => "Bulgarian",
	"ca" => "Catalan",
	"zh-CN" => "Chinese (Simplified)",
	"zh-TW" => "Chinese (Traditional)",
	"hr" => "Croatian",
	"cs" => "Czech",
	"da" => "Danish",
	"nl" => "Dutch",
	"en-GB" => "English (UK)",
	"en" => "English (US)",
	"fil" => "Filipino",
	"fi" => "Finnish",
	"fr" => "French",
	"fr-CA" => "French (Canadian)",
	"de" => "German",
	"de-AT" => "German (Austria)",
	"de-CH" => "German (Switzerland)",
	"el" => "Greek",
	"iw" => "Hebrew",
	"hi" => "Hindi",
	"hu" => "Hungarain",
	"id" => "Indonesian",
	"it" => "Italian",
	"ja" => "Japanese",
	"ko" => "Korean",
	"lv" => "Latvian",
	"lt" => "Lithuanian",
	"no" => "Norwegian",
	"fa" => "Persian",
	"pl" => "Polish",
	"pt" => "Portuguese",
	"pt-BR" => "Portuguese (Brazil)",
	"pt-PT" => "Portuguese (Portugal)",
	"ro" => "Romanian",
	"ru" => "Russian",
	"sr" => "Serbian",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"es" => "Spanish",
	"es-419" => "Spanish (Latin America)",
	"sv" => "Swedish",
	"th" => "Thai",
	"tr" => "Turkish",
	"uk" => "Ukrainian",
	"vi" => "Vietnamese")); ?>
</li>


</ul>

</div>

<div class='buttons'>
<?php echo $form->saveButton(); ?>
</div>

<?php echo $form->close(); ?>
