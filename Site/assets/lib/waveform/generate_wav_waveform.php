<?php
	use BoyHagemann\Waveform\Waveform;
	use BoyHagemann\Waveform\Generator;
	$waveform =  Waveform::fromFilename($file);
	$waveform->setGenerator(new Generator\Png)
		->setWidh(960)
		->setHeight(400);
	echo $waveform->generate();
?>