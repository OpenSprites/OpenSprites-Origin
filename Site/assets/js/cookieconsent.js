(function () {
    $(function () {
        if(sGeobytesMapReference === 'Europe ' && localStorage['cookies'] === undefined) {
            // Europe Cookie Consent Law
            $('head').append('<style>#cookielaw-text {position:absolute;left:40px;font-size:21px;height:27px;line-height:24px;} #cookielaw {position:fixed;bottom:0;right:0;width:100%;font-size:18px;line-height:18px;padding:12px;background:rgb(174,207,54);color:black;}</style>');
            
            $('body').prepend('<div id="cookielaw"><div id="cookielaw-text">We use cookies on this site. By using this site or closing this message, you are allowing us to use cookies. Our <a href="http://dev.opensprites.gwiddle.co.uk/privacy/">Privacy Policy</a> outlines how we use cookies and what they are.</div><a href="javascript:$(\'#cookielaw\').fadeOut(300);localStorage[\'cookies\'] = true;" class="btn blue" style="float: right; width: 100px; text-align:center;">Close</a></div>');
        }
    });
})();
