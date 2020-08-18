@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn tree-menu-tree-tools" data-action="expand" data-type="expand">展开全部</button>
                <button class="layui-btn tree-menu-tree-tools" data-action="collapse" data-type="collapse">收起全部</button>
                <button class="layui-btn tree-menu-save" data-type="save">保存顺序</button>
                <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加菜单</button>
            </div>
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <div class="dd" id="tree-menu">
                        @include('admin.menu.tree', ['d' => $data, 'pid' => 0])
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/jquery.nestable.css')}}"/>
<script>
function LA() {}
LA._token = "{{csrf_token()}}";

layui.config({
    uriHost: '{{asset("/admin")}}/', //项目管理端path
    base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
    , nestable: 'nestable' //主入口模块
}).use(['index', 'form', 'table', 'jquery', 'nestable'], function () {
    var $ = layui.$
            , form = layui.form
            , admin = layui.admin
            , nestable = layui.nestable
            , table = layui.table;

    $('#tree-menu').nestable([]);
    $('.tree-menu-tree-tools').on('click', function (e) {
        var action = $(this).data('action');
        if (action === 'expand') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse') {
            $('.dd').nestable('collapseAll');
        }
    });
    $('.tree-menu-save').click(function () {
        var serialize = $('#tree-menu').nestable('serialize');
        admin.req({
            url: "{{route('menu.saveOrder')}}",
            data: {_token:LA._token, order: JSON.stringify(serialize)},
            type: 'post',
            done: function (res) {
                //删除成功的提示与跳转
                layer.msg('顺序保存成功。',{offset: '15px', icon: 1,time: 1000},function () {
                    //提示完后需作业的内容 刷新当前内容
                });
            }
        });
    });


    var deleteUri = "{{route('menu.delete', ['id' => '_id'])}}";
    var editUri = "{{route('menu.input', ['id' => '_id'])}}";


    //编辑添加功能
    function editData(id) {
        var title = '添加菜单';
        if (typeof id !== 'undefined') {
            var uri = editUri.replace('_id', id);
            title = '编辑菜单';
        } else {
            var uri = editUri.replace('/_id', '');
        }
        var layerIndex = layer.open({
            type: 2
            , title: title
            , content: uri
            , maxmin: true
            , area: ['500px', '450px']
            , btn: ['确定', '取消']
            , yes: function (index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index]
                        , submitID = 'LAY-input-front-submit'
                        , submit = layero.find('iframe').contents().find('#' + submitID);
//                        iframeWindow.layui.form.render();       
                //监听提交
                iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
                    var field = data.field; //获取提交的字段
                    console.log(field)
                    //提交 Ajax 成功后，静态更新表格中的数据
                    //请求登入接口
                    admin.req({
                        url: uri,
                        data: field,
                        type: 'post',
                        done: function (res) {
                            layer.close(index); //关闭弹层
                            //删除成功的提示与跳转
                            layer.msg('编辑成功。', {
                                offset: '15px',
                                icon: 1,
                                time: 1500
                            }, function () {
                                //提示完后需作业的内容 刷新当前内容
                                location.reload();
                            });
                        }
                    });
                });
                submit.trigger('click');
            }
            , success: function (layero, index) {

            }
        });
        layer.full(layerIndex);
    }
    
    //事件
    var active = {
        del: function () {
            var id = $(this).data('id');
            if (typeof id == 'undefined') {
                return ;
            }
            layer.confirm('确认删除菜单吗？', function (index) {
                layer.close(index);
                //请求登入接口
                admin.req({
                    url: deleteUri.replace('_id', id)
                    , done: function (res) {
                        //删除成功的提示与跳转
                        layer.msg('删除成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1500
                        }, function () {
                            location.reload();
                        });
                    }
                });
            });
        }
        , edit: function(){
            var id = $(this).data('id');
            if (typeof id == 'undefined') {
                return ;
            }
            editData(id);
        }
        , add: function () {
            editData();
        }
    };
    
    $('.layuiadmin-btn-admin').on('click', function () {
        var type = $(this).data('type');
        if (!active[type]) {
            return;
        }
        active[type].call(this)
    });
});
</script>
@endsection