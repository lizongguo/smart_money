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
                            <li class="layui-this">网站设置</li>
                            <li>短信设置</li>
                            <li>微信小程序</li>
                            <li>支付宝小程序</li>
                            <li>推送设定</li>
                            <li>外卖设置</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div class="layui-form"  lay-filter="">
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">网站名称</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="sitename" lay-verify="required" placeholder="请输入网站名称" value="{{config('site.sitename')}}" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">网站域名</label>
                                        <div class="layui-input-block">
                                            <input type="text" name="domain" lay-verify="url" placeholder="请输入网站域名" value="{{config('site.domain', asset('/'))}}" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">前台标题</label>
                                        <div class="layui-input-block">
                                            <textarea name="title" lay-verify="required" placeholder="请输入前台标题" class="layui-textarea">{{config('site.title')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">META关键词</label>
                                        <div class="layui-input-block">
                                            <textarea name="keywords" class="layui-textarea" placeholder="多个关键词用英文状态 , 号分割">{{config('site.keywords')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">META描述</label>
                                        <div class="layui-input-block">
                                            <textarea name="description" class="layui-textarea" placeholder="META描述，方便搜索引擎收录">{{config('site.description')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item layui-form-text">
                                        <label class="layui-form-label">版权信息</label>
                                        <div class="layui-input-block">
                                            <textarea name="copyright"  class="layui-textarea">{{config('site.copyright', '© '.date('Y').' www.kbftech.com MIT license')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_website">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-form"  lay-filter="">
                                    {{csrf_field()}}
                                    <div id='sms_seting'>
                                        <div class="layui-form-item layui-form-text">
                                            <label class="layui-form-label">短信签名<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input name="data[sms_signName]" placeholder="请输入短信签名" value='{{$data['短信配置']['sms_signName']}}' class="layui-input" lay-verify="required">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">AccessKey ID<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[sms_accessKeyId]" value="{{$data['短信配置']['sms_accessKeyId']}}" lay-verify="required" placeholder="请输入AccessKey ID" value="" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">AccessKey Secret<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[sms_accessKeySecret]" placeholder="请输入AccessKey Secret" class="layui-input" lay-verify="required" value='{{$data['短信配置']['sms_accessKeySecret']}}'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_sms">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-form"  lay-filter="">
                                    {{csrf_field()}}
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">微信小程序</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox"  name="data[wx_app_state]" lay-filter='wxapp' value="1" lay-skin="switch" lay-text="已开通|未开通" @if($data['微信小程序']['wx_app_state'] == 1) checked @endif>
                                        </div>
                                    </div>
                                    <div id='wxapp_seting' @if($data['微信小程序']['wx_app_state'] < 1) style="display:none" @endif>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">小程序ID<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[wx_app_id]" value="{{$data['微信小程序']['wx_app_id']}}" @if($data['微信小程序']['wx_app_state'] == 1) lay-verify="required" @endif placeholder="请输入小程序AppID" value="" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item layui-form-text">
                                            <label class="layui-form-label">小程序密钥<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input name="data[wx_app_secret]" placeholder="请输入小程序AppSecret" value="{{$data['微信小程序']['wx_app_secret']}}" class="layui-input" @if($data['微信小程序']['wx_app_state'] == 1) lay-verify="required" @endif>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">商户号ID<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[wx_mch_id]" placeholder="请输入商户号ID" value="{{$data['微信小程序']['wx_mch_id']}}" class="layui-input" @if($data['微信小程序']['wx_app_state'] == 1) lay-verify="required" @endif>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">商户号密钥<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[wx_mch_key]" placeholder="请输入商户号密匙" value="{{$data['微信小程序']['wx_mch_key']}}" class="layui-input" @if($data['微信小程序']['wx_app_state'] == 1) lay-verify="required" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_wxapp">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-form"  lay-filter="">
                                    {{csrf_field()}}
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">支付宝小程序</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox"  name="data[ali_app_state]" lay-filter='aliapp' value="1" lay-skin="switch" lay-text="已开通|未开通" @if($data['支付宝小程序']['ali_app_state'] == 1) checked @endif>
                                        </div>
                                    </div>
                                    <div id='aliapp_seting' @if($data['支付宝小程序']['ali_app_state'] < 1) style="display:none" @endif>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">小程序ID<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[ali_app_id]" value="{{$data['支付宝小程序']['ali_app_id']}}" @if($data['支付宝小程序']['ali_app_state'] == 1) lay-verify="required" @endif placeholder="请输入小程序AppID" value="" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">支付宝公钥<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <textarea type="text" name="data[ali_alipayrsaPublicKey]" placeholder="请输入支付宝公钥" class="layui-textarea" @if($data['微信小程序']['wx_app_state'] == 1) lay-verify="required" @endif>{{$data['支付宝小程序']['ali_alipayrsaPublicKey']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item layui-form-text">
                                            <label class="layui-form-label">小程序密钥<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <textarea name="data[ali_rsaPrivateKey]" placeholder="请输入小程序密钥" class="layui-textarea" @if($data['支付宝小程序']['ali_app_state'] == 1) lay-verify="required" @endif>{{$data['支付宝小程序']['ali_rsaPrivateKey']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_aliapp">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-form"  lay-filter="">
                                    {{csrf_field()}}
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">推送开关</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox"  name="data[push_state]" lay-filter='push' value="1" lay-skin="switch" lay-text="开通|关闭" @if($data['推送']['push_state'] == 1) checked @endif>
                                        </div>
                                        <label style="display: inline-block;padding: 9px 0px;color: #b4b1b1">※推送设置为阿里云推送，请先前往阿里云开通阿里推送后设定</label>
                                    </div>
                                    <div id='push_seting' @if($data['推送']['push_state'] < 1) style="display:none" @endif>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">AccessKey ID<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[push_accessKeyId]" value="{{$data['推送']['push_accessKeyId']}}" @if($data['推送']['push_state'] < 1) lay-verify="required" @endif placeholder="请输入AccessKey ID" value="" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">AccessKey Secret<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[push_accessKeySecret]" placeholder="请输入AccessKey Secret" class="layui-input" @if($data['推送']['push_state'] < 1) lay-verify="required" @endif value='{{$data['推送']['push_accessKeySecret']}}'>
                                            </div>
                                        </div>
                                         <div class="layui-form-item">
                                            <label class="layui-form-label">Android AppKey<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[push_androidAppKey]" value="{{$data['推送']['push_androidAppKey']}}" @if($data['推送']['push_state'] < 1) lay-verify="required" @endif placeholder="请输入Android AppKey" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">IOS AppKey<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[push_iosAppKey]" placeholder="请输入IOS AppKey" class="layui-input" @if($data['推送']['push_state'] < 1) lay-verify="required" @endif value='{{$data['推送']['push_iosAppKey']}}'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_push">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-tab-item">
                                <div class="layui-form"  lay-filter="">
                                    {{csrf_field()}}
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">配送费<font style="color: red">*</font></label>
                                        <div class="layui-input-block" style="width: 10%">
                                            <input type="text" name="data[takeout_price]" lay-verify="number" value="{{$data['外卖配置']['takeout_price']}}" placeholder="请输入配送费" class="layui-input number">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">餐盒费<font style="color: red">*</font></label>
                                        <div class="layui-input-block" style="width: 10%">
                                            <input type="text" name="data[takeout_tableware_price]" lay-verify="number" value="{{$data['外卖配置']['takeout_tableware_price']}}" placeholder="请输入餐盒费" class="layui-input number">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">配送距离（公里）<font style="color: red">*</font></label>
                                        <div class="layui-input-block" style="width: 10%">
                                            <input type="text" name="data[takeout_distance]" lay-verify="number" value="{{$data['外卖配置']['takeout_distance']}}" placeholder="请输入配送距离" class="layui-input number">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <label class="layui-form-label">第三方配送</label>
                                        <div class="layui-input-block">
                                            <input type="checkbox"  name="data[other_service_state]" lay-filter='takeout' value="1" lay-skin="switch" lay-text="开通|关闭" @if($data['外卖配置']['other_service_state'] == 1) checked @endif>
                                        </div>
                                    </div>
                                    <div id='takeout_seting' @if($data['外卖配置']['other_service_state'] < 1) style="display:none" @endif>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">配送公司<font style="color: red">*</font></label>
                                            <div class="layui-input-block" style="width: 10%">
                                                <select lay-filter='shopSelect'  @if($data['外卖配置']['other_service_state'] > 0) lay-verify="required" @endif name="data[takeout_company]" lay-search>
                                                    <option value="">请选择配送公司</option>
                                                    @foreach(config('code.takeout_company') as $key => $company)
                                                    <option value="{{$key}}" @if($key = $data['外卖配置']['takeout_company'])) selected @endif>{{$company}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         <div class="layui-form-item sourceID">
                                             <label class="layui-form-label"><span  class="AppSecretName">配送公司@if($data['外卖配置']['takeout_company'] == 'dada')sourceId @else{{'access_token'}}@endif</span><font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[takeout_sourceId]" placeholder="请输入@if($data['外卖配置']['takeout_company'] == 'dada')sourceId @else{{'access_token'}}@endif" value="{{$data['外卖配置']['takeout_sourceId']}}" @if($data['外卖配置']['other_service_state'] > 0) lay-verify="required" @endif class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">配送公司AppKey<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[takeout_appKey]" placeholder="请输入AppKey" value="{{$data['外卖配置']['takeout_appKey']}}" @if($data['外卖配置']['other_service_state'] > 0) lay-verify="required" @endif class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">配送公司AppSecret<font style="color: red">*</font></label>
                                            <div class="layui-input-block">
                                                <input type="text" name="data[takeout_appSecret]" placeholder="请输入AppSecret" value='{{$data['外卖配置']['takeout_appSecret']}}' class="layui-input" @if($data['外卖配置']['other_service_state'] > 0) lay-verify="required" @endif >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-input-block">
                                            <button class="layui-btn" lay-submit lay-filter="set_takeout">确认保存</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
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
    
    form.on('select(shopSelect)', function(data){
        var val = data.value;
        console.log(val);
        if (val=='dianwoda') {
            $('.sourceID .AppSecretName').html('配送公司access_token');
            $('.sourceID input').attr('placeholder', '请输入access_token');
        }else {
            $('.sourceID .AppSecretName').html('配送公司sourceId');
            $('.AppSecret input').attr('placeholder', '请输入sourceId');
        }
        
    });
    
    
    
    
    
    
});
</script>
@endsection