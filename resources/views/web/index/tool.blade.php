<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{$page_title ? $page_title : config('site.title')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/found/swiper.min.css">
		<link rel="stylesheet" type="text/css" href="/css/found/iconfont.css"> 
		<link rel="stylesheet" type="text/css" href="/css/found/style.css?23232">
		<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/swiper.min.js"></script>
		<script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
	</head>
	<body>
		<div class="main">
			<div class="top_bar">
				<div class="top_bar_title">实用工具</div>
				<div class="top_bar_right">
					<i class="iconfont icon-liebiao"></i>
					<div class="my_menu">
						<h3><a href="{{ route("web.index.found") }}">我的基金</a></h3>
						<ul>
							<li><a href="{{ route("web.index.logout") }}">退出</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="tools_main">
				<ul>
					
					<li class="geshui_item">
						<a target="_blank" href="https://www.gerensuodeshui.cn/">
						<span><i class="iconfont icon-37"></i></span>
						<p>个人所得税计算器</p>
						</a>
					</li>
					<li class="waihui_item">
						<a target="_blank" href="https://www.usd-cny.com/huilv.htm">
						<span><i class="iconfont icon-waihuihuikuan"></i></span>
						<p>外汇转换</p>
						</a>
					</li>
					<li class="stock_item">
						<a target="_blank" href="https://www.eastmoney.com/">
						<span><i class="iconfont icon-stock"></i></span>
						<p>股票价格查询</p>
						</a>
					</li>	
				</ul>
			</div>
			<div class="menu_tab">
				<ul>
					<li><a href="/"><span><i class="iconfont icon-shouye"></i></span><p>首页</p></a></li>
					<li><a href="{{ route("web.index.content", 1) }}"><span><i class="iconfont icon-guanlijijin"></i></span><p>基金</p></a></li>
					<li><a href="{{ route("web.index.content", 2) }}"><span><i class="iconfont icon-zhengcezhinanzhen"></i></span><p>政策法规</p></a></li>
					<li><a href="{{ route("web.index.content", 3) }}"><span><i class="iconfont icon-shuiwu"></i></span><p>税务</p></a></li>
					<li  class="active"><a href="javascript:;"><span><i class="iconfont icon-yingyong"></i></span><p>实用工具</p></a></li>
				</ul>
			</div>
		</div>
	</body>
	<script>
		var myDiv = $(".my_menu");
		$(function ()
		{
		$(".top_bar_right i").click(function (event) 
		{

		showDiv();
		$(document).one("click", function () 
		{
		$(myDiv).stop().slideToggle(100);
		
		});
		 
		event.stopPropagation();
		});
		$(myDiv).click(function (event) 
		{
		 
		event.stopPropagation();
		});
		});
		function showDiv() 
		{
		$(myDiv).stop().slideToggle(100);
		}
	</script>
</html>
