<!DOCTYPE html>
<html>
    <head>
        <title>Index of Entries</title>
        <?php include("../header.php"); ?>
    </head>
    <body>
        <?php include("../includes.php"); ?>
        <?php
            $items = glob("./*.xml");
                    
            $parse = 0;
            while ($parse < count($items)) {
                if (substr($items[$parse], strlen($items[$parse]) - 3) !== 'xml') {
                    unset($items[$parse]);
                }
                $parse = $parse + 1;
            }
            
            for ($i = 0; $i < count($items); $i = $i + 1) {
                $item = substr($items[$i], 2 - strlen($items[$i]), strlen($items[$i]) - 6);
                $label = $item;
                
                echo "<li><a href='$item.xml'>$item.xml</a></li>";
            } ?>
    </body>
</html>