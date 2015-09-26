<?php
require __DIR__."/../../includes/connect.php";
$project_comments = file_get_html('http://scratch.mit.edu/site-api/comments/project/47606468/');
$comments = $project_comments -> find('.comment .info');
$is_good_reg = false;
foreach ($comments as $comment) {
	$creator = trim($comment -> find('.name', 0) -> plaintext);
	$content = trim($comment -> find('.content', 0) -> plaintext);
	echo $creator.' '.$content.' ';
}
?>
