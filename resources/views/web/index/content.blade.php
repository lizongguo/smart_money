<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{$page_title ? $page_title : config('site.title')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/found/swiper.min.css">
		<link rel="stylesheet" type="text/css" href="/css/found/iconfont.css"> 
		<link rel="stylesheet" type="text/css" href="/css/found/style.css?v=0518">
		<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/swiper.min.js"></script>
		<script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
	</head>
	<body>
	<div class="main">
		<div class="top_bar">
			<div class="top_bar_title">{{ $type_val }}</div>
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
		<form>
			<div class="search_box">
					<input type="text" name="sh[title]" value="{{ $sh['title'] }}" class="search_input" placeholder="请输入关键字"/>
					<button class="search_btn" type="submit"><i class="iconfont icon-search"></i></button>
			</div>
		</form>
		<div class="list_main">
			<ul>
				@if(isset($rs) and count($rs) > 0)
                    @foreach($rs as $k => $v)
                         	<a href="{{ route("web.index.contentDetail", [$v['id']]) }}"><li><h4>{{ $v['title'] }}</h4><span><i class="iconfont icon-jiantou"></i></span></li></a>
                    @endforeach 	
                @else
                	<li>暂无相关信息。</li>
				@endif
			</ul>
		</div>
		<div class="menu_tab">
			<ul>
				<li><a href="/"><span><i class="iconfont icon-shouye"></i></span><p>首页</p></a></li>
                <li @if($type==1) class="active" @endif><a href="{{ route("web.index.content", 1) }}"><span><i class="iconfont icon-guanlijijin"></i></span><p>基金</p></a></li>
                <li @if($type==2) class="active" @endif><a href="{{ route("web.index.content", 2) }}"><span><i class="iconfont icon-zhengcezhinanzhen"></i @if($type==3) class="active" @endif></span><p>政策法规</p></a></li>
                <li @if($type==3) class="active" @endif><a href="{{ route("web.index.content", 3) }}"><span><i class="iconfont icon-shuiwu"></i></span><p>税务</p></a></li>
                <li><a href="{{ route("web.index.tool") }}"><span><i class="iconfont icon-yingyong"></i></span><p>实用工具</p></a></li>
			</ul>
		</div>
	</div>	
	<script type="text/javascript">
		$(document).ready(function() {
			//悬浮
			var nav = $(".search_box");
			var position = nav.position();
			var divTop = nav.position().top; //获取其top值
			$(window).scroll(function() {
				if ($(this).scrollTop() > divTop) {
					nav.addClass("searchFix");
					
				} else {
					nav.removeClass("searchFix");
				}
			})
	    });
	</script>
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
	</body>
</html>
