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
            <div class="inv_projects_details_con">
                <h3>项目简介</h3>
                <div class="inv_projects_details_con_text">{!! nl2br($project->content) !!}</div>
                @if($project->website||$project->file_path)
                <div class="website_btn_bar" style="text-align: center;">
                    @if($project->file_path)
                    <a href="{{ route("web.index.filedetail", [$project->id, 6]) }}" style="width: 30%;display: inline-block;" class="website_btn">详细</a>
                    @endif

                    @if($project->website)
                    <a target="_blank" href="{{ $project->website }}" style="width: 30%;display: inline-block;" class="website_btn"><i class="iconfont icon-diqiu" aria-hidden="true"></i> 公司官网</a>
                    @endif
                </div>
                @endif
            </div>
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
</html>
