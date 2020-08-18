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
        <link rel="stylesheet" type="text/css" href="/css/found/style.css?v=0518">
        <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="js/common.js"></script>
        <script type="text/javascript" src="js/swiper.min.js"></script>
        <script type="text/javascript" src="js/jQuery.autoIMG.js"></script>
    </head>
    <body>
    <div class="main">
        <div class="top_bar">
            <div class="top_bar_left"></div>
            <div class="top_bar_title"></div>
            <div class="top_bar_right">
                <i class="iconfont icon-liebiao"></i>
                <div class="my_menu">
                    <h3><a href="{{ route("web.index.found") }}">我的基金</a></h3>
                    <ul>
                        <li><a href="{{ route("web.index.logout") }}">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="my_profile_box">
            <div class="my_pfofile_info">
            <div class="my_avatar">
                @if($$baseInfo->uicon)
                <img src="{{ $baseInfo->uicon }}"/>
                @else
                <img src="/images/user_img.png"/>
                @endif
            </div>
            <div class="my_name">
                <h2>{{ $baseInfo->name }}</h2>
                <p>{{ $baseInfo->email }}</p>
                </div>
            </div>
            <a href="{{ route("web.index.found") }}"><div class="my_inv_btn"><i class="iconfont icon-qiandai"></i>我的投资</div></a>
        </div>
        <div class="fund_list">
            <h3><i class="iconfont icon-gongsi"></i> 基金投资公司</h3>
            <ul>
                <a href="{{ route("web.index.foundDetail") }}">
                    <li>
                        <div class="fund_list_img"><img src="/images/cloud.png"/></div>
                        <div class="fund_list_info">
                            <h4>成都云平台</h4>
                            <p>成都云平台是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济</p>
                            <span>查看详情<i class="iconfont icon-jiantou"></i></span>
                        </div>
                    </li>
                </a>
            </ul>
        </div>
        <div class="menu_tab">
            <ul>
                <li class="active"><a href="/"><span><i class="iconfont icon-shouye"></i></span><p>首页</p></a></li>
                <li><a href="{{ route("web.index.content", 1) }}"><span><i class="iconfont icon-guanlijijin"></i></span><p>基金</p></a></li>
                <li><a href="{{ route("web.index.content", 2) }}"><span><i class="iconfont icon-zhengcezhinanzhen"></i></span><p>政策法规</p></a></li>
                <li><a href="{{ route("web.index.content", 3) }}"><span><i class="iconfont icon-shuiwu"></i></span><p>税务</p></a></li>
                <li><a href="{{ route("web.index.tool") }}"><span><i class="iconfont icon-yingyong"></i></span><p>实用工具</p></a></li>
            </ul>
        </div>
    </div>
    <script>
        var myDiv = $(".my_menu");
        $(function ()
        {
        $(".top_bar_right i").click(function (event) 
        {
    
        showDiv();
        $(document).one("click", function () 
        {
        $(myDiv).stop().slideToggle(100);
        
        });
         
        event.stopPropagation();
        });
        $(myDiv).click(function (event) 
        {
         
        event.stopPropagation();
        });
        });
        function showDiv() 
        {
        $(myDiv).stop().slideToggle(100);
        }
    </script>
    </body>
</html>