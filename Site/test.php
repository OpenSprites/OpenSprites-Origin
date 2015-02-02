<!DOCTYPE html>
<html>
<head>
<title>RAINBOW</title>
</head>
<body>
<?php
/* Initialize variables */
$finalcolor;
$width = 1000;
$placeholder = array("16", "16", "0", "0", "0", "0");
$charset = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");

/* Start with red, increase green, creating yellow */
for ($i = 0; $i <= 15; $i++) {
	for ($j = 0; $j <= 15; $j++) {
		$finalcolor = "F" . "F" . "$charset[$i]" . "$charset[$j]" . "0" . "0";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
/* Start with yellow, decrease red, creating green */
for ($i = 15; $i >= 0; $i--) {
	for ($j = 15; $j >= 0; $j--) {
		$finalcolor = "$charset[$i]" . "$charset[$j]" . "F" . "F" . "0" . "0";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
/* Start with green, increase blue, creating indigo */
for ($i = 0; $i <= 15; $i++) {
	for ($j = 0; $j <= 15; $j++) {
		$finalcolor = "0" . "0" . "F" . "F" . "$charset[$i]" . "$charset[$j]";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
/* Start with indigo, decrease green, creating blue */
for ($i = 15; $i >= 0; $i--) {
	for ($j = 15; $j >= 0; $j--) {
		$finalcolor = "0" . "0" . "$charset[$i]" . "$charset[$j]" . "F" . "F";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
/* Start with blue, increase red, creating violet */
for ($i = 0; $i <= 15; $i++) {
	for ($j = 0; $j <= 15; $j++) {
		$finalcolor = "$charset[$i]" . "$charset[$j]" . "0" . "0" . "F" . "F";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
/* Start with violet, decrease blue, creating red */
for ($i = 15; $i >= 0; $i--) {
	for ($j = 15; $j >= 0; $j--) {
		$finalcolor = "F" . "F" . "0" . "0" . "$charset[$i]" . "$charset[$j]";
		/* Echo colored stripe */
		echo "<div style=\"width: $width px; height: 1px; background-color: #$finalcolor\"></div>";
	}
}
?>
</body>
</html>