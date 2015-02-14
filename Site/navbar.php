<div class="header">
    <div class="container">	<a class="scratch" href=""></a>

        <ul class="left">
            <li><a href="">Sprites</a>
            </li>
            <li><a href="">Scripts</a>
            </li>
            <li class="last"><a href="">Media</a>
            </li>
        </ul>
        <?php if(!isset($_SESSION['username'])) { ?>
        
        <ul class="right">
            <li>
                <a href="/live/alpha/register/">Sign Up</a>
            </li>
            <li class="last" id='login'>
                <a href="javascript:void(0);">Login</a>
            </li>
        </ul>
        
        <script>
            // when login is clicked do swag
            $('#login').on('click', function() {
                alert('swag');
            });
        </script>
        
        <?php } else  { ?>  <!-- display login info/username/etc -->
        
        <?php } ?>
        
    </div>
</div>
