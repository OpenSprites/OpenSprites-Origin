// detects if your page is trapped inside someone elses frames and automatically breaks out
if(top != self && top.location.host !== "opensprites.org") {
    top.onbeforeunload = function() {};
    top.location.replace(self.location.href);
}
