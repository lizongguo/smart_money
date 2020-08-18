<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{$page_title ? $page_title : config('site.title')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/found/swiper.min.css">
		<link rel="stylesheet" type="text/css" href="/css/found/iconfont.css"> 
		<link rel="stylesheet" type="text/css" href="/css/found/style.css?3333">
		<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/swiper.min.js"></script>
		<script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
	</head>

	<body>
	<div class="main">
		<div class="top_bar">
			<div class="top_bar_left"><a href="javascript:history.back(-1);"><i class="iconfont icon-jiantou1"></i></a></div>
			<div class="top_bar_title"></div>
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
		
	<div class="view_main">
		<h1>{{ $info['title'] }}</h1>
		<div class="view_content">{!! $info->content !!}</div>
	</div>
	
		</div>	
		
	</body>
</html>
