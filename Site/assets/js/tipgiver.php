<?php

//Copy and paste this into another php file where you want the tip to be given.

$tips = array(
  "Remember to cap your scripts off with a stop this script block!",
  "OpenSprites was created by the OpenSprites team!",
  "Moving on from Scratch? Try Python!",
  "If you want to become a Scratcher, just be helpful and nice to other Scratchers!",
  "Have you got an account on OpenSprites yet?"
);

function giveTip()  {
return $tips[rand(0, tips.length)];
}

echo giveTip();

?>
