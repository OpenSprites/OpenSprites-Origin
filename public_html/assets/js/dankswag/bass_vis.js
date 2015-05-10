function createVisualizer(){
var player = $("audio").get(0);
var canvas = $("#vis-canvas").get(0);

player.onplay = function(){
	$(canvas).fadeIn();
};

player.onpause = function(){
	$(canvas).fadeOut();
};

$(canvas).fadeOut();

function drawCurve(ctx, points){
	ctx.moveTo(points[0].x, points[0].y);
	for (i = 1; i < points.length - 2; i ++){
		var xc = (points[i].x + points[i + 1].x) / 2;
		var yc = (points[i].y + points[i + 1].y) / 2;
		ctx.quadraticCurveTo(points[i].x, points[i].y, xc, yc);
	}
	ctx.quadraticCurveTo(points[i].x, points[i].y, points[i+1].x,points[i+1].y);
}

var pts = [];

function resizeVis(){
	canvas.width = canvas.height = $(window).height();
	$(canvas).css("left", (($(window).width() - $(window).height()) / 2) + "px");
}
resizeVis();
$(window).resize(resizeVis);

var ctx = canvas.getContext("2d");

var analyser;
var audioCtx = new (window.AudioContext || window.webkitAudioContext);

analyser = audioCtx.createAnalyser();
analyser.fftSize = 256 * 64;
analyser.smoothingTimeConstant = 0.1; // we kinda need shaking to be immediate

var source = audioCtx.createMediaElementSource(player);

source.connect(analyser);  // source > analyser > output
analyser.connect(audioCtx.destination);

streamData = new Uint8Array(128 * 64);

var shakeThreshold = 7000;
var shakeDelay = 1000;
var lastShakeTime = new Date().getTime();

var totalVol;

var oldColor = {r: 0, g: 0, b: 0};
var newColor = Please.make_color({format:'rgb'});
var lastColorTime = new Date().getTime();
var colorTransitionTime = 500;

setInterval(function(){
	oldColor = newColor;
	newColor = Please.make_color({format:'rgb'});
	lastColorTime = new Date().getTime();
}, colorTransitionTime);

var osLogo = new Image();
osLogo.src = "/assets/images/os-logotype.svg";

var sampleAudioStream = function() {
	if(player.paused){
		// ctx.clearRect(0, 0, canvas.width, canvas.height);
		requestAnimationFrame(sampleAudioStream);
		return;
	}

    analyser.getByteFrequencyData(streamData);
	totalVol = 0;
	for (var i = 0; i < 80; i++) {
		totalVol += Math.pow(streamData[i], 2.72) / 20000;
	}
	
	var offsetX = 0, offsetY = 0;
	
	if(totalVol > shakeThreshold){
		offsetX = Math.random() * 20 - 10;
		offsetY = Math.random() * 20 - 10;
		lastShakeTime = new Date().getTime();
	} else if(new Date().getTime() - lastShakeTime < shakeDelay){
		offsetX = Math.random() * 20 - 10;
		offsetY = Math.random() * 20 - 10;
	}
	
    for (var i = 0; i < 80; i++) {
		var data = Math.pow(streamData[i], 2.72) / 20000;
		if(data < 100) data = 100;
		
		data = data * (canvas.height / 2) / 250;
        pts[i] = {
			x: (data) * Math.cos(i * Math.PI * 2 / 160 - (Math.PI / 2)) + (canvas.height / 2) + offsetX,
			y: (data) * Math.sin(i * Math.PI * 2 / 160 - (Math.PI / 2)) + (canvas.height / 2) + offsetY
		};
    }
	
	for (var i = 80; i < 160; i++) {
		var data = Math.pow(streamData[160 - i], 2.72) / 20000;
		if(data < 100) data = 100;
		data = data * (canvas.height / 2) / 250;
        pts[i] = {
			x: (data) * Math.cos(i * Math.PI * 2 / 160 - (Math.PI / 2)) + (canvas.height / 2) + offsetX,
			y: (data) * Math.sin(i * Math.PI * 2 / 160 - (Math.PI / 2)) + (canvas.height / 2) + offsetY
		};
    }
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    ctx.beginPath();
    drawCurve(ctx, pts);
    ctx.closePath();
    ctx.strokeStyle = '#6677cc';
    ctx.lineWidth = 3;
    ctx.stroke();
	
	var fillColor = {
		r: oldColor.r + ((newColor.r - oldColor.r) * (new Date().getTime() - lastColorTime) / colorTransitionTime),
		g: oldColor.g + ((newColor.g - oldColor.g) * (new Date().getTime() - lastColorTime) / colorTransitionTime),
		b: oldColor.b + ((newColor.b - oldColor.b) * (new Date().getTime() - lastColorTime) / colorTransitionTime)
	};
	
	ctx.fillStyle = Please.RGB_to_HEX(fillColor);
	ctx.fill();
	
	var targetWidth = 180 * (canvas.height / 2) / 250;
	var targetHeight = osLogo.naturalHeight * targetWidth / osLogo.naturalWidth;
	var logoX = -(targetWidth / 2) + offsetX + (canvas.height / 2);
	var logoY = -(targetHeight / 2) + offsetY + (canvas.height / 2);
	ctx.drawImage(osLogo, 0, 0, osLogo.naturalWidth, osLogo.naturalHeight, logoX, logoY, targetWidth, targetHeight);
	requestAnimationFrame(sampleAudioStream);
};

requestAnimationFrame(sampleAudioStream);

}

if(!!window.AudioContext || !!window.webkitAudioContext) createVisualizer();