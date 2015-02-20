//detects if your page is trapped inside someone elses frames and automatically breaks out
if(top != self) {
 top.onbeforeunload = function() {};
 top.location.replace(self.location.href);
}