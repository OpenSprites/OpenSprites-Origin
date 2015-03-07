$('#forumTitle').before('<a id="back-to-the-main-site" style="position:absolute;top:40px;color:white;" href="http://dev.opensprites.x10.mx/">Back to the main site</a>');
$('#forumTitle').attr('style', 'padding-top:10px;padding-bottom:20px;');

// themes
$('#ftr-content .menu').append('<li id="theme"></li>');
$('#theme').append('Theme: <select id="theme-select" style="border-radius:5px;outline:none;"><option>Regular</option><option>Dark</option></select>');
