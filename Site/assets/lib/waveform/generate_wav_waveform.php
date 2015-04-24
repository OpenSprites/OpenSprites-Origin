<?php
	include "Waveform.php";
	include "Wave.php";
	include "Exception.php";
	include "Channel.php";
	include "Generator/Png.php";
	include "Generator/GeneratorInterface.php";
	include "Chunk/ChunkAbstract.php";
	include "Chunk/ChunkInterface.php";
	include "Chunk/Data.php";
	include "Chunk/Fmt.php";
	include "Chunk/Other.php";

	use BoyHagemann\Waveform\Waveform;
	use BoyHagemann\Waveform\Generator;
	$waveform =  Waveform::fromFilename($file);
	$waveform->setGenerator(new Generator\Png)
		->setWidh(200)
		->setHeight(200);
	echo $waveform->generate();
?>