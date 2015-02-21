<!DOCTYPE html>
<html>
    <head>
        <title>liam4's blog</title>
        <?php include("header.php"); ?>
    </head>
    <body>
        <?php include("includes.php"); ?>
        <div id="entries"></div>
        <script type="text/javascript">
            var cont = <?php if ($_GET['post'] < count(glob("entries/*.xml"))) {echo 1;} else {echo -1;} ?>;
            console.log(cont);
            if (cont === 1) {
                var $head = blog_load(<?php echo "\"" . $_GET['post'] . "\"" ?>);
                $head = $($head).find("blogheader").text();
                document.getElementsByTagName("title")[0].innerHTML = "liam4's blog | " + $head;
                document.getElementById("entries").innerHTML = blog_load_html(<?php echo "\"" . $_GET['post'] . "\""; ?>);
            } else {
                document.getElementById("entries").innerHTML = blog_load_html("error_loading");
            }
        </script>
    </body>
</html>
