@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('/layuiadmin/style/formSelects-v4.css')}}"/>
<div class="layui-form" lay-filter="layuiadmin-form-admin" id="layuiadmin-form-admin" style="padding: 20px 0 0 0;">
    {{csrf_field()}}
    <div class="layui-form-item layui-hide">
        <input type="hidden" name="data[id]" value="{{$data->id}}" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">菜品名称<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%" >
            <input type="text" name="data[goods_name]" value="{{$data->goods_name}}" lay-verify="required|max:20" placeholder="请输入菜品名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">展示图<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 50%">
            <input name="data[img]" lay-verify="required" id="LAY_image_src" placeholder="缩略图地址" value="@if($data->img){{ $data->img}}@endif" class="layui-input">
        </div>
        <div class="layui-input-block layui-btn-container" style="width: auto;">
            <button type="button" class="layui-btn layui-btn-primary uploadImage" data-prefix="LAY_image" data-obj="goods" id="LAY_image_upload">
                <i class="layui-icon">&#xe67c;</i>上传图片
            </button>
            <button class="layui-btn layui-btn-primary" id="LAY_image_show">查看</button >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所属店铺<font style="color: red">*</font></label>
        <div class="layui-input-inline" style="width: 70%">
            <select xm-select="roles" xm-select-direction="down" xm-select-search xm-select-height="150px" xm-select-show-count="6" lay-verify="required" name="data[shop_ids]" lay-search>
                <option value="">请选择店铺</option>
                @foreach($shops as $shop)
                <option value="{{$shop->id}}" @if(in_array($shop->id,$data->shop_id)) selected @endif>{{$shop->shop_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">分类<font style="color: red">*</font></label>
        <div class="layui-input-inline">
            <select lay-verify="required" name="data[category_id]" id="category">
                <option value="">请选择分类</option>
                @foreach($categories as $category)
                <option class="shop_{{$category->shop_id}} shop" value="{{$category->id}}" @if($category->id == $data->category_id) selected @endif>{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">多规格</label>
            <div class="layui-input-inline">
                <input type="checkbox" lay-filter="multiple_spec" name="data[is_multiple_spec]" value="1" lay-skin="switch" lay-text="多规格|否" @if($data->is_multiple_spec == 1) checked @endif>
            </div>
        </div>
        <div class="layui-inline" id='multiple_spec_no' @if($data->is_multiple_spec == 1) style="display:none" @endif>
            <label class="layui-form-label">售价<font style="color: red">*</font></label>
            <div class="layui-input-inline">
                <input type="text" name="data[sell_price]" @if($data->is_multiple_spec != 1) lay-verify="number" @endif value="{{$data->sell_price}}" placeholder="输入单价" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>
    <div class="layui-form-item" id='multiple_spec_yes' @if($data->is_multiple_spec != 1) style="display:none" @endif>
        <label class="layui-form-label"></label>
        <div class="layui-input-inline" style="width: 70%">
            <table class="layui-table">
                <colgroup>
                    <col width="250">
                    <col width="250">
                    <col width="50">
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th>规格</th>
                        <th>售价</th>
                        <th><button class="layui-btn layui-btn-primary layui-btn-sm" id="spec-add" title="添加"><i class="layui-icon layui-icon-add-circle-fine"></i></button></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @forelse($data->goods_specs as $i => $item)
                    <tr>
                        <td><input type="hidden"  name="data[products][{{$i}}][id]" value='{{$item->id}}'>
                            <input type="text" placeholder="例：大、中、小等" class="layui-input text" size="5" name="data[products][{{$i}}][spec_str]" value='{{$item->spec_str}}' @if($data->is_multiple_spec == 1) lay-verify="required" @endif >
                        </td>
                        <td><input type="text" placeholder="例：10.58" class="layui-input number" name="data[products][{{$i}}][sell_price]" value="{{$item->sell_price}}" @if($data->is_multiple_spec == 1) lay-verify="number" @endif></td>
                        <td><button class="layui-btn layui-btn-primary layui-btn-sm spec-del" title="删除"><i class="layui-icon layui-icon-delete"></i></button></td>
                    </tr>
                    @empty
                    <tr>
                        <td><input type="hidden"  name="data[products][0][id]" value=''>
                            <input type="text" placeholder="例：大、中、小等" class="layui-input text" size="5" name="data[products][0][spec_str]" value='' @if($data->is_multiple_spec == 1) lay-verify="required" @endif >
                        </td>
                        <td><input type="text" placeholder="例：10.58" class="layui-input number" name="data[products][0][sell_price]" value="" @if($data->is_multiple_spec == 1) lay-verify="number" @endif></td>
                        <td><button class="layui-btn layui-btn-primary layui-btn-sm spec-del" title="删除"><i class="layui-icon layui-icon-delete"></i></button></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <input type="hidden" name="spec_num" value="{{count($data->specs)}}">
            <script type="text/html" id="tableTpl">
            <tr>
                <td><input type="hidden"  name="data[products][_i_][id]" value=''>
                    <input type="text" placeholder="例：大、中、小等" class="layui-input text" size="5" lay-verify="required"  name="data[products][_i_][spec_str]" value=''>
                </td>
                <td><input type="text" placeholder="例：10.58" class="layui-input number" lay-verify="number" name="data[products][_i_][sell_price]"></td>
                <td><button class="layui-btn layui-btn-primary layui-btn-sm spec-del" title="删除"><i class="layui-icon layui-icon-delete"></i></button></td>
            </tr>
            </script>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">菜品说明</label>
        <div class="layui-input-inline" style="width: 70%">
            <input type="text" name="data[desc]" value="{{$data->desc}}" placeholder="输入菜品说明" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item layui-input-inline">
        <div class="layui-inline">
            <label class="layui-form-label">推荐</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="data[recommend]" value="1" lay-skin="switch" lay-text="是|否" @if($data->recommend == 1) checked @endif>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">上架</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="data[is_shelves]" value="1" lay-skin="switch" lay-text="是|否" @if($data->is_shelves == 1) checked @endif>
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">公开状态</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="data[state]" value="1" lay-skin="switch" lay-text="公开|禁用" @if(!isset($data->state) || $data->state == 1) checked @endif>
            </div>
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
    }).use(['layedit', 'index', 'form', 'upload', 'edit', 'formSelects'], function () {
        var $ = layui.$,
        admin = layui.admin,
        layedit = layui.layedit,
        upload = layui.upload,
        formSelects = layui.formSelects,
        form = layui.form;

        function setCategory(sid) {
            $('#category option.shop').remove();
            $('#template option.shop_'+sid).each(function(){
                $('#category').append($(this)[0].outerHTML);
            });
            form.render();
        }
        form.on('select(shopSelect)', function(data){
            var val = data.value;
            setCategory(val);
        });
//        
//        @if($data->shop_id)
//            setCategory('{{$data->shop_id}}');
//        @endif
        
        form.on('switch(multiple_spec)', function(data){
            if (data.elem.checked) {
                $('#multiple_spec_no').hide();
                $('#multiple_spec_yes').show();
                $('#multiple_spec_no input').removeAttr('lay-verify');
                $('#multiple_spec_yes input.text').attr('lay-verify', 'required');
                $('#multiple_spec_yes input.number').attr('lay-verify', 'number');
            } else {
                $('#multiple_spec_yes').hide();
                $('#multiple_spec_no').show();
                $('#multiple_spec_yes input').removeAttr('lay-verify');
                $('#multiple_spec_no input').attr('lay-verify', 'number');
            }
            form.render();
        });
        
        $(document).on('click','#spec-add',function(){
            var i = $("input[name='spec_num']").val();
            i++;
            $('table.layui-table tbody').append($('#tableTpl').html().replace(/_i_/g, i));
            $("input[name='spec_num']").val(i);
            form.render();
            layer.msg('hello');
        });
        
        $(document).on('click','.spec-del',function(){
            if ($('.spec-del').length <= 1) {
                layer.msg('只有一个规格，不能再删除了');
                return false;
            }
            $(this).parent().parent().remove();
            form.render();
        });
        
        
    })
</script>
@endsection