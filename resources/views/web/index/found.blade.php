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
				<div class="top_bar_left"><a href="/"><i class="iconfont icon-jiantou1"></i></a></div>
				<div class="top_bar_title">我的基金</div>
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
			<div class="my_fund_top">
				<div class="my_fund_total">
					<h3><span id="num_a">{{ $statis['pay_amount_total'] }}</span><p>基金投资总额（折合美元部分）</p></h3>
					<ul>
						<li>认缴总额：<b>{{ $statis['subcribe_pay_total'] }} </b></li>
						<li>支付利息总额：<b>{{ $statis['pay_interest_total'] }}</b></li>
						<li>利息补偿总额：<b>{{ $statis['interest_allowance_total'] }}</b></li>
						<li>转板补缴总额：<b>{{ $statis['add_pay_total'] }}   </b></li>
					</ul>
				</div>
				<div class="my_fund_total_b">
				<h3><span id="num_b">{{ $statis['self_total_val'] }}</span><p>基金价值（折合美元部分）</p></h3>	
				</div>
			</div>
			<div class="my_fund_list">
				<h3>基金列表</h3>
				<ul>
					@if(isset($founds) and count($founds) > 0)
                        @foreach($founds as $k => $v)
                         	<a href="{{ route("web.index.project", [$v['found_no']]) }}">
								<li>
									<h4>{{ $v['current_name'] }}(单位：@if($v['currency']==1)元@else美元@endif)</h4>
									<h5>
										<span>基金总金额：<b>{{ $v['total_value_cn'] }}</b></span>
										<span>基金当前价值：<b>{{ $v['found_val'] }}</b></span>
									</h5>
									<div class="my_fund_list_info">
										<div class="my_fund_list_info_left">
									
											<p>个人占比：<b>{{ $v['proportion'] }}</b></p>
											<p>个人认缴金额：<b>{{ $v['subcribe_pay'] }}</b></p>
				                    		<p>支付利息总额：<b>{{ $v['pay_interest'] }}</b></p>
				                    		<p>利息补偿总额：<b>{{ $v['interest_allowance'] }}</b></p>
				                    		<p>转板补缴总额：<b>{{ $v['add_pay'] }}</b></p>
										</div>
										<div class="my_fund_list_arrow">
											<i class="iconfont icon-jiantou"></i>
										</div>
									</div>
								</li>
							</a>
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
		//数字滚动效果
		var options = {
		useEasing : true,
		useGrouping : false,
		separator : ',',
		decimal : '.',
		prefix : '',
		suffix : ''
		};
		var num_a = new CountUp("num_a", 0, '{{ $statis['pay_amount_total'] }}', 2, 1, options);
		var num_b = new CountUp("num_b", 0, '{{ $statis['self_total_val'] }}', 2, 1, options);
		num_a.start();
		num_b.start();
	</script>
</html>
