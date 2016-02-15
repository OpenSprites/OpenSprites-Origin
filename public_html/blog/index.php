<!DOCTYPE html>
<html>
    <head>
        <title>OpenSprites Blog</title>
        <link href='../navbar.css' type="text/css" rel=stylesheet>
        <link href='/blogmainstyle.css' type="text/css" rel=stylesheet>
        <?php include("header.php"); ?>
    </head>
    <body>
        <?php include('../assets/includes/connect.php'); include("../navbar.php"); ?>
        <div id="entries"></div>
        <?php include("../footer.html"); ?>
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
            
            function backcall(r) {
                $("#entries").append(r).append('<hr>');
                $('code').wrap('<div>').each(function(i, block) {
                    hljs.highlightBlock(block);
                });
                
                i--;
                if(i > 0) {
                    entries += blog_load_html(i.toString(), function(r) {
                        backcall(r);
                    });
                }    
            }
            
            var entries = "";
            var i = count;
            entries += blog_load_html(i.toString(), function(r) {
                backcall(r);
            });
        </script>
    </body>
</html>
