var AJAX = function()
{
    //http://snipplr.com/view/2300/ajax-gethttpobject-function/

    this.AJAXUrl = 'index.php';

    this.AJAXAnswerReceived = function (responseText) { alert(responseText);};

    this.GetHTTPObject = function ()
    {
        var xhr = false;//set to false, so if it fails, do nothing
        if(window.XMLHttpRequest)
        {//detect to see if browser allows this method
            var xhr = new XMLHttpRequest();//set var the new request
        }
        else if(window.ActiveXObject)
        {//detect to see if browser allows this method
            try
            {
                var xhr = new ActiveXObject("Msxml2.XMLHTTP");//try this method first
            } catch(e)
            {//if it fails move onto the next

                try
                {
                    var xhr = new ActiveXObject("Microsoft.XMLHTTP");//try this method next
                } catch(e)
                {//if that also fails return false.
                    xhr = false;
                }
            }
        }
        return xhr;//return the value of xhr
    }

    this.SendAJAXRequest = function(command)
    {
        var AJAXHTTPObject = this.GetHTTPObject();
        if (AJAXHTTPObject)
        {
            var url =this.AJAXUrl;
            url += '?timeOfRequest=' + new Date().getTime();
            if (command != "")
            {
                url += '&' + command;
            }

            AJAXHTTPObject.open("GET", url, true);
            AJAXHTTPObject.send();

            var self = this;

            AJAXHTTPObject.onreadystatechange =  function()
            {
                if (AJAXHTTPObject.readyState==4 && AJAXHTTPObject.status==200)
                {
                    if (self.AJAXAnswerReceived != null)
                    {
                        self.AJAXAnswerReceived(AJAXHTTPObject.responseText);
                    }
                }
            }
        }
    }
}
