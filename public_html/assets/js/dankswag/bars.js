$('#overlay-img').css('opacity', '1');
var player = $("audio").get(0);
var divs = [];
var c = Please.make_color({
    format: 'hsv'
});
var colors = Please.make_scheme({
    h: c.h,
    s: c.s,
    v: c.v
}, {
    scheme_type: 'complement',
    format: 'hex'
});
for (var i = 0; i < 80; i++) {
    var div = $("<div>");
    div.attr("id", "d" + i).attr('style', 'transition: background 250ms;position:fixed;z-index:-1;vertical-align:bottom;bottom:0;').css({
        "display": "inline-block",
        "width": "1.3%"
    }).addClass("bar");

    $("body").append(div);
    divs.push(div);
}

try {
    var analyser;
    var audioCtx = new(window.AudioContext || window.webkitAudioContext);
    analyser = audioCtx.createAnalyser();
    analyser.fftSize = 256;
    var source = audioCtx.createMediaElementSource(player);
    source.connect(analyser);
    analyser.connect(audioCtx.destination);

    streamData = new Uint8Array(128);
    volume = 0;

    var sampleAudioStream = function () {
        analyser.getByteFrequencyData(streamData);
        var total = 0;
        for (var i = 0; i < 80; i++) {
            total += streamData[i];
            var h = Math.pow(streamData[i], 2.72) / 20000;
            divs[i].height(h);
            divs[i].css('top', (window.innerHeight - h) / 2);
            divs[i].css('left', (window.innerWidth / 80) * i);
            divs[i].css('background', c[1]);
        }
        volume = total;

        window.requestAnimationFrame(sampleAudioStream);
    };
    sampleAudioStream();
} catch (e) {
    console.log('Bars Visualiser not supported.');
}