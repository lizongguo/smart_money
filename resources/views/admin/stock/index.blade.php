@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">股票名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="sh[name]" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">股票代码</label>
                    <div class="layui-input-block">
                        <input type="text" name="sh[code]" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-search" lay-submit lay-filter="LAY-admin-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>
                <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加</button>
            </div>
            <table id="LAY-admin-manage" lay-filter="LAY-admin-manage"></table>
            <script type="text/html" id="roleNameTpl">
                @{{ d.roles.name }}
            </script>
            <script type="text/html" id="table-admin-opation">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                @{{#  if(d.role == 1){ }}
                <a class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon layui-icon-delete"></i>删除</a>
                @{{#  } else { }}
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                @{{#  } }}
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
    }).use(['index', 'form', 'table'], function () {
        var $ = layui.$
                , form = layui.form
                , admin = layui.admin
                , table = layui.table;
        //用户管理
        table.render({
            elem: '#LAY-admin-manage'
            , url: '{{route("stock.items")}}' //模拟接口
            , cols: [[
                    {type: 'checkbox', fixed: 'left',width:'5%'}
                    , {field: 'id', width: '20%', title: '序号'}
                    , {field: 'name', width:'30%', title: '股票名称'}
                    , {field: 'code',width:'30%', title: '股票代码'}
                    , {title: '操作', width: '15%', align: 'center', fixed: 'right', toolbar: '#table-admin-opation'}
                ]]
            , page: true
            , limit: 30
            , height: '400px'
            , text: {
                none: '暂未查询到相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
            }
            ,done: function (res, curr, count) {
                for (var i in res.data) {
                    var item = res.data[i];
                    if (item.role == 1) {
                        $('tr[data-index=' + i + '] input[type="checkbox"]').prop('disabled', true);
                        $('tr[data-index=' + i + '] input[type="checkbox"]').removeAttr('name');
                        form.render();// 重新渲染一下
                    }
                }
            }
        });
        var deleteUri = "{{route('stock.delete', ['id' => '_id'])}}";
        var editUri = "{{route('stock.input', ['id' => '_id'])}}";

        //排序功能
        table.on('sort(LAY-admin-manage)', function (obj) {
            //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"               
            //尽管我们的 table 自带排序功能，但并没有请求服务端。
            //有些时候，你可能需要根据当前排序的字段，重新向服务端发送请求，从而实现服务端排序，如：
            table.reload('LAY-admin-manage', {//testTable是表格容器id
                initSort: obj //记录初始排序，如果不设的话，将无法标记表头的排序状态。 layui 2.1.1 新增参数
                , where: {//请求参数（注意：这里面的参数可任意定义，并非下面固定的格式）
                    sh: {
                        order: {
                            field: obj.field, //排序字段
                            sort: obj.type, //排序方式
                        }
                    }
                }
            });
        });
        //编辑添加功能
        function editData(id) {
            var title = '添加股票';
            if (typeof id !== 'undefined') {
                var uri = editUri.replace('_id', id);
                title = '编辑股票';
            } else {
                var uri = editUri.replace('/_id', '');
            }
            var layerIndex = layer.open({
                type: 2
                , title: title
                , content: [uri, 'yes']
                , maxmin: true
                , scrollbar: false
                , area: ['600px', '610px']
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
                                table.reload('LAY-admin-manage'); //数据刷新
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
            layer.full(layerIndex);
        }

        //监听工具条
        table.on('tool(LAY-admin-manage)', function (obj) {
            var data = obj.data;
            if (obj.event === 'del') {
                layer.confirm('确认删除当前数据吗？', function (index) {
                    layer.close(index);
                    //请求登入接口
                    admin.req({
                        url: deleteUri.replace('_id', data.id)
                        , done: function (res) {
                            obj.del();
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
                var tr = $(obj.tr);
                editData(obj.data.id);
            }
        });

        //监听搜索
        form.on('submit(LAY-admin-search)', function (data) {
            var field = data.field;
            console.log(field);
            //执行重载
            table.reload('LAY-admin-manage', {
                where: field
            });

        });

        //批量事件
        var active = {
            batchdel: function () {
                var checkStatus = table.checkStatus('LAY-admin-manage'),
                        checkData = checkStatus.data; //得到选中的数据
                console.log(checkData);
                var ids = [];
                checkData.forEach(function(item){
                    if (item.role != 1) {
                        ids.push(item.id);
                    }
                });
                if (ids.length === 0) {
                    return layer.msg('请先选择数据~');
                }

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
                            table.reload('LAY-admin-manage');
                        }
                    });
                });
            }
            , add: function () {
                editData();
            }
        };

        $('.layui-btn.layuiadmin-btn-admin').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    });
    </script>
    @endsection