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
        <link rel="stylesheet" type="text/css" href="/css/found/style.css?1212">
        <script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript" src="/js/swiper.min.js"></script>
        <script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
        <script type="text/javascript" src="/js/countUp.min.js"></script>
    </head>
    <body>
        <div class="main">
            <div class="top_bar">
                <div class="top_bar_left"><a href="javascript:history.back(-1);"><i class="iconfont icon-jiantou1"></i></a></div>
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
            <div class="inv_projects_details_top">
                <div class="inv_projects_details_info">
                    <h3><span>{{ $project->state }}</span>{{ $project->company_name }}</h3>
                    <p>地址：{{ $project->address }}</p>
                </div>
            </div>
            <div class="my_financial_statements_list">
                <h3>风险提示</h3>
                @if($ifper) 
                    @if(count($rs) > 0)
                    <ul>
                        @foreach($rs as $k => $v)
                        <a href="{{ route("web.index.filedetail", [$v->id, 5]) }}"><li><h4>{{ $v->name }}</h4><span><i class="iconfont icon-jiantou"></i></span></li></a>
                        @endforeach
                    </ul>
                    @else
                        <div class="no_limits_text">暂无相关信息</div>
                    @endif
                @else
                    <div class="no_limits_text">当前您无权限查看！</div>
                @endif
            </div>
            @if(!$ifper)
            <div class="pop_bg" id="no_permission">
                <div class="pop_box">
                    <div class="pop_box_con">
                        <p>
                            <strong>内容暂时未开放 <br/> 你没有当前页面的查看权限，<br/>请联系管理员后开通！</strong>
                        </p>
                        <a href="javascript:;" class="confirm_btn">确 认</a>
                    </div>  
                </div>
            </div>
            @endif
    </body>
    <script>
        var myDiv = $(".my_menu");
        $(function() {
            $(".top_bar_right i").click(function(event) {
                showDiv();
                $(document).one("click", function() {
                    $(myDiv).stop().slideToggle(100);

                });
                event.stopPropagation();
            });
            $(myDiv).click(function(event) {
                event.stopPropagation();
            });
        });
        function showDiv() {
            $(myDiv).stop().slideToggle(100);
        }
    </script>
    <script type="text/javascript">
        $(".confirm_btn").click(function(){
            $("#no_permission").hide();
        });
    </script>

</html>
