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
    navbar += '        <a class="scratch" href="//opensprites.org"></a>';
    navbar += '    </div>'
    navbar += '</div>';
    
    //$('body').append(navbar);
    
    // back to the main site link on navbar
    $('#forumTitle').before('<a id="back-to-the-main-site" style="position:absolute;top:40px;color:white;" href="http://opensprites.org/">Back to the main site</a>');
    $('#forumTitle').attr('style', 'padding-top:10px;padding-bottom:20px;');

    // logout fix
    $('.item-logout a').attr('href', 'http://opensprites.org/logout.php?return=http://opensprites.org/forums/?p=conversations');

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
