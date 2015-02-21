<?php
    // Footer file. Just set the contents of the <footer> to whatever you want.
    // Magic words to make things easier:
    // $AUTHORNAME$: Returns the author's name as specified in header.php.
    // $AUTHORLINK$: Returns the author's page link as specified in header.php.
    //  $COPYRIGHT$: Returns the copyright entity, &#169; or "©".
    //   $THISYEAR$: Returns the current year.
?>

<footer id="footer">
    $COPYRIGHT$ 2015 - $THISYEAR$ <a href="$AUTHORLINK$">$AUTHORNAME$</a>
    (used <a href="https://github.com/chjj/marked">marked</a> by chjj)
</footer>

<script>
    var footer = document.getElementById("footer");
    function replace_data(elem, replace_find, replace_with) {
        elem.innerHTML = elem.innerHTML.replace(replace_find, replace_with);
    }
    replace_data(footer, "$COPYRIGHT$", "&#169;");
    replace_data(footer, "$THISYEAR$", new Date().getFullYear());
    replace_data(footer, "$AUTHORNAME$", <?php echo "\"".$AUTHOR_NAME."\""; ?>);
    replace_data(footer, "$AUTHORLINK$", <?php echo "\"".$AUTHOR_LINK."\""; ?>);
</script>