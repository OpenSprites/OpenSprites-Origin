<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <?php include("header.php"); ?>
        <script src="blogload.js" type="text/javascript"></script>
    </head>
    <body>
        <?php // This is slightly inspired by andrewjcole's blog, those who haven't should check it out at /Blog/andrewjcole but I'm not sure if it's been added to the main site yet :P ?>
        <?php include("includes.php") ?>
        <p id="demo"></p>
        <script type="text/javascript">
            document.getElementById("demo").innerHTML = blog_load("1");
        </script>
    </body>
</html>