<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{$page_title ? $page_title : config('site.title')}}</title>
  		<meta name="description" content="{{ config('site.description') }}">
  		<meta name="keywords" content="{{ config('site.keywords') }}">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/found/iconfont.css"> 
		<link rel="stylesheet" type="text/css" href="/css/found/style.css?v=0513">
		<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/found/photoswipe.css">
		<link rel="stylesheet" type="text/css" href="/css/found/default-skin/default-skin.css">
		<script type="text/javascript" src="/js/photoswipe.min.js"></script>
		<script type="text/javascript" src="/js/photoswipe-ui-default.min.js"></script>
	</head>

	<body>
	<div class="main">
		<div class="top_bar">
			<div class="top_bar_left"><a href="javascript:history.back(-1);"><i class="iconfont icon-jiantou1"></i></a></div>
			<div class="top_bar_title"></div>
			<div class="top_bar_right">
			</div>
		</div>

		<div class="fund_details">
			<div class="fund_details_tab">
				<ul>
					<li class="active"><p>基金</p></li>
					<!-- <li><p>公司信息</p></li> -->
				</ul>
			</div>
			<div class="fund_details_tab_content">
			<ul>
			<div class="fund_details_list">
			<ul>
				@foreach($founds as $k => $v)
				<a href="{{ route("web.index.foundDetailAbout", [$v->found_no]) }}">
					<li>
						<div class="fund_details_list_info">
						<h3>{{ $v->current_name }}</h3>
						<p>曾用名：{{ $v->ever_name }}</p>
						<span>{{ $v->type }}</span>
						</div>
						<div class="fund_details_list_arrow">
							<i class="iconfont icon-jiantou"></i>
						</div>	
					</li>
				</a>
				@endforeach
			</ul>		
			</div>	
			</ul>
			<ul style="display: none;" class="sub_ul" >
				<div class="sub_con">
				<ul style="display: block;" class="sub_con_ul">
					<div class="fund_about">
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。
					盘古创富是一支拥有国际化专业化投资背景的团队营运的私药股权投资管理有限公司。自2008年创立以来，着眼于中国经济中的高成长创新型领域的企业，借鉴海外产业的成熟经验和技术，帮助投资项目企业强化竞争力，扩大国际销售渠道，助力企业实现了快速增值。	
					</div>
				
				</ul>
				<ul class="sub_con_ul">
				<div class="fund_histroy">
				<ul>
				<li>
					<h4>【2008年3月】</h4>
					<p>日本朝规资本（Ant Capltal)的雷港成立其在中国区的授演主体公司Vangoo Capital Limlited(盘古基金授询管理有限公司）</p>
					</li>	
					<li>
						<h4>【2010年3月】</h4>
						<p>和天津有达集团成立合激基金管理公司以及合演般权投资企业</p>
						</li>
						<li>
							<h4>【2011年3月】</h4>
							<p>和中粮集团成立合演农业产业基金管理公司以及合激衣农业产业段权投资概盒</p>
							</li>
							<li>
								<h4>【2011年3月】</h4>
								<p>蚂蚁资本中国区投资团队完成对盘古创富的管理层收购，全面本土化</p>
								</li>
									<li>
									<h4>【2013年3月】</h4>
									<p>和中信信托以及日本上市公司EPS在中国的全资子公司益新中国合资设立苏州益信开元医疗健康股权投资合伙企业</p>
									</li>
				</ul>	
				</div>
				</ul>
				<ul class="sub_con_ul">
				<div class="fund_class">
					<ul>
						<li class="fund_class_a"><span><i class="iconfont icon-jiqiren"></i></span><h4>人工智能</h4><p>自然语言生成</p><p>语音识别</p><p>计算机视觉</p><p>知识图谱</p><p>深度学习</p></li>
						<li class="fund_class_b"><span><i class="iconfont icon-yunjisuan"></i></span><h4>信息技术</h4><p>大数据</p><p>通讯技术</p><p>区块链</p><p>分布式存储</p></li>
						<li class="fund_class_c"><span><i class="iconfont icon-huaban-"></i></span><h4>光电芯片</h4><p>光通讯</p><p>光电技术</p><p>集成电路</p><p>芯片技术等</p></li>
						<li class="fund_class_d"><span><i class="iconfont icon-shengwu"></i></span><h4>生物技术</h4><p>人脑工程</p><p>基因技术</p><p>生物制药</p><p>干细胞</p><p>胚胎筛选技术</p></li>
						<li class="fund_class_e"><span><i class="iconfont icon-dianqizidonghua"></i></span><h4>智能制造</h4><p>3D打印</p><p>传感器</p><p>数控设备</p><p>工业互联网</p><p>离散制造</p></li>
						<li class="fund_class_f"><span><i class="iconfont icon-feiji"></i></span><h4>航天航空</h4><p>飞机发动机</p><p>卫星导航</p><p>空间探测器</p><p>地理信息系统</p><p>飞机器</p></li>
					</ul>
				</div>
				</ul>
				<ul class="sub_con_ul">
				<div class="team_list">
					<ul>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_01.jpg"/></div>
							<div class="team_list_info">
								<h4>许萍（创始合伙人/首席执行官)</h4>
								<p>于2005年加入Ant Capital Partners公司，负责其中国的投资业务，帮助日本企业获得战略伙伴和走进中国市场，同时帮助中国企业购并拥有先进技术和品牌的日本公司。2011年许萍和其团队成功地从AntCapital Partners公司以管理层收购（MBO）独立。<br>作为盘古资本的创始合伙人，在中日的二级资本市场和私莱股权投资方面拥有近二十年的经验，且能够通过其在中日商界和政界的独有的丰富人脉和网络，为中日两国商业发展起到重要的桥梁作用。在消费相关、TMT、电子商务、能源相关、医疗健康领域都有成功的投资案例。且不仅在各个产业领城有广泛间历和投资经验，而且在整个基金公司的运作和组织能力上也有丰窗的经验。先后与天津泰达集团、中粮集团设立了合资基金；与中信以及益新中国投资公司一起组建了一只国际医疗健康投资基金一苏州益信开元。<br>庆应义垫大学经济学专业毕业。北京大学光华管理学院EMBA毕业。</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
								<a href="https://www.toutiao.com/a1662768823523340" target="_blank"  class="win_button"><em>赢在路上</em></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_02.png"/></div>
							<div class="team_list_info">
								<h4>刘凯（管理合伙人)</h4>
								<p>通信行业资深经验，在朗讯科技从事网络方案咨询、3G新产品引入、商务管理、4G产品定价策略等工作，对通信行业、互联网行业有着深刻理解。加入盘古创富后，专注于企业服务、金融科技、网络安全、人工智能等方面。先后参与及负责了搜当网、况客金服、摩贝、布比区块链、杰思安全、正益移动、风险管家多个项目的股权投资。<br>清华大学MBA、中国科学院硕士。</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_03.png"/></div>
							<div class="team_list_info">
								<h4>张燕玲（副总裁)</h4>
								<p>曾任新华网股份有限公司投资办投资总监，之前任职于人民网、盛大资本，负责TMT、大文化领域的战略投资，在该领域积累了丰富的并购与股权投资经验。参与和主导了布比区块链、链安科技、澳客网、微屏软件、酷6网、盛世骄阳、虾米等项目。<br>毕业于北京化工大学，企业管理硕士。</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_04.png"/></div>
							<div class="team_list_info">
								<h4>刘文浩（副总裁)</h4>
								<p>曾就职于中信国安集团有限公司资本运营部、毕马威华振会计师事务所，参与多项境内外IPO及并购重组项目，在TMT、文化传媒、消费品等领域积累了丰富的投资及财务审计经验。加入盘古创富以来，参与和主导了风险管家、中交信源等多个项目。<br>毕业于上海同济大学工业工程专业，获得了CFA2级认证。</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_05.png"/></div>
							<div class="team_list_info">
								<h4>许琪（风险合伙人)</h4>
								<p>药历网创始人<br>曾担任美国研发与制药生产者协会（PhRMA）驻中国的第一企代表，中国研发制药工业协会（RDPAC)高级经济政策主管、法玛西亚公司（Pharmacia Corporation，现为辉瑞）国际金融总监、企业策划总监。<br>北京大学物理学士学位和美国华盛顿大学MBA学位（金融)</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_06.png"/></div>
							<div class="team_list_info">
								<h4>严浩 先生（风险合伙人)</h4>
								<p>日本EPS集团创始人<br>日本中华总商会会长、中华海外联谊会理事<br>国务院侨办海外专家咨询委员<br>日本内阁府专家咨询委员<br>拥有深厚的中日医药产业资源</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_07.png"/></div>
							<div class="team_list_info">
								<h4>郁雯（法务总监)</h4>
								<p>曾就职于国内著名律师事务所，熟悉公司、合同、外商投资、房地产等方面的法律、法规并具有丰富的实践经验。曾为诸多大中型企业的资产转让/股权并购项目提供尽职调查、交易架构设计、法律文本撰拟、合同谈判、报批登记等全过程的法律服务。曾为多家境外机构在中国投资设立股权投资基金提供全程法律服务。<br>中国政法大学民商经济法学院毕业，硕士学位。2008年取得中国法律职业资格</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						<a href="#">
						<li>
							<div class="team_list_img"><img src="/images/team_08.png"/></div>
							<div class="team_list_info">
								<h4>王巍（基金管理总监)</h4>
								<p>超过十年的海外及国内财务和基金管理经验，曾任职于KPMGAUSTRALIA,担任会计及税务顾问，为企业提供日常财务服务及税务规划、中报工作；2012年回国加入盘古创富，担任财务及基金管理工作至今。热悉私募股权基金的设立、协会备案、财务、税收、审计、投后管理等基金管理工作。毕业于Curtin Universityof Technology Australia金融专业，CPA AUSTRALIA会员。</p>
								<a  class="more_button"><em>查看全部</em><i class="iconfont icon-jiantou1"></i></a>
							</div>
						</li>
						</a>
						
						
			
						
		
					</ul>
				</div>
				</ul>
				<ul class="sub_con_ul">
					@if($ifper)
					<div class="fund_pic">
						<figure itemprop="associatedMedia" id="0" >
							<a href="/images/company.jpg" itemprop="contentUrl" data-size="1000x1000">
								<img id="img0" itemprop="thumbnail" alt="000" src="/images/company.jpg"/>
							</a>
						</figure>
					</div>
					@else
					<div class="no_limits_text">当前您无权限查看！</div>
					@endif
				</ul>
				</div>

				<div class="sub_tab">
					<ul>
						<li data-id="1" class="active"><a href="javascript:;"><span><i class="iconfont icon-jianjie"></i></span><p>简介</p></a></li>
						<li data-id="2"><a href="javascript:;"><span><i class="iconfont icon-gongsi"></i></span><p>公司沿革</p></a></li>
						<li data-id="3"><a href="javascript:;"><span><i class="iconfont icon-diqiu"></i></span><p>投资范围</p></a></li>
						<li data-id="4"><a href="javascript:;"><span><i class="iconfont icon-tuandui"></i></span><p>团队</p></a></li>
						<li data-id="5"><a href="javascript:;"><span><i class="iconfont icon-guanxitu"></i></span><p>关系图</p></a></li>
					</ul>
				</div>
			</ul>
			</div>
			

			
		</div>
		<div class="pop_bg" id="no_permission" style="display: none;">
            <div class="pop_box">
                <div class="pop_box_con">
                    <p>
                        <strong>内容暂时未开放 <br/> 你没有当前页面的查看权限，<br/>请联系管理员后开通！</strong>
                    </p>
                    <a href="javascript:;" class="confirm_btn">确 认</a>
                </div>  
            </div>
    	</div>
	<script type="text/javascript">
		var ifper = '{{ $ifper }}';
		$(document).ready(function(e) {

			$(".more_button").on("click",function(){
			$(this).toggleClass("on");
			 if($(this).children("em").html() == "查看全部"){
				  $(this).parent().find("p").addClass("all-txt");
			 	  $(this).children("em").html("点击收起");
			 	 }
			 else{
			  $(this).parent().find("p").removeClass("all-txt");
			  $(this).children("em").html("查看全部");
			 }
			});
			
			//悬浮
			var nav = $(".fund_details_tab");
			var sub_nav = $(".sub_tab");
			var position = nav.position();
			var divTop = nav.position().top; //获取其top值
			$(window).scroll(function() {
				if ($(this).scrollTop() > divTop) {
					nav.addClass("navFix");
					
				} else {
					nav.removeClass("navFix");
				}
			})
	        $(".fund_details_tab ul li").click(function(){
				$(this).parent(".fund_details_tab ul").children("li").eq($(this).index()).addClass("active").siblings().removeClass("active");
				$(this).parents(".fund_details_tab").next(".fund_details_tab_content").children("ul").hide().eq($(this).index()).show();
			})
			$(".sub_tab ul li").click(function(){
				if ($(this).data('id')==5&&ifper==0) {
					$("#no_permission").show();
				}
				$(this).parent(".sub_tab ul").children("li").eq($(this).index()).addClass("active").siblings().removeClass("active");
				$(this).parents(".sub_tab").prev(".sub_con").children("ul").hide().eq($(this).index()).show();
			})
	    });
	</script>

	<script type="text/javascript">
        $(".confirm_btn").click(function(){
            $("#no_permission").hide();
        });
    </script>


		</div>


		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"> 
		  
		  <!-- Background of PhotoSwipe. 
		         It's a separate element, as animating opacity is faster than rgba(). -->
		  <div class="pswp__bg"></div>
		  
		  <!-- Slides wrapper with overflow:hidden. -->
		  <div class="pswp__scroll-wrap"> 
		    
		    <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. --> 
		    <!-- don't modify these 3 pswp__item elements, data is added later on. -->
		    <div class="pswp__container">
		      <div class="pswp__item"></div>
		      <div class="pswp__item"></div>
		      <div class="pswp__item"></div>
		    </div>
		    
		    <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
		    <div class="pswp__ui pswp__ui--hidden">
		      <div class="pswp__top-bar"> 
		        
		        <!--  Controls are self-explanatory. Order can be changed. -->
		        
		        <div class="pswp__counter"></div>
		        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
		 
		        <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
		        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
		        
		        <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR --> 
		        <!-- element will get class pswp__preloader--active when preloader is running -->
		        <div class="pswp__preloader">
		          <div class="pswp__preloader__icn">
		            <div class="pswp__preloader__cut">
		              <div class="pswp__preloader__donut"></div>
		            </div>
		          </div>
		        </div>
		      </div>
		      <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
		        <div class="pswp__share-tooltip"></div>
		      </div>
		      <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"> </button>
		      <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"> </button>
		      <div class="pswp__caption">
		        <div class="pswp__caption__center"></div>
		      </div>
		    </div>
		  </div>
		</div>
		<script>
		var initPhotoSwipeFromDOM = function(gallerySelector) {
		
		    // parse slide data (url, title, size ...) from DOM elements 
		    // (children of gallerySelector)
		    var parseThumbnailElements = function(el) {
		      // var thumbElements = el.childNodes,
			   var thumbElements = $(gallerySelector+'figure'),
		            numNodes = thumbElements.length,
		            items = [],
		            figureEl,
		            linkEl,
		            size,
		            item;
		//alert(thumbElements.html());
		        for(var i = 0; i < numNodes; i++) {
		
		            figureEl = thumbElements[i]; // <figure> element
		
		            // include only element nodes 
		            if(figureEl.nodeType !== 1) {
		                continue;
		            }
					
		            linkEl = figureEl.children[0]; // <a> element
		            //alert(linkEl.children[0].getAttribute('src'));
					
		            size = linkEl.getAttribute('data-size').split('x');
		
		            // create slide object
		            item = {
		                src: linkEl.getAttribute('href'),
		                w: parseInt(size[0], 10),
		                h: parseInt(size[1], 10)
		            };
		
		
		
		            if(figureEl.children.length > 1) {
		                // <figcaption> content
		                item.title = figureEl.children[1].innerHTML; 
		            }
		
		            if(linkEl.children.length > 0) {
		                // <img> thumbnail element, retrieving thumbnail url
		                //item.msrc = $("#img"+i).attr('src');//linkEl.children[0].getAttribute('src');
						 item.msrc = linkEl.children[0].getAttribute('src');
						//alert(item.msrc);
						//break;
		            } 
		
		            item.el = figureEl; // save link to element for getThumbBoundsFn
		            items.push(item);
		        }
		
		        return items;
		    };
		
		    // find nearest parent element
		    var closest = function closest(el, fn) {
		        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		    };
		
		    // triggers when user clicks on thumbnail
		    var onThumbnailsClick = function(e) {
		        e = e || window.event;
		        e.preventDefault ? e.preventDefault() : e.returnValue = false;
		
		        var eTarget = e.target || e.srcElement;
		
		        // find root element of slide
		        var clickedListItem = closest(eTarget, function(el) {
		            return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
		        });
		
		        if(!clickedListItem) {
		            return;
		        }
		
		        // find index of clicked item by looping through all child nodes
		        // alternatively, you may define index via data- attribute
		        var clickedGallery = clickedListItem.parentNode,
		            childNodes = clickedListItem.parentNode.childNodes,
		            numChildNodes = childNodes.length,
		            nodeIndex=2,
		            index;
		
		        for (var i = 0; i < numChildNodes; i++) {
		            if(childNodes[i].nodeType !== 1) { 
		                continue; 
		            }
		
		            if(childNodes[i] === clickedListItem) {
		                index = childNodes[i].getAttribute('id');
						//console.log(childNodes[i].getAttribute('id'));
		                break;
		            }
		            nodeIndex++;
		        }
		
		
				//alert(index);
		        if(index >= 0) {
		            // open PhotoSwipe if valid index found
		            openPhotoSwipe( index, clickedGallery );
		        }
		        return false;
		    };
		
		    // parse picture index and gallery index from URL (#&pid=1&gid=2)
		    var photoswipeParseHash = function() {
		        var hash = window.location.hash.substring(1),
		        params = {};
		
		        if(hash.length < 5) {
		            return params;
		        }
		
		        var vars = hash.split('&');
		        for (var i = 0; i < vars.length; i++) {
		            if(!vars[i]) {
		                continue;
		            }
		            var pair = vars[i].split('=');  
		            if(pair.length < 2) {
		                continue;
		            }           
		            params[pair[0]] = pair[1];
		        }
		
		        if(params.gid) {
		            params.gid = parseInt(params.gid, 10);
		        }
		
		        return params;
		    };
		
		    var openPhotoSwipe = function(index, galleryElement, disableAnimation, fromURL) {
		        var pswpElement = document.querySelectorAll('.pswp')[0],
		            gallery,
		            options,
		            items;
		
		        items = parseThumbnailElements(galleryElement);
		
		        // define options (if needed)
		
		        options = {
		
		            // define gallery index (for URL)
		            galleryUID: galleryElement.getAttribute('data-pswp-uid'),
		
		            getThumbBoundsFn: function(index) {
		                // See Options -> getThumbBoundsFn section of documentation for more info
		                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
		                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		                    rect = thumbnail.getBoundingClientRect(); 
		
		                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		            }
		
		        };
		
		        // PhotoSwipe opened from URL
		        if(fromURL) {
		            if(options.galleryPIDs) {
		                // parse real index when custom PIDs are used 
		                // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
		                for(var j = 0; j < items.length; j++) {
		                    if(items[j].pid == index) {
		                        options.index = j;
		                        break;
		                    }
		                }
		            } else {
		                // in URL indexes start from 1
		                options.index = parseInt(index, 10) - 1;
		            }
		        } else {
		            options.index = parseInt(index, 10);
		        }
		
		        // exit if index not found
		        if( isNaN(options.index) ) {
		            return;
		        }
		
		        if(disableAnimation) {
		            options.showAnimationDuration = 0;
		        }
		
		        // Pass data to PhotoSwipe and initialize it
		        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		        gallery.init();
		    };
		
		    // loop through all gallery elements and bind events
		    var galleryElements = document.querySelectorAll( gallerySelector );
		
		    for(var i = 0, l = galleryElements.length; i < l; i++) {
		        galleryElements[i].setAttribute('data-pswp-uid', i+1);
		        galleryElements[i].onclick = onThumbnailsClick;
		    }
		
		    // Parse URL and open gallery if it contains #&pid=3&gid=1
		    var hashData = photoswipeParseHash();
		    if(hashData.pid && hashData.gid) {
		        openPhotoSwipe( hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true );
		    }
		};
		
		// execute above function
		initPhotoSwipeFromDOM('.fund_pic ');
			
		</script>



	</body>
</html>