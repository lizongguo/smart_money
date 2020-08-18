<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=0,shrink-to-fit=no">
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<title>洋富柜儿-桌位印刷-{{$data['shop_id']}}</title>
		<link rel="shortcut icon" href="/static/img/favicon.ico">
        <link href="{{asset('/static/css/mint-ui.css')}}" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="{{asset('/static/css/index.css')}}?v={{time()}}" />
        <script>
            var access_token = '{{$user["access_token"]}}';
        </script>
	</head>
	<body>
        <div id="app" class="{{$baseClass}}">
        @yield('content')
        </div>
    </body>
</html>
