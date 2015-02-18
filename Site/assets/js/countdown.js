var strTargetDate = "12/25/2015 12:00 AM";
var strFormat = "$DAYS$ Days, $HOURS$ Hours, $MINUTES$ Minutes, $SECONDS$ Seconds.";
var strExpired = "Merry Christmas!";

function doCountDown(seconds)
{
    if (seconds < 0)
    {
        document.getElementById("countdown").innerHTML = strExpired;
        return;
    }
    var strMsg = strFormat;
    strMsg = strMsg.replace("$DAYS$",   ((Math.floor(seconds/86400))%100000).toString());
    strMsg = strMsg.replace("$HOURS$",  ((Math.floor(seconds/3600))%24).toString());
    strMsg = strMsg.replace("$MINUTES$",   ((Math.floor(seconds/60))%60).toString());
    strMsg = strMsg.replace("$SECONDS$",   ((Math.floor(seconds))%60).toString());

    document.getElementById("countdown").innerHTML = strMsg;

    setTimeout("doCountDown(" + (seconds-1).toString() + ")", 1000);
}

function initCountDown()
{
    var dtTarget = new Date(strTargetDate);
    var dtNow = new Date();
    var dtDiff = new Date(dtTarget-dtNow);
    var totalSeconds = Math.floor(dtdiff.valueOf()/1000);

    doCountDown(totalSeconds);
}

initCountDown();
