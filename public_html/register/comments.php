<?php
require __DIR__."/../../includes/connect.php";

$project_comments = file_get_html('http://scratch.mit.edu/site-api/comments/project/47606468/'.'?_='.mt_rand());
$comments = $project_comments -> find('.comment .info');
$is_good_reg = false;
foreach ($comments as $comment) {
	$creator = trim($comment -> find('.name', 0) -> plaintext);
	$content = trim($comment -> find('.content', 0) -> plaintext);
	if ($creator == $_GET['user'] && $content == $_GET['key']) {
		$is_good_reg = true;
		break;
	}
}

echo json_encode($is_good_reg);

?>
