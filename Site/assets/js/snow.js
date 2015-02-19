function scheduleMessage() {
    var today=new Date()

    var christmas=new Date(today.getFullYear(), 12, 25)
    if (today.getMonth()==12 && today.getDate()>25)
        christmas.setFullYear(christmas.getFullYear()+1)

    var timeout = christmas.getTime()-today.getTime();
    if( timeout > 2147483647 ){
        window.setTimeout( scheduleMessage(), 2147483647 )
    } else {
        window.setTimeout(function()
var snowsrc="http://opensprites.x10.mx/live/alpha/assets/images/SnowFlake.svg"
var no = 10;

var dx, xp, yp;
var am, stx, sty;
var i, doc_width = 800, doc_height = 600;

if (typeof window.innerWidth != 'undefined')
{
   doc_width = window.innerWidth;
   doc_height = window.innerHeight;
}
else
if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0)
{
   doc_width = document.documentElement.clientWidth;
   doc_height = document.documentElement.clientHeight;
}
else
{
   doc_width = document.getElementsByTagName('body')[0].clientWidth;
   doc_height = document.getElementsByTagName('body')[0].clientHeight;
}

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();

for (i = 0; i < no; ++ i)
{
   dx[i] = 0;
   xp[i] = Math.random()*(doc_width-50);
   yp[i] = Math.random()*doc_height;
   am[i] = Math.random()*20;
   stx[i] = 0.02 + Math.random()/10;
   sty[i] = 0.7 + Math.random();

   if (i == 0)
   {
      document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><a href=\"http://opensprites.x10.mx/\"><img src='"+snowsrc+"' border=\"0\"><\/a><\/div>");
   }
   else
   {
      document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src='"+snowsrc+"' border=\"0\"><\/div>");
   }
}

function DoSnow()
{
   for (i = 0; i < no; ++ i)
   {
      yp[i] += sty[i];
      if (yp[i] > doc_height-50)
      {
         xp[i] = Math.random()*(doc_width-am[i]-30);
         yp[i] = 0;
         stx[i] = 0.02 + Math.random()/10;
         sty[i] = 0.7 + Math.random();
      }
      dx[i] += stx[i];
      document.getElementById("dot"+i).style.top=yp[i]+"px";
      document.getElementById("dot"+i).style.left=xp[i] + am[i]*Math.sin(dx[i])+"px";
   }
   snowtimer=setTimeout("DoSnow()", 10);
}

setTimeout("DoSnow()", 500);
