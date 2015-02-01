//

var http = require('http'),
    url = require('url'),
    fs = require('fs'),
    express = require('express')();
var server;

function onStartup(){
  console.log('HTTP Server started up, listening.');
}
function getFile(fileName){
  return fs.readFileSync(__dirname+'/'+fileName).toString(); // Not fancy, but works
}
express.get('/', function sendMainPage(request, response){
  var r = response;
  r.writeHead(200, {'Content-Type': 'text/html' });
  r.write(getFile('./Frontend_HTML/Header.html'));
  r.write(getFile('./Frontend_HTML/Navbar.html'));
  r.write(getFile('./Frontend_HTML/Footer.html'));
  r.write('<div id="rightcontainer"><div id="signupprompt"><h2>Sign up for a free account!</h2><p>Share sprites, scripts, backdrops and costumes with others for free!</p></div></div>');
  r.end();
});
express.get('/main.css', function sendMainCss(request, response){
  response.end(getFile('./Frontend_HTML/Main.css'));
});
express.get('/navbar.css', function sendMainCss(request, response){
  response.end(getFile('./Frontend_HTML/Navbar.css'));
});
server = express.listen(8080, onStartup);