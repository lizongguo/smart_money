<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>{{config('site.sitename', '内容')}}</title>
  <meta name="keywords" content="{{config('site.keywords')}}" />
  <meta name="description" content="{{config('site.description')}}" />
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" type="text/css" href="{{asset('/screen/css/index.css')}}?v=1.0" />
</head>
<body class="layui-layout-body">
  @yield('content')
</body>
</html>


