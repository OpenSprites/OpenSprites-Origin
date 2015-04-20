<!DOCTYPE html>
<html>
    <head>
        <title>OpenSprites Blog</title>
        <?php include("header.php"); ?>
    </head>
    <body>
        <!-- This is slightly inspired by andrewjcole's blog, those who haven't should
        check it out at blog.opensprites.x10.mx/andrewjcole/ -->
        <?php include("includes.php"); ?>
        <div id="entries"></div>
        <script>
            var on_page_limit = 5;
            var count = <?php
                if(isset($_GET['count'])) {
                    $number = $_GET['count'];
                } else {
                    $number = 0;
                    foreach(glob("entries/*.xml") as $filename) {
                        if(is_numeric(substr($filename, 8, -4))) {
                            $number++;
                        }
                    }
                }
                echo $number;
            ?>;
            var entries = "";
            for (var i = count; i > count - on_page_limit && i > 0; i--) {
                entries += blog_load_html(i.toString(), function(r) {
                    $("#entries").append(r);
                    $('code').wrap('<pre>').each(function(i, block) {
                        hljs.highlightBlock(block);
                    });
                });
            }
        </script>
    </body>
</html>