var Scanner = function()
{
    this.scanTimer = new SimpleTimer();

    this.Scan = function ()
    {
        var form = document.getElementById("scanRequestForm");
        var ipInput = document.getElementById("ip");
        var emailInput = document.getElementById("email");
        var scanDiv = document.getElementById("scanDiv");

        if (!ipInput.value)
        {
            alert("Scanner IP is empty");
            ipInput.focus();
        } else
        {
            var ip = ipInput.value;
            var email = emailInput.value;

            var ajax = new AJAX();
            var url = 'ip='+ip+'&email='+email+'&action=scan';

            ajax.AJAXAnswerReceived = this.OnScanFinished;
            ajax.SendAJAXRequest(url);

            document.getElementById("scanButton").style.display = 'none';
            scanDiv.innerHTML = "&nbsp;Scanning, please wait ....";

            this.scanTimer.Interval = 100;
            this.scanTimer.Tick = this.ScanProgressTick;
            this.scanTimer.Enable = true;
            this.scanTimer.Start();
        }
    }

    this.ScanProgressTick = function()
    {
        var scanProgressDiv = document.getElementById("scanProgressDiv");

        /*
        ◴25f4
        ◵25f5
        ◶25f6
        ◷25f7
        */

        var part = Date.now() % 4000;
        var ch = "\u25f4";
        if (part>3000)
        {
            ch = "\u25f7";
        } else
        if (part>2000)
        {
            ch = "\u25f4";
        } else
        if (part>1000)
        {
            ch = "\u25f5";
        } else
        {
            ch = "\u25f6";
        }

        scanProgressDiv.innerHTML = ch;
    }

    this.OnScanFinished = function(responseText)
    {
        document.getElementById("scanProgressDiv").style.display = 'none';
        document.getElementById("scanButton").style.display = 'inline';
        document.getElementById("scanDiv").innerHTML = responseText;
    }
}