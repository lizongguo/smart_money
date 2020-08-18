@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-admin" data-type="expand">全部展开</button>
                <button class="layui-btn layuiadmin-btn-admin" data-type="collapse">全部收起</button>
                
                <button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>
                <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加</button>
            </div>
            
            <table class="layui-table layui-form" id="LAY-admin-manage" lay-filter="LAY-admin-manage" lay-size="sm"></table>
            
            <script type="text/html" id="table-admin-opation">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
            </script>
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
}).use(['index', 'form', 'treeTable'], function () {
    var $ = layui.$
    , form = layui.form
    , admin = layui.admin
    , treeTable = layui.treeTable;
    
    var	re = treeTable.render({
        elem: '#LAY-admin-manage',
//        data: {!! json_encode($data) !!},
        url: "{{route('category.items')}}",
        icon_key: 'name',
        parent_key: 'parent_id',
        is_checkbox: true, //true,
        checked: {
            key: 'id',
            data: [],
        },
        end: function(e){
            form.render();
        },
        cols: [
            {
                key: 'id',
                title: 'ID',
                width: '100px',
                align: 'center',
            },
            {
                key: 'parent_id',
                title: '父ID',
                width: '100px',
                align: 'center',
            },
            {
                key: 'name',
                title: '分类名称',
                width: '20%',
                template: function(item){
                    if(item.level == 0){
                        return '<span style="color:red;">'+item.name+'</span>';
                    }else if(item.level == 1){
                        return '<span style="color:green;">'+item.name+'</span>';
                    }else if(item.level == 2){
                        return '<span style="color:#aaa;">'+item.name+'</span>';
                    }
                }
            },
            {
                key: 'state',
                title: '公开状态',
                width: '100px',
                align: 'center',
                template: function(item){
                    if(item.state == 0){
                        return '<span style="color:red;">非公开</span>';
                    }else if(item.state == 1){
                        return '<span style="color:green;">公开</span>';
                    }
                }
            },
            {
                title: '操作',
                align: 'center',
                width: '150px',
                template: function(item){
                    return '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-filter="tools" data-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a> <a class="layui-btn layui-btn-danger layui-btn-xs" lay-filter="tools" data-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>'
                }
            }
        ]
    });

    
    var deleteUri = "{{route('category.delete', ['id' => '_id'])}}";
    var editUri = "{{route('category.input', ['id' => '_id'])}}";
    
    //编辑添加功能
    function editData(id) {
        var title = '添加分类';
        if (typeof id !== 'undefined') {
            var uri = editUri.replace('_id', id);
            title = '编辑分类';
        } else {
            var uri = editUri.replace('/_id', '');
        }
        layer.open({
            type: 2
            , title: title
            , content: uri
            , maxmin: true
            , area: ['500px', '300px']
            , btn: ['确定', '取消']
            , yes: function (index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index]
                        , submitID = 'LAY-input-front-submit'
                        , submit = layero.find('iframe').contents().find('#' + submitID);
//                        iframeWindow.layui.form.render();       
                //监听提交
                iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
                    var field = data.field; //获取提交的字段
                    if (typeof field['data[state]'] == 'undefined') {
                        field['data[state]'] = 0;
                    }
                    //提交 Ajax 成功后，静态更新表格中的数据
                    admin.req({
                        url: uri,
                        data: field,
                        type: 'post',
                        done: function (res) {
                            layer.close(index); //关闭弹层
                            treeTable.render(re);
//                            table.reload('LAY-admin-manage'); //数据刷新
                            //删除成功的提示与跳转
                            layer.msg('编辑成功。', {
                                offset: '15px',
                                icon: 1,
                                time: 1500
                            }, function () {
                                //提示完后需作业的内容
                            });
                        }
                    });
                });
                submit.trigger('click');
            }
            , success: function (layero, index) {

            }
        });
    }
    
    //监听工具条
    treeTable.on('tree(tools)', function (obj) {
        obj.event = $(obj.elem).attr('data-event') ? $(obj.elem).attr('data-event') : null;
        var data = obj.item;
        if (obj.event === 'del') {
            layer.confirm('确认删除当前数据吗？', function (index) {
                layer.close(index);
                //请求登入接口
                admin.req({
                    url: deleteUri.replace('_id', data.id)
                    , done: function (res) {
                        treeTable.render(re);
                        //删除成功的提示与跳转
                        layer.msg('删除成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1500
                        }, function () {});
                    }
                });
            });
        } else if (obj.event === 'edit') {
            editData(data.id);
        }
    });
    
    
    //批量事件
    var active = {
        batchdel: function () {
            var checkData   = treeTable.checked(re) //得到选中的数据
            console.log(checkData);
            if (checkData.length === 0) {
                return layer.msg('请先选择数据~');
            }
            var ids = checkData;
            
            layer.confirm('确定删除选中的数据吗？', function (index) {
                layer.close(index);
                //请求登入接口
                admin.req({
                    url: deleteUri.replace('_id', ids.join())
                    , done: function (res) {
                        //删除成功的提示与跳转
                        layer.msg('批量删除成功。', {
                            offset: '15px'
                            , icon: 1
                            , time: 1500
                        }, function () {});
                        treeTable.render(re);
                    }
                });
            });
        }
        , add: function () {
            editData();
        }
        , collapse: function () {
            treeTable.closeAll(re);
        }
        , expand: function () {
            treeTable.openAll(re);
        }
        , refresh: function () {
            treeTable.render(re);
        }
    };

    $('.layui-btn.layuiadmin-btn-admin').on('click', function () {
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });
});
</script>
@endsection