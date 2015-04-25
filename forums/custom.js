// is theme defined / valid?
var themes = ['Regular', 'Dark'];
if(typeof localStorage['os-theme'] === 'undefined' || themes.indexOf(localStorage['os-theme']) === -1) {
    console.log('wrong theme');
    localStorage['os-theme'] = 'Regular';
    window.location.reload();
}

// set theme based on the theme selection
var theme = localStorage['os-theme'];
$('head').append('<!-- theme = "' + theme + '" -->');
$('head').append('<link href="/forums/themes/' + 'all' + '.css' + '?_=' + (new Date()).toISOString() + '" rel="stylesheet" type="text/css">');
$('head').append('<link href="/forums/themes/' + theme.toLowerCase() + '.css' + '?_=' + (new Date()).toISOString() + '" rel="stylesheet" type="text/css">');

//////////////////////////////////////// NAVBAR ////////////////////////////////////////
$('body').css('display', 'none !important');
window.onload = function() {
    var navbar = '';
    navbar += '<div class="header">';
    navbar += '    <div class="container">';
    navbar += '        <a class="scratch" href="//dev.opensprites.gwiddle.co.uk"></a>';
    navbar += '        <ul class="left">';
    navbar += '            <li>';
    navbar += '                <a href="//dev.opensprites.gwiddle.co.uk/media">Media</a>';
    navbar += '            </li>';
    navbar += '            <li>';
    navbar += '                <a href="//dev.opensprites.gwiddle.co.uk/scripts">Scripts</a>';
    navbar += '            </li>';
    navbar += '            <li>';
    navbar += '                <a href="//dev.opensprites.gwiddle.co.uk/collections">Collections</a>';
    navbar += '            </li>';
    navbar += '            <li>';
    navbar += '                <a href="//blog.opensprites.gwiddle.co.uk">Blog</a>';
    navbar += '            </li>';
    navbar += '            <li class="last">';
    navbar += '                <a href="//opensprites.gwiddle.co.uk/forums">Forums</a>';
    navbar += '            </li>';
    navbar += '        </ul>';
    navbar += '        <ul class="right">';
    if(SESSION.userId) {
    navbar += '            <li class=\'navbar-upload\'>';
    navbar += '                <a href="//dev.opensprites.gwiddle.co.uk/upload/"><img class=\'upload-icon\' src=\'//dev.opensprites.gwiddle.co.uk/assets/images/upload.png\' /> Upload</a>';
    navbar += '            </li>';
    // display log info/username/etc
    navbar += '            <li>';
    navbar += '                <a style="padding: 0; padding-left: 10px; padding-right: 10px;" href="//dev.opensprites.gwiddle.co.uk/users/'+SESSION.userId+'">';
    navbar += $('.item-user a').text();
    navbar += '                </a>';
    navbar += '            </li>';
    navbar += '            <li class="last" onclick="window.location = \'//dev.opensprites.gwiddle.co.uk/logout.php?return=http://opensprites.gwiddle.co.uk/forums/\';"><span>Log Out</span></li>'
    } else {
    navbar += '            <li><a href="//dev.opensprites.gwiddle.co.uk/register/">Sign Up</a></li>';
    navbar += '            <li class="last" id=\'login\' onclick="window.location = \'/forums/?p=user/login&amp;return=conversations\';"><span>Log In</span></li>';
    }
    navbar += '        </ul></div></div>';
    
    $('body').append(navbar);
    
    // back to the main site link on navbar
    $('#forumTitle').before('<a id="back-to-the-main-site" style="position:absolute;top:40px;color:white;" href="http://dev.opensprites.gwiddle.co.uk/">Back to the main site</a>');
    $('#forumTitle').attr('style', 'padding-top:10px;padding-bottom:20px;');

    // logout fix
    $('.item-logout a').attr('href', 'http://dev.opensprites.gwiddle.co.uk/logout.php?return=http://opensprites.gwiddle.co.uk/forums/?p=conversations');

    // themes
    $('#ftr-content .menu').append('<li id="theme"></li>');
    $('#theme').append('Theme: <select id="theme-select" style="border-radius:5px;outline:none;"><option>Regular</option><option>Dark</option></select>');

    // when theme selection is changed
    $('#theme-select').on('change', function() {
        var newt = $(this).val();
        localStorage['os-theme'] = newt;
        window.location.reload();
    });

    
    // set selection to the saved theme
    $('#theme-select').val(localStorage['os-theme']);
    
    // channel stuff
    $('.channelListItem, a[data-channel=all]').remove();
    
    // new topic
    $('#channels').prepend('<li class="selected" onclick="window.location = \'/forums/?p=conversation/start\';"><a href="/forums/?p=conversation/start" title="Create a new topic!" class="newtopic channel" data-channel="newtopic" style="position: relative; left: 1px;">New Topic</a></li>');
    
    // settings and admin btns
    $('li.item-markListedAsRead').append('<li><a href="/forums/?p=settings"> Settings</a></li><li><a href="/forums/?p=admin"> Admin</a></li>');
    
    // close white screen of death
    $('body').css('display', 'block !important');
}

window.setInterval(function() {
    document.title = 'OpenSprites Forum';
}, 1000);
