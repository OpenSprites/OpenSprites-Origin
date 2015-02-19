<?php // Main header file containing scripts and stylesheets. ?>

<link rel="stylesheet" href="themes/<?php if ($_POST['theme']) {echo $_POST['theme'];} else {echo "beigedark";}?>/global.css">
<script src="blogload.js" type="text/javascript"></script>
<script src="jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="marked/marked.min.js"></script>