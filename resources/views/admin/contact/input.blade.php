@extends('layouts.admin')
@section('content')
    <style>

        .table-striped tbody > tr:nth-child(2n+1) > td{
            background-color: #f9f9f9;
        }

    </style>
<table class="layui-table table-striped">
    <tbody>
        <tr>
            <td  width="160">ID</td>
            <td>{{ $data->contact_id }}</td>
        </tr>
        <tr>
            <td>名前</td>
            <td>{{ $data->name }}</td>
        </tr>
        <tr>
            <td>メールアドレス</td>
            <td>{{ $data->email }}</td>
        </tr>
        <tr>
            <td>電話番号</td>
            <td>{{ $data->cell_phone }}</td>
        </tr>
        <tr>
            <td>お申し込みプラン</td>
            <td>{{ $data->plan }}</td>
        </tr>
        <tr>
            <td>国籍</td>
            <td>{{ $data->nationality }}</td>
        </tr>
        <tr>
            <td>現在のお住まい</td>
            <td>{{ $data->address }}</td>
        </tr>
        <tr>
            <td>日本語レベル</td>
            <td>{{ $data->jp_level }}</td>
        </tr>
        <tr>
            <td>自己PR</td>
            <td style="white-space: pre-line;">{{$data->message}}</td>
        </tr>

    </tbody>
</table>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script src="{{asset("/layuiadmin/style/Content/ace/ace.js")}}"></script>
    <script>
    layui.config({
        uriHost: '{{asset("/admin")}}/', //项目管理端path
        base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
    }).extend({
        index: 'lib/index', //主入口模块
        formSelects: 'formSelects-v4.min' //主入口模块
    }).use(['layedit', 'index', 'form', 'upload', 'edit'], function () {
        var $ = layui.$,
        admin = layui.admin,
        layedit = layui.layedit,
        upload = layui.upload,
        form = layui.form;
        
    })
</script>
@endsection