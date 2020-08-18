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
			<div class="fund_details_about_top">
				<div class="fund_details_list_info">
					<h3>{{ $found->current_name }}(单位：@if($found->currency==1)元@else美元@endif)</h3>
					<p>曾用名：{{ $found->ever_name }}</p>
					<span>{{ $found->type }}</span>
				</div>
			</div>
			<div class="fund_details_tab">
				<ul>
					<li class="active">
						<p>基金简介</p>
					</li>
					<li>
						<p>投资项目</p>
					</li>
				</ul>
			</div>
			<div class="fund_details_about_con">
				<ul class="fund_details_about_con_ul">
					<div class="fund_details_about_box">
						{{ $found->introduce }}
						<div class="fund_num">
							<ul>
								<li class="num_box_a"><span id="num_a">{{ $found->total_value_cn }}</span>
									<p>基金总额(单位：@if($found->currency==1)元@else美元@endif)</p>
								</li>
								<li class="num_box_b"><span id="num_b">{{ $total_val }}</span>
									<p>基金价值(单位：@if($found->currency==1)元@else美元@endif)</p>
								</li </ul> </div>
								
						</div>
				</ul>
				
				<ul class="fund_details_about_con_ul" style="display: none;">
				<div class="inv_projects">
					<ul>
						@foreach($projects as $k => $v)
						<a href="{{ route("web.index.projectDetail", [$v->project_no, 1]) }}">
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
								<p>投资总额：<strong>{{ $v->amount_cn }}</strong>投资占比：<strong>{{ $v->rate }}%</strong></p>
								<p>当前价值：<strong>{{ $v->cur_val }}</strong></p>
							</li>
						</a>
						@endforeach
					</ul>
				</div>
				</ul>
				
			</div>
		</div>
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
		var num_a = new CountUp("num_a", 0, '{{ $found->total_value_cn }}', 2, 1, options);
		var num_b = new CountUp("num_b", 0, '{{ $total_val }}', 2, 1, options);
		num_a.start();
		num_b.start();
		$(document).ready(function(e) {
		    $(".fund_details_tab ul li").click(function(){
				$(this).parent(".fund_details_tab ul").children("li").eq($(this).index()).addClass("active").siblings().removeClass("active");
				$(this).parents(".fund_details_tab").next(".fund_details_about_con").children("ul").hide().eq($(this).index()).show();
			})
		});
	</script>
	</body>
</html>