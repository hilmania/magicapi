<html>
	<head>
		<title>404 - Page Not Found</title>
<style>
.sheep,.sheep .head,.sheep .top{position:relative}.scene,.sheep .body,.sheep .head,body,body:before{vertical-align:middle}.sheep .head .eye:before,.sheep .head:before,.sheep:before,body:before{content:''}body,html{margin:0;padding:0;width:100%;height:100%}@keyframes bob{0%{top:0}50%{top:.2em}}body{background:#FF5F6D;background:-webkit-linear-gradient(to right,#FFC371,#FF5F6D);background:linear-gradient(to right,#FFC371,#FF5F6D);text-align:center;transform:translate3d(0,0,0)}.sheep .head,.sheep .head:before{background:#211e21;display:inline-block}body:before{display:inline-block;height:100%;margin-right:-.25em}.scene{display:inline-block}.text{color:#fff;font-size:2em;font-family:helvetica;font-weight:700}.sheep{display:inline-block;font-size:1em}.sheep *{transition:transform .3s}.sheep .top{top:0;animation:bob 1s infinite}.sheep:hover .head{transform:rotate(0)}.sheep:hover .head .eye{width:1.25em;height:1.25em}.sheep:hover .head .eye:before{right:30%}.sheep:hover .top{animation-play-state:paused}.sheep .head{width:5em;height:5em;border-radius:100%;top:1em;transform:rotate(30deg)}.sheep .head:before{width:80%;height:50%;position:absolute;bottom:0;right:-10%;border-radius:50% 40%}.sheep .head:hover .ear.one,.sheep .head:hover .ear.two{transform:rotate(0)}.sheep .head .eye{display:inline-block;width:1em;height:1em;border-radius:100%;background:#fff;position:absolute;overflow:hidden}.sheep .head .ear,.sheep .head .eye:before{width:50%;position:absolute;border-radius:100%}.sheep .head .eye:before{display:inline-block;background:#000;height:50%;right:10%;bottom:10%;transition:all .3s}.sheep .head .eye.one{right:-2%;top:1.7em}.sheep .head .eye.two{right:2.5em;top:1.7em}.sheep .head .ear{background:#211e21;height:30%}.sheep .head .ear.one{left:-10%;top:5%;transform:rotate(-30deg)}.sheep .head .ear.two{top:2%;right:-5%;transform:rotate(20deg)}.sheep .body{display:inline-block;width:7em;height:7em;border-radius:100%;background:#fff;position:relative;margin-right:-3em}.sheep .legs,.sheep:before{display:inline-block;position:absolute}.sheep .legs{top:80%;left:10%;z-index:-1}.sheep .legs .leg{display:inline-block;background:#141214;width:.5em;height:2.5em;margin:.2em}.sheep:before{top:112%;width:100%;height:10%;border-radius:100%;background:rgba(0,0,0,.4)}
/* Credit: https://codepen.io/shubhra/pen/dicka */
</style>
	</head>
	<body>
		<div class="scene">
		    <div class="text" style="margin-top:-140px;"><h1>404<br><span style="font-size: 24px;">Page Not Found!</span></h1></div>
		  <div class="text" style="font-size: 15px;font-weight: 300; position: absolute;margin-left: 200px;margin-top: 20px;">Don't touch Me!</div>
		  <div class="text" style="font-size: 15px;font-weight: 300; position: absolute;margin-left: 200px;margin-top: 40px;">Please back to <a href="{{ url('/') }}" style="color:#fff;text-decoration: none;">[ Home ]</a></div>
		  <div class="sheep">
		    <span class="top">
		      <div class="body"></div>
		      <div class="head">
		        <div class="eye one"></div>
		        <div class="eye two"></div>
		        <div class="ear one"></div>
		        <div class="ear two"></div>
		      </div>
		    </span>
		      <div class="legs">
		        <div class="leg"></div>
		        <div class="leg"></div>
		        <div class="leg"></div>
		        <div class="leg"></div>
		    </div>
		  </div>
		</div>
	</body>
</html>