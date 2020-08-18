@extends('layouts.admin')
@section('content')

<style type="text/css">
.layui-table-cell {
    height: 40px;
    line-height: 20px;
}
.layui-form-switch{
    margin-top: -5px;
}
</style>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">开始日期</label>
                    <div class="layui-input-block">
                        <input type="text" name="sh[start_date]" id="start"  readonly="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束日期</label>
                    <div class="layui-input-block">
                        <input type="text" name="sh[end_date]" id="end"  readonly="" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">店铺</label>
                    <div class="layui-input-block">
                        <select name="sh[shop_id]" lay-search>
                            <option value="">请选择店铺</option>
                            @foreach($shops as $shop)
                            <option value="{{$shop->id}}" @if($shop->id == $data->shop_id) selected @endif>{{$shop->shop_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">外卖订单</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="sh[is_takeout]" value="1" lay-skin="switch" lay-text="是|否">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">餐号</label>
                    <div class="layui-input-block">
                        <input type="number" name="sh[meal_no]" placeholder="请输入取餐号"  autocomplete="off" class="layui-input">
                    </div>
                </div>
                
                <div class="layui-inline">
                    <label class="layui-form-label">支付方式</label>
                    <div class="layui-input-block">
                        <select name="sh[pay_type]">
                            <option value="">请选择</option>
                            @foreach($code['pay_type'] as $key => $val)
                            <option value="{{$key}}" >{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">订单类型</label>
                    <div class="layui-input-block">
                        <select name="sh[order_type]">
                            <option value="">请选择</option>
                            @foreach($code['order_type'] as $key => $val)
                            <option value="{{$key}}" >{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">订单状态</label>
                    <div class="layui-input-block">
                        <select name="sh[state]">
                            <option value="">请选择</option>
                            @foreach($code['order_state'] as $key => $val)
                            <option value="{{$key}}" >{{$val}}</option>
                            @endforeach
                        </select>
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
            <table id="LAY-admin-manage" lay-filter="LAY-admin-manage"></table>
            <script type="text/html" id="shopTpl">
                @{{#  if(d.shops){ }}
                @{{  d.shops.join() }}
                @{{#  } }}
            </script>
            <script type="text/html" id="takeoutTpl">
                @{{#  if(d.is_takeout == 1){ }}
                <button class="layui-btn layui-btn-xs">是</button>
                @{{#  } else { }}
                <button class="layui-btn layui-btn-disabled layui-btn-xs">否</button>
                @{{#  } }}
            </script>
            <script type="text/html" id="table-admin-opation">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="info"><i class="fa fa-check-square-o"></i> 详细</a>
                @{{#  if(d.order_type == 2 && d.state == 1 ){ }}
                <!--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="state"><i class="fa fa-ban"></i> 已付款</a>-->
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
}).use(['index', 'form', 'table', 'laydate'], function () {
    var $ = layui.$
            , form = layui.form
            , admin = layui.admin
            , laydate = layui.laydate
            , table = layui.table;
    laydate.render({
        elem: '#start' //指定元素
        ,type: 'date'
        ,trigger: 'click'
    });
    laydate.render({
        elem: '#end' //指定元素
        ,type: 'date'
        ,trigger: 'click'
    });
    //用户管理
    table.render({
        elem: '#LAY-admin-manage'
        , url: '{{route("orders.items")}}' //模拟接口
        , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', width: 60, title: 'ID', sort: true}
                , {field: 'order_no', width: 180,  title: '订单号'}
                , {field: 'meal_no', title: '取餐号', width: 100}
                , {field: 'shops', title: '店铺', width: 200, templet: '#shopTpl'}
                , {field: 'orderType', title: '类型', width: 100}
                , {field: 'payType', title: '分类', width: 80}
                , {field: 'is_takeout', title: '外卖', width: 60, templet: '#takeoutTpl'}
                , {field: 'total_amount', title: '总额', width: 100, sort: true}
                , {field: 'preferential_amount', title: '抵扣额', width: 100, sort: true}
                , {field: 'payment_amount', title: '支付额', width: 100, sort: true}
                , {field: 'stateStr', title: '状态', width: 100}
                , {field: 'completion_time', title: '完成时间', width: 150}
                , {field: 'created_at', title: '创建时间', width: 150}
                , {title: '操作', width: 160, align: 'center', fixed: 'right', toolbar: '#table-admin-opation'}
            ]]
        , page: true
        , limit: 30
        , height: 'full-220'
        , text: {
            none: '暂未查询到相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
        }
    });
    var deleteUri = "{{route('orders.delete', ['id' => '_id'])}}";
    var editUri = "{{route('orders.input', ['id' => '_id'])}}";
    var stateUri = "{{route('orders.state', ['id' => '_id'])}}";
    var detailUri = "{{route('orders.detail', ['id' => '_id'])}}";
    
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
        var title = '添加订单';
        if (typeof id !== 'undefined') {
            var uri = editUri.replace('_id', id);
            title = '编辑订单';
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
                    if (typeof field['data[state]'] == 'undefined') {
                        field['data[state]'] = 0;
                    }
                    
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
            return ;
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
            return ;
            var tr = $(obj.tr);
            editData(obj.data.id);
        }  else if(obj.event == 'state') {
            layer.confirm('确认修改该数据状态吗？', function (index) {
                layer.close(index);
                //请求登入接口
                admin.req({
                    url: stateUri.replace('_id', data.id)
//                    ,type: 'post'
                    , done: function (res) {
                        //删除成功的提示与跳转
                        table.reload('LAY-admin-manage'); //数据刷新
                        layer.msg('修改状态成功', {
                            offset: '15px'
                            , icon: 1
                            , time: 1500
                        }, function () {});
                    }
                });
            });
        }else if(obj.event == 'info') {
            var uri = detailUri.replace('_id', data.id);
            var title = '订单详情';
            var layerIndex1 = layer.open({
                type: 2
                , title: title
                , content: uri
                , maxmin: true
                , area: ['500px', '450px']
                , btn: ['确认']
                , yes: function (index, layero) {
                    layer.close(index); //关闭弹层
                }
                , success: function (layero, index) {

                }
            });
            layer.full(layerIndex1);
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
                checkData   = checkStatus.data; //得到选中的数据

            if (checkData.length === 0) {
                return layer.msg('请先选择数据~');
            }
            var ids = [];
            checkData.forEach(function(item){
                ids.push(item.id);
            });
            
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