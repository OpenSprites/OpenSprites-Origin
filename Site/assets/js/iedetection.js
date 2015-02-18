//Script by EdenStudio
var browser = navigator.appName;
var ver = navigator.appVersion;
var pos = parseFloat(ver.indexOf("MSIE"))+1;
var version = parseFloat(ver.substring(pos+4, pos+7));
if ((browser == "Microsoft Internet Explorer") && (version < 8))
{
   window.location.replace("http://opensprites.x10.mx/ie.html"); //this is where the user gets moved to if their IE is under 8
}
