<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=0,shrink-to-fit=no">
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<title>{{$currentTitle}} {{$code['site_title']}}</title>
		<link rel="shortcut icon" href="{{asset('/static/img/favicon.ico')}}">
		<!-- Bootstrap -->
		
		<!-- 通用样式 -->
		<link rel="stylesheet" href="{{asset('/static/lib/bootstrap/css/bootstrap.min.css')}}">
		<link rel="stylesheet" href="{{asset('/static/css/content.css')}}?v=1.1">
        <link rel="stylesheet" href="{{asset('/static/css/style.css')}}">
		
		<!--[if lt IE 9]>
        <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="{{asset('/static/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/layer/layer.js')}}" type="text/javascript"></script>
		<script src="{{asset('/static/lib/bootstrap/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript">var access_token = '{{$user["access_token"]}}';</script>
        <script src="{{asset('/static/js/model/common.js')}}?v=20181121"></script>
        <script src="{{asset('/static/js/vue.min.js')}}"></script>
	</head>
	<body>
        <!-- 顶部导航 -->
        @include('web.common.header')
        <!-- 轮播图 -->
        @if($banner)
        @include('web.common.banner')
        @endif
		
        @yield('content')
        @if($mypage)
        @include('web.common.roleApply')
        @endif
		<!--footer-->
        @include('web.common.footer')
		<script>
			$(function() {
				// 展会讯息
				var resize = function() {
					var h = ($('#card_demo').height() - 56) / 3;
					$('.small-card').height(h);
					$('.small-card img').height(h);
				};
				var func = function() {
					var wh = $(window).width();
					if (wh >= 768) resize();
				};
				func();
				$(window).on('resize', func);
                @if($errorSession)layer.msg('{{$errorSession}}', {icon: 5});@endif
			});
		</script>
	</body>
</html>