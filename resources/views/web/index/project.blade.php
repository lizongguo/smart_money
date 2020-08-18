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
                <div class="top_bar_left"><a href="{{ route("web.index.found") }}"><i class="iconfont icon-jiantou1"></i></a></div>
                <div class="top_bar_title">投资项目</div>
                <div class="top_bar_right">
                    <i class="iconfont icon-liebiao"></i>
                    <div class="my_menu">
                        <h3><a href="{{ route("web.index.found") }}">我的基金</a></h3>
                        <ul>
                            <li><a href="{{ route("web.index.project", [$found['found_no']]) }}">投资项目</a></li>
                            <li><a href="{{ route("web.index.financial", [$found['found_no']]) }}">财务报表</a></li>
                            <li><a href="{{ route("web.index.audit", [$found['found_no']]) }}">审计报告</a></li>
                            <li><a href="{{ route("web.index.investment", [$found['found_no']]) }}">出资明细</a></li>
                            <li><a href="{{ route("web.index.risk", [$found['found_no']]) }}">风险提示</a></li>
                            <li><a href="{{ route("web.index.diagram", [$found['found_no']]) }}">基金关系图</a></li>
                            <li><a href="{{ route("web.index.other", [$found['found_no']]) }}">其他</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="my_fund_sub_top">
                <div class="my_fund_sub_top_info">
                    <h3>{{ $found['current_name'] }}</h3>
                    <p>曾用名：{{ $found['ever_name'] }}</p>
                    <span>{{ $found['type'] }}</span>
                </div>
                <div class="my_fund_sub_total">
                    <h3>
                        <span><b id="num_a">{{ $found_total }}</b><p>基金投资总额(单位：@if($found['currency']==1)元@else美元@endif)</p></span>
                        <span><b id="num_b">{{ $total_val }}</b><p>基金当前价值(单位：@if($found['currency']==1)元@else美元@endif)</p></span>
                    </h3>
                    <ul>
                        <li>基金占比：<b>{{ $found['proportion'] }}%</b></li>
                        <li>个人认缴金额：<b>{{ $found['subcribe_pay'] }}</b></li>
                        <li>支付利息总额：<b>{{ $found['pay_interest'] }}</b></li>
                        <li>利息补偿总额：<b>{{ $found['interest_allowance'] }}</b></li>
                        <li>转板补缴总额：<b>{{ $found['add_pay'] }}</b></li>
                    </ul>
                </div>
                <div class="my_fund_sub_tab">
                    <ul>
                        <li class="active"><a href="{{ route("web.index.project", [$found['found_no']]) }}">投资项目</a></li>
                        <li><a href="{{ route("web.index.financial", [$found['found_no']]) }}">财务报表</a></li>
                        <li><a href="{{ route("web.index.audit", [$found['found_no']]) }}">审计报告</a></li>
                        <li><a href="{{ route("web.index.investment", [$found['found_no']]) }}">出资明细</a></li>
                        <li><a href="{{ route("web.index.risk", [$found['found_no']]) }}">风险提示</a></li>
                        <li><a href="{{ route("web.index.diagram", [$found['found_no']]) }}">基金关系图</a></li>
                        <li><a href="{{ route("web.index.other", [$found['found_no']]) }}">其他</a></li>
                    </ul>
                </div>
            </div>
            <div class="my_inv_projects_list">
                <h3>投资项目(单位：@if($found['currency']==1)元@else美元@endif)</h3>
                <ul>
                    @if(isset($projects) and count($projects) > 0)
                        @foreach($projects as $k => $v)
                        <li>
                            <h4>
                                @if($v->state_val==1)
                                <span class="inv_listed">{{ $v->state }}</span>
                                @endif
                                @if($v->state_val==0)
                                <span class="inv_unlisted">{{ $v->state }}</span>
                                @endif
                                @if($v->state_val>1)
                                <span class="inv_over">{{ $v->state }}</span>
                                @endif
                                {{ $v->company_name }}
                            </h4>
                            <h5>
                                <span><b>@if($found['currency']==1){{ $v->amount_cn }}@else{{ $v->amount_us }}@endif</b>投资总额</span>
                                <span><b>{{ $v->rate }}%</b>投资占比</span><span><b>{{ $v->listed_val }}</b>当前价值</span>
                            </h5>
                            <h5 class="my_fund_num">
                                <span><b>{{ $v->self_amount }}</b>个人投资总额</span>
                                <span><b>{{ $v->tz_rate_show }}%</b>个人投资占比</span><span><b>{{ $v->cur_val }}</b>个人当前价值</span>
                            </h5>
                            <div class="my_inv_projects_list_btn">
                                <a href="{{ route("web.index.projectDetail", [$v->project_no, $found['found_no']]) }}"><i class="iconfont icon-jianjie"></i> 项目详细</a>
                                @if($v->state_val==1&&$v->shares_url)
                                    <a target="_blank" href="{{ $v->shares_url }}"><i class="iconfont icon-baobiao1"></i> 股价实时查询</a>
                                @endif
                                <a href="{{ route("web.index.projectRisk", [$v->project_no, $found['found_no']]) }}"><i class="iconfont icon-fengxian"></i> 风险提示</a>
                            </div>
                        </li>
                        @endforeach
                    @else
                        <li>暂无相关信息。</li>
                    @endif
                </ul>
            </div>
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
    <script type="text/javascript">
        var options = {
        useEasing : true,
        useGrouping : false,
        separator : ',',
        decimal : '.',
        prefix : '',
        suffix : ''
        };
        var num_a = new CountUp("num_a", 0, '{{ $found_total }}', 2, 1, options);
        var num_b = new CountUp("num_b", 0, '{{ $total_val }}', 2, 1, options);
        num_a.start();
        num_b.start();
    </script>
</html>
