<!DOCTYPE html>
<html>
    <head>
        <title>Markdown Editor BETA</title>
        <link rel="stylesheet" href="editor.css">
        <?php include("header.php"); ?>
        <script src="editor.js" type="text/javascript"></script>
    </head>
    <body onload="setup()">
        <?php include("includes.php"); ?>
        <div id="entries">
            <div class="entry" id="editor">
                <p>This is in beta.. it doesn't work well yet, stick to using your traditional text editor please. :)</p>
                <h2 id="editor-title" contenteditable>Hello world</h2>
                <div id="editor-contents" contenteditable>###Put your markdown here!</div>
            </div>
            <div class="entry" id="preview"></div>
            </script>
        </div>
    </body>
</html>