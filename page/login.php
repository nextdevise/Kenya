<?php
	session_start();
	if(isset($_SESSION['admin'])){
		header("location:/index.php");
		die();
	}
	if(isset($_SESSION['errpwd'])){
		if($_SESSION['errpwd'] > 0){
			$xs = 'style="display:block;"';
		}else{
			$xs = 'style="display:none;"';
		}
	}else{
		$xs = 'style="display:none;"';
	}
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	if(stripos($user_agent,'android')||stripos($user_agent,'iphone')){
	    if(stripos($user_agent,'MicroMessenger')){
	        $ok=true;//微信端
	    }else{
	        $ok=false;//其他移动端
	    }
	}else{
	    $ok=true;//PC端
	}
	if(!$ok){
	    die("请在微信端访问");
	}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>模板盒子资产管理系统</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
	<script type="text/javascript">
		var userAgent = navigator.userAgent; 
		var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1;
		if(isIE){
			window.location.href="/page/noie.html"
		}
	</script>
    <link rel="icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        html, body {margin: 0;width: 100%;height: 100%;overflow: hidden}
        body {background: #1E9FFF;background: url("../images/login_bg.jpg");background-repeat:no-repeat;background-size:100% 100%;}
        body:after {content:'';background-repeat:no-repeat;background-size:cover;-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:blur(3px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;}
        .layui-container {position:fixed;top:0;width: 100%;height: 100%;overflow: hidden}
        .admin-login-background {width:360px;height:260px;position:absolute;left:50%;top:50%;margin-left:-180px;margin-top:-130px;}
        .logo-title {text-align:center;letter-spacing:2px;padding:14px 0;}
        .logo-title h1 {color:#a8a9bb;font-size:25px;font-weight:bold;}
        .login-form {background-color:#223;border:1px solid #223;border-radius:3px;padding:14px 20px;box-shadow:0 0 8px #223;}
        .login-form .layui-form-item {position:relative;}
        .login-form .layui-form-item label {position:absolute;left:1px;top:1px;width:38px;line-height:36px;text-align:center;color:#d2d2d2;}
        .login-form .layui-form-item input {padding-left:36px;}
        .captcha {width:60%;display:inline-block;}
        .captcha-img {display:inline-block;width:34%;float:right;}
        .captcha-img img {height:34px;border:1px solid #e6e6e6;height:36px;width:100%;}
		.clouds_one {
		  background: url("../images/cloud_one.png");
		  background-repeat:no-repeat;
		  background-size:100% 100%;
		  position: absolute;
		  left: 0;
		  top: -180px;
		  height: 100%;
		  width: 300%;
		  -webkit-animation: cloud_one 50s linear infinite;
		  -moz-animation: cloud_one 50s linear infinite;
		  -o-animation: cloud_one 50s linear infinite;
		  animation: cloud_one 50s linear infinite;
		  -webkit-transform: translate3d(0, 0, 0);
		  -ms-transform: translate3d(0, 0, 0);
		  -o-transform: translate3d(0, 0, 0);
		  transform: translate3d(0, 0, 0);
		}
		.clouds_two {
		  background: url("../images/cloud_two.png");
		  background-repeat:no-repeat;
		  background-size:100% 100%;
		  position: absolute;
		  left: 0;
		  top: 0;
		  height: 100%;
		  width: 300%;
		  -webkit-animation: cloud_two 75s linear infinite;
		  -moz-animation: cloud_two 75s linear infinite;
		  -o-animation: cloud_two 75s linear infinite;
		  animation: cloud_two 75s linear infinite;
		  -webkit-transform: translate3d(0, 0, 0);
		  -ms-transform: translate3d(0, 0, 0);
		  -o-transform: translate3d(0, 0, 0);
		  transform: translate3d(0, 0, 0);
		}
		.clouds_three {
		  background: url("../images/cloud_three.png");
		  background-repeat:no-repeat;
		  background-size:100% 100%;
		  position: absolute;
		  left: 0;
		  top: -100px;
		  height: 100%;
		  width: 300%;
		  -webkit-animation: cloud_three 100s linear infinite;
		  -moz-animation: cloud_three 100s linear infinite;
		  -o-animation: cloud_three 100s linear infinite;
		  animation: cloud_three 100s linear infinite;
		  -webkit-transform: translate3d(0, 0, 0);
		  -ms-transform: translate3d(0, 0, 0);
		  -o-transform: translate3d(0, 0, 0);
		  transform: translate3d(0, 0, 0);
		}
		@-webkit-keyframes cloud_one {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@-moz-keyframes cloud_one {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@keyframes cloud_one {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		
		@-webkit-keyframes cloud_two {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@-moz-keyframes cloud_two {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@keyframes cloud_two {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@-webkit-keyframes cloud_three {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@-moz-keyframes cloud_three {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
		@keyframes cloud_three {
		  0% {
			left: 0
		  }
		  100% {
			left: -200%
		  }
		}
    </style>
</head>
<body>
<div class="layui-container" id="container">
    <div class="admin-login-background">
        <div class="layui-form login-form">
            <form class="layui-form" action="">
                <div class="layui-form-item logo-title">
                    <h1>资产管理系统</h1>
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-username" for="username"></label>
                    <input type="text" name="username" lay-verify="required|account" placeholder="用户名" autocomplete="off" class="layui-input" value="">
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-password" for="password"></label>
                    <input type="password" name="password" lay-verify="required|password" placeholder="密码" autocomplete="off" class="layui-input" value="">
                </div>

                <div class="layui-form-item">
                    <input type="checkbox" name="rememberMe" value="true" lay-skin="primary" title="记住密码">
                </div>

                <div class="layui-form-item">
                    <button class="layui-btn layui-btn layui-btn-normal layui-btn-fluid" lay-submit="" lay-filter="login">登 入</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/jquery.md5.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer;

        // 登录过期的时候，跳出ifram框架
        if (top.location != self.location) top.location = self.location;

        // 进行登录操作
        form.on('submit(login)', function (data) {
            data = data.field;
            if (data.username == '') {
                layer.msg('用户名不能为空');
                return false;
            }
            if (data.password == '') {
                layer.msg('密码不能为空');
                return false;
            }
			var isyzm = $("#yzmdiv").css("display");
			if(isyzm == 'block'){
				if (data.captcha == '') {
					layer.msg('验证码不能为空');
					return false;
				}
			}
			var p = $.md5(data.password);
			$.post("../action.php?mode=login",{user:data.username,pass:p,isyzm:isyzm,yzm:data.captcha},function(res){
				console.log(res);
				var r = JSON.parse(res);
				if(r.status == 1){
					layer.msg('登录成功', function () {
						window.location = '/index.php';
					});
				}else{
					layer.msg(r.msg);
					if(r.err > 0){
						$("#yzmdiv").css("display","block");
					}
					//if(r.status == -9){
					    $("#captchaPic").attr("src", '../yzm/?tm=' + Math.random());
					//}
					return false;
				}
			})
            return false;
        });
    });
	
	var d = new Date();
	var n = d.getDate();
	
	var x = n % 2;
	console.log(n,x);
	if(x == 0){
		//document.write('<script type="text/javascript" color="209,26,45" opacity="1" zIndex="-1" count="120" src="/js/line.js"><\/script>');
	}else{
		//document.write('<script type="text/javascript" src="/js/point-line.js"><\/script>');
	}
</script>




<script>
(function webpackUniversalModuleDefinition(root, factory) 
{
    if (typeof exports === 'object' && typeof module === 'object') {
        module.exports = factory();
    }
    else if (typeof define === 'function' && define.amd)
    {
        define([], factory);
    }
    else if (typeof exports === 'object') {
        exports["POWERMODE"] = factory();
    }
    else {
        root["POWERMODE"] = factory();
    }
})(this, function () 
{
    return (function (modules) {
        var installedModules = {};
        function __webpack_require__(moduleId) 
        {
            if (installedModules[moduleId]) {
                return installedModules[moduleId].exports;
            }
            var module = installedModules[moduleId] = {
                exports : {}, id : moduleId, loaded : false
            };
            modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
            module.loaded = true;
            return module.exports;
        }
        __webpack_require__.m = modules;
        __webpack_require__.c = installedModules;
        __webpack_require__.p = "";
        return __webpack_require__(0);
    })([function (module, exports, __webpack_require__) 
    {
        'use strict';
        var canvas = document.createElement('canvas');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        canvas.style.cssText = 'position:fixed;top:0;left:0;pointer-events:none;z-index:999999';
        window.addEventListener('resize', function () 
        {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
        document.body.appendChild(canvas);
        var context = canvas.getContext('2d');
        var particles = [];
        var particlePointer = 0;
        var frames = 120;
        var framesRemain = frames;
        var rendering = false;
        POWERMODE.shake = true;
        function getRandom(min, max) 
        {
            return Math.random() * (max - min) + min;
        }
        function getColor(el) 
        {
            if (POWERMODE.colorful) 
            {
                var u = getRandom(0, 360);
                return 'hsla(' + getRandom(u - 10, u + 10) + ', 100%, ' + getRandom(50, 80) + '%, ' + 1 + ')';
            }
            else {
                return window.getComputedStyle(el).color;
            }
        }
        function getCaret() 
        {
            var el = document.activeElement;
            var bcr;
            if (el.tagName === 'TEXTAREA' || (el.tagName === 'INPUT' && el.getAttribute('type') === 'text')) 
            {
                var offset = __webpack_require__(1)(el, el.selectionStart);
                bcr = el.getBoundingClientRect();
                return {
                    x : offset.left + bcr.left, y : offset.top + bcr.top, color : getColor(el)
                };
            }
            var selection = window.getSelection();
            if (selection.rangeCount) 
            {
                var range = selection.getRangeAt(0);
                var startNode = range.startContainer;
                if (startNode.nodeType === document.TEXT_NODE) {
                    startNode = startNode.parentNode;
                }
                bcr = range.getBoundingClientRect();
                return {
                    x : bcr.left, y : bcr.top, color : getColor(startNode)
                };
            }
            return {
                x : 0, y : 0, color : 'transparent'
            };
        }
        function createParticle(x, y, color) 
        {
            return {
                x : x, y : y, alpha : 1, color : color, velocity : {
                    x : - 1 + Math.random() * 2, y : - 3.5 + Math.random() * 2
                }
            };
        }
        function POWERMODE() 
        {
            {
                var caret = getCaret();
                var numParticles = 5 + Math.round(Math.random() * 10);
                while (numParticles--) 
                {
                    particles[particlePointer] = createParticle(caret.x, caret.y, caret.color);
                    particlePointer = (particlePointer + 1) % 500;
                }
                framesRemain = frames;
                if (!rendering) {
                    requestAnimationFrame(loop);
                }
            }

            {
                if (POWERMODE.shake) 
                {
                    var intensity = 1 + 2 * Math.random();
                    var x = intensity * (Math.random() > 0.5 ? - 1 : 1);
                    var y = intensity * (Math.random() > 0.5 ? - 1 : 1);
                    document.body.style.marginLeft = x + 'px';
                    document.body.style.marginTop = y + 'px';
                    setTimeout(function () 
                    {
                        document.body.style.marginLeft = '';
                        document.body.style.marginTop = '';
                    }, 75);
                }
            }
        };
        POWERMODE.colorful = false;
        function loop() 
        {
            if (framesRemain > 0) {
                requestAnimationFrame(loop);
                framesRemain--;
                rendering = true;
            }
            else {
                rendering = false;
            }
            context.clearRect(0, 0, canvas.width, canvas.height);
            for (var i = 0; i < particles.length; ++i) 
            {
                var particle = particles[i];
                if (particle.alpha <= 0.1) {
                    continue;
                }
                particle.velocity.y += 0.075;
                particle.x += particle.velocity.x;
                particle.y += particle.velocity.y;
                particle.alpha *= 0.96;
                context.globalAlpha = particle.alpha;
                context.fillStyle = particle.color;
                context.fillRect(Math.round(particle.x - 1.5), Math.round(particle.y - 1.5), 3, 3);
            }
        }
        requestAnimationFrame(loop);
        module.exports = POWERMODE;
    },
    function (module, exports) 
    {
        (function () 
        {
            var properties = ['direction', 'boxSizing', 'width', 'height', 'overflowX', 'overflowY', 'borderTopWidth', 
            'borderRightWidth', 'borderBottomWidth', 'borderLeftWidth', 'borderStyle', 'paddingTop', 'paddingRight', 
            'paddingBottom', 'paddingLeft', 'fontStyle', 'fontVariant', 'fontWeight', 'fontStretch', 'fontSize', 
            'fontSizeAdjust', 'lineHeight', 'fontFamily', 'textAlign', 'textTransform', 'textIndent', 
            'textDecoration', 'letterSpacing', 'wordSpacing', 'tabSize', 'MozTabSize'];
            var isFirefox = window.mozInnerScreenX != null;
            function getCaretCoordinates(element, position, options) 
            {
                var debug = options && options.debug || false;
                if (debug) 
                {
                    var el = document.querySelector('#input-textarea-caret-position-mirror-div');
                    if (el) {
                        el.parentNode.removeChild(el);
                    }
                }
                var div = document.createElement('div');
                div.id = 'input-textarea-caret-position-mirror-div';
                document.body.appendChild(div);
                var style = div.style;
                var computed = window.getComputedStyle ? getComputedStyle(element) : element.currentStyle;
                style.whiteSpace = 'pre-wrap';
                if (element.nodeName !== 'INPUT') {
                    style.wordWrap = 'break-word';
                }
                style.position = 'absolute';
                if (!debug) {
                    style.visibility = 'hidden';
                }
                properties.forEach(function (prop) 
                {
                    style[prop] = computed[prop];
                });
                if (isFirefox) {
                    if (element.scrollHeight > parseInt(computed.height)) {
                        style.overflowY = 'scroll';
                    }
                }
                else {
                    style.overflow = 'hidden';
                }
                div.textContent = element.value.substring(0, position);
                if (element.nodeName === 'INPUT') {
                    div.textContent = div.textContent.replace(/\s/g, " ");
                }
                var span = document.createElement('span');
                span.textContent = element.value.substring(position) || '.';
                div.appendChild(span);
                var coordinates = 
                {
                    top : span.offsetTop + parseInt(computed['borderTopWidth']), left : span.offsetLeft + parseInt(computed['borderLeftWidth'])
                };
                if (debug) {
                    span.style.backgroundColor = '#aaa';
                }
                else {
                    document.body.removeChild(div);
                }
                return coordinates;
            }
            if (typeof module != "undefined" && typeof module.exports != "undefined") {
                module.exports = getCaretCoordinates;
            }
            else {
                window.getCaretCoordinates = getCaretCoordinates;
            }
        }
        ());
    }])
});;
POWERMODE.colorful = true;
POWERMODE.shake = false;
document.body.addEventListener('input', POWERMODE);
</script>
</body>
</html>