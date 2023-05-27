//可拖拽，吸附边框
    var bodyWidth=document.body.clientWidth;
    document.getElementById("btnHome").style.left=bodyWidth-46+"px";
    //getOffsetSum 获取相对与document的偏移量
    function getOffsetSum(ele){
        var top= ele.offsetTop,left=ele.offsetLeft;
        return { top:top, left:left }
    }
    var maindiv=document.getElementById("btnHome");
    maindiv.addEventListener("touchmove",touch,false);
 
    function touch(e)
    {
        switch(e.type)
        {
            case "touchmove":
                var ele=getOffsetSum(e.target);
                var left=ele.left;
                var top=ele.top;
                var x=e.touches[0].clientX-left/2;
                var y=e.touches[0].clientY-top/2;
                e.preventDefault();
 
                if (x < 100) {
                    e.target.parentNode.style.left="0";
                }else if (x >300) {
                    e.target.parentNode.style.left=bodyWidth-46+"px";
                }else{
                    e.target.parentNode.style.left=x+"px";
                }
                if (y < 100) {
                    e.target.parentNode.style.top = "30%";
                }else if(y > 500){
                    e.target.parentNode.style.top = "80%";
                }else{
                    e.target.parentNode.style.top = y+"px";
                }
                setCookie('touch_x',e.target.parentNode.style.left);
                setCookie('touch_y',e.target.parentNode.style.top);
        }
    }
    //固定此按钮
    var touch_x = getCookie('touch_x');
    var touch_y = getCookie('touch_y');
    var btnFloat = document.getElementById('btnHome');
    if(touch_x){
        btnFloat.style.left = touch_x;
    }
    if(touch_y){
        btnFloat.style.top = touch_y;
    }
 
    var username=document.cookie.split(";")[0].split("=")[1];
    //JS操作cookies方法!
    //写cookies
    function setCookie(name,value)
    {
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days*24*60*60*1000);
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";path=/";
    }
    //读cookies
    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    }
