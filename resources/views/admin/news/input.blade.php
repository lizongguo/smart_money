@extends('layouts.admin')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">文章分类<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <select lay-verify="required"  name="data[category_id]">
                @foreach($categories as $cid => $category)
                <option value="@if($cid > 0){{$cid}}@endif" @if($cid = $data->category_id)) selected @endif>{!! $category !!}</option>
                @endforeach
          </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[title]" value="{{$data->title}}" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">关键字</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[keywords]" value="{{$data->keywords}}" placeholder="请输入关键字" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" name="data[description]" style="height: 60px;"
                      placeholder="请输入文章描述" autocomplete="off" class="layui-textarea">{{$data->description}}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">缩略图<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[thumb]" lay-verify="required" id="LAY_image_src" placeholder="缩略图地址" value="{{$data->thumb}}" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadImage" data-prefix="LAY_image" data-obj="news" id="LAY_image_upload">
                <i class="layui-icon">&#xe67c;</i>上传缩略图
            </button>
            <button class="layui-btn layui-btn-primary" id="LAY_image_show">查看缩略图</button >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">点击量<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <input type="number" name="data[hits]" value="{{$data->hits ?: 0}}" lay-verify="required" class="layui-input" placeholder="点击量" autocomplete="off">
        </div>
    </div>
    <div class="layui-form-item layui-input-inline">
        <label class="layui-form-label">公开状态</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[state]" value="1" lay-skin="switch" lay-text="公开|非公开" @if($data->state == 1) checked @endif>
        </div>
    </div>
    <div class="layui-form-item layui-input-inline">
        <label class="layui-form-label">是否推荐</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="data[recommend]" value="1" lay-skin="switch" lay-text="推荐|不推荐" @if($data->recommend == 1) checked @endif>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">文章内容<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <textarea type="text" name="data[content]" id="layedit_content" style="height: 100px;" lay-verify="required"
                      placeholder="请输入HTTP路径" autocomplete="off" class="layui-textarea">{{$data->content}}</textarea>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="LAY-input-front-submit" id="LAY-input-front-submit" value="确认">
    </div>
</div>

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
        var ieditor = layedit.build('layedit_content', {
            uploadImage: {
                url: "{{route('upload.save', ['object' => 'content'])}}",
                accept: 'image',
                field: 'attachment',
                acceptMime: 'image/*',
                exts: 'jpg|png|gif|bmp|jpeg',
                size: 1024 * 10,
                done: function (data) {
                    console.log(data);
                }
            },
            uploadFiles: {
                url: "{{route('upload.save', ['object' => 'files'])}}",
                accept: 'file',
                field: 'attachment',
                acceptMime: 'file/*',
                size: '20480',
                autoInsert: true, //自动插入编辑器设置
                done: function (data) {
                    console.log(data);
                }
            },
            calldel: {
                url: "{{route('upload.deleteFile')}}"
            }
        });
        
    })
</script>
@endsection