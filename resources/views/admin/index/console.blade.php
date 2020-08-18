@extends('layouts.admincontent')
@section('content')
<div class="layui-fluid">
    welcome
</div>
<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
    layui.config({
        uriHost: '{{asset("/admin")}}/', //项目管理端path
        base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'console']);
</script>
@endsection