<!DOCTYPE html>
<html>
    <head>
        <title>liam4's Blog</title>
        <?php include("header.php"); ?>
    </head>
    <body>
        <?php // This is slightly inspired by andrewjcole's blog, those who haven't should check it out at /Blog/andrewjcole but I'm not sure if it's been added to the main site yet :P ?>
        <?php include("includes.php"); ?>
        <div id="entries"></div>
        <script type="text/javascript">
            var count = 1;
            var entries = "";
            if (window.location.search.split("?")[1] > 1) {
                count = window.location.search.split("?")[1];
            }
            for (var i = count; i > 0; i--) {
                entries += blog_load_html(i.toString());
            }
            document.getElementById("entries").innerHTML = entries;
        </script>
    </body>
</html>