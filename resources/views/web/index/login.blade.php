<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{$page_title ? $page_title : config('site.title')}}</title>
        <meta name="description" content="{{ config('site.description') }}">
        <meta name="keywords" content="{{ config('site.keywords') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
        <link rel="stylesheet" type="text/css" href="/css/found/swiper.min.css">
        <link rel="stylesheet" type="text/css" href="/css/found/iconfont.css"> 
        <link rel="stylesheet" type="text/css" href="/css/found/style.css?v=0602">
        <script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/swiper.min.js"></script>
        <script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/vintage-popup.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/pgwmenu.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/icheck.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery-html5Validate.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.formkeeper.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/foundation-datepicker.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/foundation-datepicker.ja.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/jquery.mloading.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/new/common.js')}}"></script>
        <script src="{{asset('/js/new/axios.min.js')}}"></script>
        <script src="{{asset('/js/new/VeeValidate/vee-validate.js')}}"></script>
        <script src="{{asset('/js/new/VeeValidate/ja.js')}}"></script>
        <script src="{{asset('/js/new/layer/mobile/layer.js')}}"></script>
        <script type="text/javascript" src="{{asset('/js/new/vue.min.js')}}"></script>
    </head>
    <body>
    <div class="login_main">
        <div class="login_title"><img src="images/login_title.png?v=0602"/></div>
        <div class="login_box">
            <form id="form">
                {{csrf_field()}}
                <ul>
                    <li>
                        <span><i class="iconfont icon-bianzubeifen"></i></span>
                        <input type="text" placeholder="用户名" name="email" class="login_input" required/>
                    </li>
                    <li>
                        <span><i class="iconfont icon-bianzubeifen4"></i></span>
                        <input type="password" placeholder="密码" name="password" data-max="12"  class="login_input" required/>
                    </li>
                    <li><input type="submit" value="登 录" class="login_btn"/></li>
                </ul>
            </form>
        </div>
    </div>
    <script>
        $("#form").html5Validate(function() {
            $.ajax({
                type: "post",
                url: $("#form").attr("action"),
                dataType: "json",
                data: $("#form").serialize(),
                success: function(content){
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            if (content.status == 200) {
                                //window.location.href = content.url;
                                window.location.href = '/';
                            }
                        }
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });
        }, {

        });
    </script>
    </body>
</html>