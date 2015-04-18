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
        <script type="text/javascript">
            var on_page_limit = 5;
            var count = <?php
                // Yeah, I'm putting PHP in a script. Woo!
                if ($_GET['count']) {
                    $number = $_GET['count'];
                } else {
                    $items = glob("entries/*.xml");
                    while ($number < count($items)) {
                        // Error log I was using for testing, but it's disabled at the moment since the program's working now.
                        #error_log("Number: $number; Current: " . $items[$number] . "; First Letter: " . $items[$number][8] . "; Int Value: " . intval($items[$number][8]));
                        if (intval($items[$number][8]) < 1) {
                            unset($items[$number]);
                            $number = $number - 1;
                        }
                        $number = $number + 1;
                    }
                }
                echo $number;
            ?>;
            var entries = "";
            for (var i = count; i > count - on_page_limit && i > 0; i--) {
                entries += blog_load_html(i.toString());
            }
            document.getElementById("entries").innerHTML = entries;
        </script>
    </body>
</html>