var countdown=60; 
function sendemail(){
	
    var obj = $('#getCode');
    settime(obj);
    
    }
function settime(obj) { 
	//发送验证码倒计时
	
    if (countdown == 0) { 
        obj.attr('disabled',false); 
        //obj.removeattr("disabled"); 
        obj.val("获取验证码");
        countdown = 60; 
        return;
    } else { 
        obj.attr('disabled',true);
        obj.val("重新发送(" + countdown + ")");
        countdown--; 
    } 
setTimeout(function() { 
    settime(obj) }
    ,1000) 
}

var countdowns=60; 
function sendemails(){
	
    var objs = $('#getCodes');
    settimes(objs);
    
}
function settimes(objs) { 

	//发送验证码倒计时
    if (countdowns == 0) { 
        objs.attr('disabled',false); 
        //obj.removeattr("disabled"); 
        objs.val("获取验证码");
        countdowns = 60; 
        return;
    } else { 
        objs.attr('disabled',true);
        objs.val("重新发送(" + countdowns + ")");
        countdowns--; 
    } 
setTimeout(function() { 
    settimes(objs) }
    ,1000) 
}