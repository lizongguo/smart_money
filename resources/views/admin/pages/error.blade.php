@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layadmin-tips">
        <i class="layui-icon" face>&#xe664;</i>

        <div class="layui-text" style="font-size: 20px;">
            @if($error){{$error}} @else 好像出错了呢 @endif
        </div>

    </div>
</div>
<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
    layui.config({
        uriHost: '{{asset("/admin")}}/', //项目管理端path
        base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index']);
</script>
@endsection
