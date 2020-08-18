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
                        <li>个人认缴金额：<b>{{ $found['subcribe_pay'] }}元</b></li>
                        <li>支付利息总额：<b>{{ $found['pay_interest'] }}元</b></li>
                        <li>利息补偿总额：<b>{{ $found['interest_allowance'] }}元</b></li>
                        <li>转板补缴总额：<b>{{ $found['add_pay'] }}元</b></li>
                    </ul>
                </div>
                <div class="my_fund_sub_tab">
                    <ul>
                        <li><a href="{{ route("web.index.project", [$found['found_no']]) }}">投资项目</a></li>
                        <li><a href="{{ route("web.index.financial", [$found['found_no']]) }}">财务报表</a></li>
                        <li><a href="{{ route("web.index.audit", [$found['found_no']]) }}">审计报告</a></li>
                        <li class="active"><a href="{{ route("web.index.investment", [$found['found_no']]) }}">出资明细</a></li>
                        <li><a href="{{ route("web.index.risk", [$found['found_no']]) }}">风险提示</a></li>
                        <li><a href="{{ route("web.index.diagram", [$found['found_no']]) }}">基金关系图</a></li>
                        <li><a href="{{ route("web.index.other", [$found['found_no']]) }}">其他</a></li>
                    </ul>
                </div>
            </div>
            @if($ifper)
            <div class="fund_details_tab">
                <ul>
                    <li class="active">
                        <p>我的</p>
                    </li>
                    <li>
                        <p>全体</p>
                    </li>
                </ul>
            </div>
            <div class="my_investment_details">
                @if(count($single) > 0)
                <ul>
                    <table class="my_investment_details_table">
                        <thead>
                            <th width="15%">认缴人</th>
                            <th width="30%">认缴金额（人民币）</th>
                            <th width="30%">认缴金额（美元）</th>
                            <th width="25%">认缴时间</th>
                        </thead>
                    <tbody>
                        @foreach($single as $k => $v)
                        <tr>
                        <td>{{ $v->uname }}</td>
                        <td>{{ $v->amount_cn }}</td>
                        <td>{{ $v->amount_us }}</td>
                        <td>{{ $v->date }}</td>
                        </tr>
                        @endforeach
                    </tbody>    
                    </table>
                    <div class="my_investment_details_total">合计(人民币)： <b>{{ $total_single }}</b>&nbsp;&nbsp;&nbsp;合计(美元)： <b>{{ $total_single_us }}</b></div>
                </ul>
                @else
                    <ul style="text-align: center;padding-top: 50px;height: 100px;">暂无相关信息</ul>
                @endif

                @if(count($group) > 0)
                <ul style="display:none;">
                    <table class="my_investment_details_table">
                        <thead>
                            <th width="15%">认缴人</th>
                            <th width="30%">认缴金额（人民币）</th>
                            <th width="30%">认缴金额（美元）</th>
                            <th width="25%">认缴时间</th>
                        </thead>
                    <tbody>
                        @foreach($group as $k => $v)
                        <tr>
                        <td>{{ $v->uname }}</td>
                        <td>{{ $v->amount_cn }}</td>
                        <td>{{ $v->amount_us }}</td>
                        <td>{{ $v->date }}</td>
                        </tr>
                        @endforeach
                    </tbody>    
                    </table>
                    <div class="my_investment_details_total">合计(人民币)： <b>{{ $total_group }}</b>&nbsp;&nbsp;&nbsp;合计(美元)： <b>{{ $total_group_us }}</b></div>
                </ul>
                @else
                    <ul style="text-align: center;padding-top: 50px;height: 100px;display: none;">暂无相关信息</ul>
                @endif
            </div>
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
    <script type="text/javascript">
        $(document).ready(function(e) {
            $(".fund_details_tab ul li").click(function(){
                $(this).parent(".fund_details_tab ul").children("li").eq($(this).index()).addClass("active").siblings().removeClass("active");
                $(this).parents(".fund_details_tab").next(".my_investment_details").children("ul").hide().eq($(this).index()).show();
            })  
        });
    </script>
    <script type="text/javascript">
        $(".confirm_btn").click(function(){
            $("#no_permission").hide();
        });
    </script>
</html>
