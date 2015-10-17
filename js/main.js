var pubKey = 'pub-c-234be608-9e97-43e7-b10a-cca06d4daf82';
var subKey = 'sub-c-42bb9cbc-b7e2-11e4-addc-0619f8945a4f';
var channelName = "web_stock";

function initPN() {
 
     pubnub = PUBNUB.init({                                  
         publish_key   : pubKey,
         subscribe_key : subKey
     })
 
     pubnub.subscribe({                                      
         channel : channelName,
         message : function(message,env,channel){
           document.getElementById('chat').innerHTML +=
           message.username + ": " + message.text  + '<br>'
         },
         connect: pub
     })
 
     function pub() {
        
     }
 };


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}


function sendMessage(){
	var textBox = document.getElementById('textBox');
	var sendMsg = textBox.value;
    if(sendMsg != ""){
    	var user    = "Anonymous"; 
    	// Check cookies
        var theuser = getCookie("email");
        var newuser = theuser.substring(0, 4);
    	if (getCookie("email")!="") user=newuser;
    	else { user = "Anonymous"; }
    	var payload = {text:sendMsg, username:user};
    	pubnub.publish({                                     
             channel : channelName,
             message : payload,
             callback: function(m){ console.log(m) }
        })
    }
    textBox.value = "";
}
initPN();