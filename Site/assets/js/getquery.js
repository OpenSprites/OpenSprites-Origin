// same as PHP $_GET['query']
function $_GET(q,s) { 
  s = s ? s : window.location.search; 
  var re = new RegExp('&'+q+'(?:=([^&]*))?(?=&|$)','i'); 
  return (s=s.replace(/^?/,'&').match(re)) ? (typeof s[1] == 'undefined' ? '' : decodeURIComponent(s[1])) : undefined; 
} 
