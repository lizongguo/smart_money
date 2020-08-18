@extends('layouts.admincontent')
@section('content')
<style>
    .layui-form-label{
        width: 200px;
    }
    .layui-input-block{
        margin-left:230px;
        width: 70%;
    }
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <!--<div class="layui-card-header">网站设置</div>-->
                <div class="layui-card-body" pad15>
                    <div class="layui-tab">
                        <ul class="layui-tab-title">
                            <li class="layui-this">订单信息</li>
                            <li>商品信息</li>
                            @if($data->is_takeout == 1) 
                            <li>外卖信息</li>
                            @endif
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div class="layui-card-header">订单详细</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col>
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>ID</th>
                                          <th>订单号</th>
                                          <th>取餐号</th>
                                          <th>店铺</th>
                                          <th>商品数量</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>{{$data->id}}</td>
                                          <td>{{$data->order_no}}</td>
                                          <td>{{$data->meal_no}}</td>
                                          <td>{{$data->shops->shop_name}}</td>
                                          <td>{{$data->goods_num}}</td>
                                        </tr>
                                        <tr class="layui-table-click">
                                          <th>类型</th>
                                          <th>分类</th>
                                          <th>外卖订单</th>
                                          <th>支付状态</th>
                                          <th>下单时间</th>
                                        </tr>
                                        <tr>
                                          <td>{{$code['order_type'][$data->order_type]}}</td>
                                          <td>{{$code['pay_type'][$data->pay_type]}}</td>
                                          <td>@if($data->is_takeout == 1) 是 @else 否 @endif</td>
                                          <td>@if($data->pay_state == 1) 已支付 @else 待支付 @endif</td>
                                          <td>{{$data->created_at}}</td>
                                        </tr>
                                        <tr class="layui-table-click">
                                          <th>总金额</th>
                                          <th>优惠金额</th>
                                          <th>支付金额</th>
                                          <th>备注</th>
                                          <th>完成时间</th>
                                        </tr>
                                        <tr>
                                          <td>{{$data->total_amount}}元</td>
                                          <td>{{$data->preferential_amount}}元</td>
                                          <td>{{$data->payment_amount}}元</td>
                                          <td>{{$data->memo}}</td>
                                          <td>{{$data->completion_time}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                @if($data->activity)
                                <div class="layui-card-header">优惠详细</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="30">
                                        <col width="200">
                                        <col width="100">
                                        <col width="120">
                                        <col width="120">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>#ID</th>
                                          <th>活动名</th>
                                          <th>活动类型</th>
                                          <th>优惠</th>
                                          <th>活动金额</th>
                                          <th>抵扣金额</th>
                                          <th>参与时间</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>{{$data->activity->id}}</td>
                                          <td>{{$data->activity->activity->activity_name}}</td>
                                          <td>{{$code['activity_type'][$data->activity->activity_type]}}</td>
                                          <td>
                                              @if($data->activity->activity_type == 1)
                                              满{{$data->activity->full_amount}}减{{$data->activity->minus_amount}} 
                                              @else
                                              {{$data->activity->discount}}折
                                              @endif
                                          </td>
                                          <td>{{$data->activity->activity_amount}}元</td>
                                          <td>{{$data->activity->discount_amount}}元</td>
                                          <td>{{$data->activity->created_at}}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-card-header">商品详情</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="80">
                                        <col width="150">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                        <col width="100">
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>商品ID</th>
                                          <th>商品名</th>
                                          <th>商品图片</th>
                                          <th>规格</th>
                                          <th>单价</th>
                                          <th>购买数</th>
                                          <th>退菜数</th>
                                          <th>状态</th>
                                          <th>购买时间</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($data->goods as $goods)
                                        <tr>
                                          <td>{{$goods->goods_id}}</td>
                                          <td>{{$goods->goods_name}}</td>
                                          <td><img src="{{asset($goods->img)}}" width="50" height="50"></td>
                                          <td>{{$goods->spec_str}}</td>
                                          <td>{{$goods->sell_price}}元/份</td>
                                          <td>{{$goods->goods_num}}</td>
                                          <td>{{$goods->return_num}}</td>
                                          <td>@if($goods->state > 0) 已上菜 @else 待上菜 @endif</td>
                                          <td>{{$goods->created_at}}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($data->is_takeout == 1) 
                            <div class="layui-tab-item">
                                <div class="layui-card-header">外卖情报</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                        <col width="150">
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>配送类别</th>
                                          <th>配送方式</th>
                                          <th>收货人</th>
                                          <th>收货电话</th>
                                          <th>状态</th>
                                          <th>时间</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td>{{$code['takeout_type'][$data->takeout->takeout_type]}}</td>
                                          <td>{{$code['takeout_cate'][$data->takeout->take_cate]}}</td>
                                          <td>{{$data->takeout->accept_name}}</td>
                                          <td>{{$data->takeout->phone}}</td>
                                          <td>{{$code['takout_state'][$data->takeout->takeout_state]}}</td>
                                          <td>{{$data->takeout->delivery_time}}</td>
                                        </tr>
                                        @if($data->takeout->takeout_type == 1)
                                        <tr class="layui-table-click">
                                          <th>配送员</th>
                                          <th>配送员电话</th>
                                          <th>配送费</th>
                                          <th>餐盒费</th>
                                          <th colspan="2">配送地址</th>
                                        </tr>
                                        <tr>
                                          <td>{{$data->takeout->express_name}}</td>
                                          <td>{{$data->takeout->express_phone}}</td>
                                          <td>{{$data->takeout->takeout_amount}}元</td>
                                          <td>{{$data->takeout->tableware_amount}}元</td>
                                          <td colspan="2">{{$data->takeout->delivery_address}}</td>
                                        </tr>
                                        @endif
                                      </tbody>
                                    </table>
                                </div>
                                <div class="layui-card-header">配送履历</div>
                                <div class="layui-card-body">
                                    <table class="layui-table">
                                      <colgroup>
                                        <col width="120">
                                        <col width="300">
                                        <col width="100">
                                        <col width="120">
                                      </colgroup>
                                      <thead>
                                        <tr>
                                          <th>操作者</th>
                                          <th>描述</th>
                                          <th>状态</th>
                                          <th>操作时间</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($data->takeoutInfo as $info)
                                        <tr>
                                          <td>{{$info->operator}}</td>
                                          <td>{{$info->message}}</td>
                                          <td>{{$code['takout_state'][$info->state]}}</td>
                                          <td>{{$info->created_at}}</td>
                                        </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
function LA() {}
LA._token = "{{csrf_token()}}";
layui.config({
    uriHost: '{{asset("/admin")}}/', //项目管理端path
    base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
}).use(['index', 'form', 'upload', 'set'], function () {
    var $ = layui.$
            , setter = layui.setter
            , admin = layui.admin
            , form = layui.form
            , upload = layui.upload;
    //网站设置
    form.on('submit(set_website)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        admin.req({
            url: '{{route("setting.site")}}'
            , data: {'_token': LA._token, data: obj.field}
            , type: 'post'
            , success: function (res) {

            }
            , done: function (res) {
                //登入成功的提示与跳转
                layer.msg('内容保存成功', {
                    offset: '15px'
                    , icon: 1
                    , time: 1000
                }, function () {
                    location.reload();
                });
            }
        });
    });
    
    var saveUri = '{{route("setting.save", ["category" => "_cate_"])}}';
    
    function saveData(field, cate) {
        admin.req({
            url: saveUri.replace('_cate_', cate)
            , data: field
            , type: 'post'
            , success: function (res) {

            }
            , done: function (res) {
                //登入成功的提示与跳转
                layer.msg('内容保存成功', {
                    offset: '15px'
                    , icon: 1
                    , time: 1000
                }, function () {
                    location.reload();
                });
            }
        });
    }
    
    //微信小程序设置
    form.on('submit(set_wxapp)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'wxapp';
        saveData(obj.field, cate);
    });
    form.on('switch(wxapp)', function(data){
        if (data.elem.checked) {
            $('#wxapp_seting').show();
            $('#wxapp_seting input').attr('lay-verify', 'required');
        } else {
            $('#wxapp_seting').hide();
            $('#wxapp_seting input').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //支付宝小程序设置
    form.on('submit(set_aliapp)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'aliapp';
        saveData(obj.field, cate);
    });
    form.on('switch(aliapp)', function(data){
        if (data.elem.checked) {
            $('#aliapp_seting').show();
            $('#aliapp_seting input,#aliapp_seting textarea').attr('lay-verify', 'required');
        } else {
            $('#aliapp_seting').hide();
            $('#aliapp_seting input,#aliapp_seting textarea').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //阿里大于短信设置
    form.on('submit(set_sms)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'sms';
        saveData(obj.field, cate);
    });
    //阿里推送设置
    form.on('submit(set_push)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'push';
        saveData(obj.field, cate);
    });
    form.on('switch(push)', function(data){
        if (data.elem.checked) {
            $('#push_seting').show();
            $('#push_seting input,#aliapp_seting textarea').attr('lay-verify', 'required');
        } else {
            $('#push_seting').hide();
            $('#push_seting input,#aliapp_seting textarea').removeAttr('lay-verify');
        }
        form.render();
    });
    
    //阿里推送设置
    form.on('submit(set_takeout)', function (obj) {
//      layer.msg(JSON.stringify(obj.field));
        //提交设置保存
        var cate = 'takeout';
        saveData(obj.field, cate);
    });
    form.on('switch(takeout)', function(data){
        if (data.elem.checked) {
            $('#takeout_seting').show();
            $('#takeout_seting input,#aliapp_seting select').attr('lay-verify', 'required');
        } else {
            $('#takeout_seting').hide();
            $('#takeout_seting input,#aliapp_seting select').removeAttr('lay-verify');
        }
        form.render();
    });
    
    
    
    
});
</script>
@endsection