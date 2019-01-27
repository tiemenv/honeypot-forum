var socket;

document.addEventListener('DOMContentLoaded', init);
function init() {
    var host = "ws://192.168.10.119:9000/path/to/app"; // SET THIS TO YOUR SERVER

    try
    {
        socket = new WebSocket(host);
        log('WebSocket - status ' + socket.readyState);

        socket.onopen = function(msg)
        {
            if(this.readyState == 1)
            {
                log("We are now connected to websocket server. readyState = " + this.readyState);
            }
        };

        //Message received from websocket server
        socket.onmessage = function(msg)
        {
            log(" [ + ] Received: " + msg.data);
        };

        //Connection closed
        socket.onclose = function(msg)
        {
            log("Disconnected - status " + this.readyState);
        };

        socket.onerror = function()
        {
            log("Some error");
        }
    }

    catch(ex)
    {
        log('Some exception : '  + ex);
    }

    $("msg").focus();
}

function send()
{
    var txt, msg;
    txt = $("msg");
    msg = txt.value;

    if(!msg)
    {
        alert("Message can not be empty");
        return;
    }

    txt.value="";
    txt.focus();

    try
    {
        socket.send(msg);
        log('Sent : ' + encodeHTML(msg));
    }
    catch(ex)
    {
        log(ex);
    }
}

function quit()
{
    if (socket != null)
    {
        log("Goodbye!");
        socket.close();
        socket=null;
    }
}

function reconnect()
{
    quit();
    init();
}

// Utilities
function $(id)
{
    return document.getElementById(id);
}

function log(msg)
{
    $('log').innerHTML += '<br />' + msg;
    $('log').scrollTop = $('log').scrollHeight;
}

function onkey(event)
{
    if(event.keyCode==13)
    {
        send();
    }
}

function logout() {
    let cookies = document.cookie.split(";");

    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        let eqPos = cookie.indexOf("=");
        let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }

    window.location.reload();

}

function encodeHTML(s) {
    return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
}