/**
 
 @Name：layuiAdmin 用户登入和注册等
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License: LPPL
 
 */

layui.define(['layedit', 'form'], function (exports) {
    var $ = layui.$
    , layer = layui.layer
    , setter = layui.setter
    , admin = layui.admin
    , layedit = layui.layedit
    , form = layui.form;

    var $body = $('body');
    $('head').append('<link media="all" rel="stylesheet" href="'+setter.uriHost + '../layuiadmin/style/Content/css.css" type="text/css"/>');
    layedit.set({
        console.log(3333);
        //暴露layupload参数设置接口 --详细查看layupload参数说明
        uploadImage: {
            url: setter.uriHost + "api/upload/content",
            accept: 'image',
            field: 'attachment',
            acceptMime: 'image/*',
            exts: 'jpg|png|gif|bmp|jpeg',
            size: 1024 * 10,
            done: function (data) {
                console.log(data);
            }
        },
//        uploadVideo: {
//            url: setter.uriHost + "api/upload/video",
//            accept: 'video',
//            field: 'attachment',
//            acceptMime: 'video/*',
//            exts: 'mp4|flv|avi|rm|rmvb',
//            size: 1024 * 10 * 2,
//            done: function (data) {
//                console.log(data);
//            }
//        },
        uploadFiles: {
            url: setter.uriHost + "api/upload/files",
            accept: 'file',
            field: 'attachment',
            acceptMime: 'file/*',
            size: '20480',
            autoInsert: true, //自动插入编辑器设置
            done: function (data) {
                console.log(data);
            }
        }
        //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
        //传递参数：
        //图片： imgpath --图片路径
        //视频： filepath --视频路径 imgpath --封面路径
        //附件： filepath --附件路径
        , calldel: {
            url: setter.uriHost + "api/upload/delete/0",
            done: function (data) {
                console.log(data);
            }
        }
        , rightBtn: {
            type: "layBtn", //default|layBtn|custom  浏览器默认/layedit右键面板/自定义菜单 default和layBtn无需配置customEvent
            customEvent: function (targetName, event) {
                //根据tagName分类型设置
                switch (targetName) {
                    case "img":
                        alert("this is img");
                        break;
                    default:
                        alert("hello world");
                        break;
                }
                ;
                //或者直接统一设定
                //alert("all in one");
            }
        }
        //测试参数
        , backDelImg: true
                //开发者模式 --默认为false
        , devmode: true
                //是否自动同步到textarea
        , autoSync: true
                //内容改变监听事件
        , onchange: function (content) {
            console.log(content);
        }
        //插入代码设置 --hide:false 等同于不配置codeConfig
        , codeConfig: {
            hide: true, //是否隐藏编码语言选择框
            default: 'javascript', //hide为true时的默认语言格式
            encode: true //是否转义
            , class: 'layui-code' //默认样式
        }
        //新增iframe外置样式和js
        , quote: {
//            style: [layui.cache.base + '/style/Content/css.css'],
            //js: ['/Content/Layui-KnifeZ/lay/modules/jquery.js']
        }
        //自定义样式-暂只支持video添加
        //, customTheme: {
        //    video: {
        //        title: ['原版', 'custom_1', 'custom_2']
        //        , content: ['', 'theme1', 'theme2']
        //        , preview: ['', '/images/prive.jpg', '/images/prive2.jpg']
        //    }
        //}
        //插入自定义链接
        , customlink: {
            title: '插入官网'
            , href: setter.uriHost
            , onmouseup: ''
        }
        , facePath: 'http://knifez.gitee.io/kz.layedit/Content/Layui-KnifeZ/'
        , devmode: true
        , videoAttr: ' preload="none" '
                //预览样式设置，等同layer的offset和area规则，暂时只支持offset ,area两个参数
                //默认为 offset:['r'],area:['50%','100%']
                //, previewAttr: {
                //    offset: 'r'
                //    ,area:['50%','100%']
                //}
        , tool: [
            'html', 'undo', 'redo', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'removeformat', 'fontFomatt', 'fontfamily', 'fontSize', 'fontBackColor', 'colorpicker', 'face'
                    , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'attachment', 'anchors'
                    , '|'
                    , 'table', 'customlink'
                    , 'fullScreen', 'preview'
        ] //'video', 'fontBackColor', 'colorpicker', 
        , height: '300px'
    });
    
    //对外暴露的接口
    exports('edit', {});
});