<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=0,shrink-to-fit=no">
		<meta name="description" content="{{$item->description}}" />
		<meta name="keywords" content="{{$item->keywords}}" />
		<meta name="author" content="空白符科技" />
		<title>{{$item->title}}</title>
		<link rel="shortcut icon" href="./favicon.ico">
		<link rel="stylesheet" type="text/css" href="{{asset('/news/css/index.css')}}" />
		<script src="{{asset('/news/js/flexible.js')}}"></script>
	</head>
	<body>
		<div id="app">
			<div class="text-page">
				<h3 class="text-single-center"><span>{{$item->title}}</span></h3>
				<div class="time">
					<span>{{$item->created_at}}</span>
				</div>
				{!! $item->content !!}
			</div>
		</div>
	</body>
</html>