function getBrowserInfo(){
    var Sys = {};
    var ua = window.navigator.userAgent.toLowerCase();
    var re =/(msie|firefox|chrome|opera|iphone|ipad|bb10|mobile|version).*?([\d.]+)/;
    var m = ua.match(re);
    if (m) {
        Sys.browser = m[1].replace(/version/, "safari");
        Sys.ver = m[2]; 
    } else {
        Sys.browser = "other";
        Sys.ver = 'unknow';
    }
    return Sys;
}

function getQueryString(name)
{
     var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     var r = window.location.search.substr(1).match(reg);
     if(r!=null)return  unescape(r[2]); return null;
}

function getSubStringAsCharacter(str, n) {  
    var strReg = /[^\x00-\xff]/g;  
    var _str = str.replace(strReg,"**");  
    var _len = _str.length;  
    if(_len > n){  
        var _newLen = Math.floor(n/2);  
        var _strLen = str.length;  
        for(var i = _newLen; i < _strLen; i++){  
            var _newStr=str.substr(0,i).replace(strReg,"**");  
            if(_newStr.length >= n){  
                return str.substr(0,i);  
                break;  
            }  
        }  
    } else {  
        return str;  
    }  
}

//超过一定字数显示其他内容
(function($){
    $.fn.contentLimit = function(num, endShow){ 
        this.each(function(){   
            if(num > 0){
                var maxwidth = num;
                if($(this).text().replace(/[^\x00-\xff]/g,"**").length > maxwidth){
                    $(this).text(getSubStringAsCharacter($(this).text(), maxwidth));
                    $(this).html($(this).html() + endShow);
                }
            }                  
        });
    }         
})(jQuery);


function setCookie(name,value)
{
    var Days = 365;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

function getCookie(name) {
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}


function addPopup(content) {
    var top =  $(document).scrollTop();
    top += 120;
    $('#custom-popup-board').css('top' , top).show().find('.popup-content').text(content);
}

jQuery(document).ready(function($) {
    $('#custom-popup-board .popup-close-btn').click(function(event) {
        $('#custom-popup-board').hide();
    });
});