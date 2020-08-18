@extends('layouts.admin')
@section('content')
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                {{--<div class="layui-inline">--}}
                    {{--<label class="layui-form-label">ID</label>--}}
                    {{--<div class="layui-input-block">--}}
                        {{--<input type="text" name="sh[resume_id]" placeholder="请输入" autocomplete="off" class="layui-input">--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="layui-inline">
                    <label class="layui-form-label">名前</label>
                    <div class="layui-input-block">
                        <input type="text" name="sh[name]" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <input type="text" class="layui-input" name="sh[created_start]" id="test-laydate-start">
                </div>
                <div class="layui-inline">
                    〜
                </div>
                <div class="layui-inline">
                    <input type="text" class="layui-input" name="sh[created_end]" id="test-laydate-end">
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
                {{--<button class="layui-btn layuiadmin-btn-admin" data-type="batchdel">删除</button>--}}
{{--                <button class="layui-btn layuiadmin-btn-admin" data-type="add">添加</button>--}}
            </div>
            
            <table id="LAY-admin-manage" lay-filter="LAY-admin-manage"></table>
            
            <script type="text/html" id="table-admin-opation">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">詳細</a>
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">推薦文</a>
            </script>
            <script type="text/html" id="experienceTpl">
                @{{#  if(d.is_experience == 1){ }}
                有
                @{{#  } else { }}
                無
                @{{#  } }}
            </script>
        </div>
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
layui.config({
    uriHost: '{{asset("/admin")}}/', //项目管理端path
    base: '{{asset("/layuiadmin/")}}/', //静态资源所在路径
    version: '20200004',
}).extend({
    index: 'lib/index' //主入口模块
}).use(['index', 'form', 'table', 'laydate'], function () {
    var $ = layui.$
            , form = layui.form
            , admin = layui.admin
            , table = layui.table
            , laydate = layui.laydate;
    //用户管理
    var d = new Date();
    var fileName = "履歴書・職務経歴書一覧" + d.getFullYear() + "年" + (d.getMonth() + 1) + "月" + d.getDate() + "日" + d.getHours() + "時" + d.getMinutes() + "分";
    table.render({
        toolbar: true,
        totalrow: true,
        title: fileName,
        elem: '#LAY-admin-manage'
        , url: '{{route("experience.items")}}' //数据接口
        , cols: [[
               //{type: 'checkbox', fixed: 'left'},
                {field: 'id', width: 70, title: 'ID', sort: true}
                , {field: 'name', title: '名前', width: 120}
                , {field: 'sex', title: '性別', width: 80, sort: true}
                , {field: 'age', title: '年齢', width: 80}
                , {field: 'birthday', title: '生年日', width: 120}
                , {field: 'nationality_str', title: '国籍', width: 130}
                , {field: 'address_str', title: '現住所', width: 140}
                , {field: 'emergency_contact_address', title: '緊急連絡先', width: 140}
                , {field: 'visa_type_str', title: 'ビザ種類', width: 100, hide:true}
                , {field: 'visa_term', title: 'ビザ有効期限', width: 120, hide:true}
                , {field: 'nearest_station', title: '最寄駅', width: 120, hide:true}
                , {field: 'japan_year', title: '来日年数', width: 100}
                , {field: 'have_license', title: '資格・免許', width: 120}
                , {field: 'jp_level_str', title: '日本語', width: 200}
                , {field: 'en_level_str', title: '英語', width: 200}
                , {field: 'language_skill_str', title: '語学スキル(その他)', width: 200, hide:true}
                , {field: 'email', title: 'Eメールアドレス', width: 150}
                , {field: 'postal_code', title: '郵便番号', width: 180, hide:true}
                , {field: 'cell_phone', title: '携帯電話', width: 120}
                , {field: 'skill_os', title: 'ITスキルOS', width: 160, hide:true}
                , {field: 'skill_office', title: 'ITスキルOFFICE', width: 160, hide:true}
                , {field: 'desired_places', title: '希望勤務地', width: 200, hide:true}
                , {field: 'commuting_hours_str', title: '通勤時間', width: 100}
                , {field: 'family_members_num_str', title: '扶養家族', width: 100}
                , {field: 'is_spouse_str', title: '配偶者', width: 80}
                , {field: 'is_spouse_support_str', title: '配偶者の扶養義務', width: 150, hide:true}
                , {field: 'pr_other', title: '自己PR', width: 200, hide:true}
                , {field: 'other_expected_types', title: '希望記入欄', width: 200, hide:true}
                , {field: 'is_experience', title: '職歴', width: 200, hide:true, templet: '#experienceTpl' }
                , {field: 'job_summary', title: '職務要約', width: 200, hide:true}
                , {field: 'created_at', title: '作成時間', width: 180, hide: false}
                , {title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-admin-opation'}
            ]]
        , page: true
        , limit: 30
        , height: 'full-220'
        , text: {
            none: '暂未查询到相关数据' //默认：无数据。注：该属性为 layui 2.2.5 开始新增
        }
    });

    //开始日期
    var insStart = laydate.render({
        elem: '#test-laydate-start'
        ,done: function(value, date){
            //更新结束日期的最小日期
            insEnd.config.min = lay.extend({}, date, {
                month: date.month - 1
            });
        }
    });

    //结束日期
    var insEnd = laydate.render({
        elem: '#test-laydate-end'
        ,done: function(value, date){
            //更新开始日期的最大日期
            insStart.config.max = lay.extend({}, date, {
                month: date.month - 1
            });
        }
    });

    var deleteUri = "{{route('experience.delete', ['id' => '_id'])}}";
    var editUri = "{{route('experience.input', ['id' => '_id'])}}";
    var detailUri = "{{route('experience.detail', ['id' => '_id'])}}";
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
        var title = '推薦文編集';
        if (typeof id !== 'undefined') {
            var uri = editUri.replace('_id', id);
            title = '推薦文編集';
        } else {
            var uri = editUri.replace('/_id', '');
        }
        var layerIndex = layer.open({
            type: 2
            , title: title
            , content: uri
            , area: ['500px', '450px']
            , btn: ['　OK　', 'キャンセル']
            , yes: function (index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index]
                    , submitID = 'LAY-input-front-submit'
                    , submit = layero.find('iframe').contents().find('#' + submitID);
                       iframeWindow.layui.form.render();
                //监听提交
                iframeWindow.layui.form.on('submit(' + submitID + ')', function (data) {
                    var field = data.field; //获取提交的字段
                    console.log(field);
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
                            layer.msg('保存に成功しました。', {
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

    //编辑添加功能
    function detailData(id) {
        var title = '履歴書・職務経歴書詳細';
        var uri = detailUri.replace('_id', id);
        var layerIndex = layer.open({
            type: 2
            , title: title
            , content: uri
            , maxmin: true
            , area: ['500px', '450px']
            , btn: ['　OK　']
            , yes: function (index, layero) {
                layer.close(index);
            }
            , success: function (layero, index) {

            }
        });
        //全屏显示
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
                    url: deleteUri.replace('_id', data.resume_id)
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
        else if (obj.event === 'detail') {
            var tr = $(obj.tr);
            detailData(obj.data.id);
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