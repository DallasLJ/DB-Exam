<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0044)https://passport.bilibili.com/login?act=exit -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>跳转提示</title>
        <style type="text/css">
            body {
                background-repeat: no-repeat;
                color: #000;
                font:9pt/200% Verdana;
            }
            a,b {
                text-decoration: none;
                color:#659B28
            }
            a:hover {
                text-decoration: underline;
            }
            #successdiv {
                padding:30px;
                padding:36px 80px;
                border:1px solid #a9a9a9;background:#ffffff ; 
                text-align:center; 
                margin:20% auto; 
                background-repeat: no-repeat; 
                width:55%;
            }
        </style>
    </head>
    <body>
        <center>
            <div id="successdiv">
                <?php echo($message); ?>
                <br><b id="wait"><?php echo($waitSecond); ?></b><a id="href" href="<?php echo($jumpUrl); ?>">秒后，如果你的浏览器没反应，请点击这里...</a>
            </div>
        </center>
    </body>
<script type="text/javascript">
function removeElement(_element){
    var _parentElement = _element.parentNode;
    if(_parentElement){
        _parentElement.removeChild(_element);  
    }
}

(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
    var time = --wait.innerHTML;
    if(time <= 0) {
        location.href = href;
        clearInterval(interval);
    };
}, 1000);
})();
</script>
</html>