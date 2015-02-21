<!DOCTYPE HTML>
<?php
#
// I see you would like some idea as to where to go, since you found this file. I'm assuming you know how to read files,
// since A] I'm pretty sure all people who have servers that can run PHP know how to browse files, and B] you found this
// file.
#
// Basically this is a blog tool that's come with a whole bunch of things to improve your site. It's got themes to customize
// it your way. It runs through PHP and JavaScript ONLY, so you don't need any SQL, databases, or otherwise things you don't
// have and/or want (note that it has no support for comments and/or users, so think of it as a simple web log).
#
// To install it, all you have to do is move or copy this folder (the one containing this file) in to your server files.
// After that, assuming you have PHP installed, just open up [your site]/blog (or blog.[your site], or wherever you moved
// this file to) in your web browser. And now you're set! In order to make an entry to your post, you'll have to use the
// editor (at blog/editor.php), then copy the text it gave you in to a new file within blog/entries/ called NUMBER.xml, where
// NUMBER is the ID of your post, which should absolutely be the most recent ID + 1 (for example, name your file 2.xml if
// the entry with the highest ID is 1.xml).
#
// To customize your blog, open up blog/header.php and select an option from each "block". The defaults are:
//   Style option:       themes/beigedark/global.css
//   Sidebar option:     themes/sidebarleft.css
// You can also change or make one of your own themes by going in to the themes/ folder and editing the files there.
#
?>

<html>
    <head>
        <title>Oh noes!</title>
        <meta coding="utf-8">
    </head>
    <body>
        Whoops, you're reading this file the wrong way! Open this in your text editor!
    </body>
</html>