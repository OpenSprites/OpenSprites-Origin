<!DOCTYPE html>
<html>
    <head>
        <title>liam4's Blog | Post Overview</title>
        <link rel="stylesheet" href="blogoverview.css">
        <?php include("header.php"); ?>
    </head>
    <body>
        <?php include("includes.php"); ?>
        <div id="entries">
            <div class="entry">
                <h1>Post Overview</h1>
                <p>Here's a complete list of the <span id="amount"></span> blog posts on this blog, from most recent to oldest.</p>
                <div id="overview"></div>
            </div>
        </div>
        <script type="text/javascript">
            var overview = document.getElementById("overview");
            var amountelem = document.getElementById("amount");
            var posts = [<?php
                $items = glob("entries/*.xml");
                $number = 0;
                while ($number < count($items)) {
                    if (intval($items[$number][8]) < 1) {
                        unset($items[$number]);
                        $number -= 1;
                    }
                    $number += 1;
                }
                $parse = 0;
                while ($parse <= $number) {
                    echo "\"".$items[$parse]."\",";
                    $parse += 1;
                }
            ?>].reverse();
            var output = "";
            var amount = <?php echo $number; ?>;
            for (var i = posts.length - 1; i > 0; i--) {
                var header = $(blog_load(i.toString())).find("blogheader").text();
                var wordcount = marked($(blog_load(i.toString())).find("blogcontents").text()).split(' ').length;
                var previewtext = marked($(blog_load(i.toString())).find("blogcontents").text()).slice(0, 30);

                // These regular expressions will remove tags from the preview.
                // Kind of buggy, it doesn't work well with headers.. will have to
                // fix at some point.
                previewtext = previewtext.replace(/<\/?([phbi][1-6]?|blockquote|strong|em|sup|sub)[^>]*>/g, "");
                previewtext = previewtext.replace(/</g, "");
                previewtext = previewtext.replace(/>/g, "");
                output += "<div class='post'><div class='post-id'>" + i.toString() + "</div><a href='viewpost.php?post=" + i + "'>" + header + "</a><div class='post-preview-text'>" + previewtext + "</div><div class='post-word-count'>" + wordcount + "</div></div>";
            }
            overview.innerHTML = output;
            amountelem.innerHTML = amount;
        </script>
    </body>
</html>