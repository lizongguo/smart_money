<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{$page_title ? $page_title : config('site.title')}}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=no">
		<link rel="stylesheet" type="text/css" href="/css/found/iconfont.css?v=0511"> 
		<link rel="stylesheet" type="text/css" href="/css/found/style.css?v=0511">
		<script type="text/javascript" src="/js/jquery-2.1.4.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/jQuery.autoIMG.js"></script>

		<link rel="stylesheet" type="text/css" href="/css/found/photoswipe.css?v=0511">
		<link rel="stylesheet" type="text/css" href="/css/found/default-skin/default-skin.css?v=0511">
		<script type="text/javascript" src="/js/photoswipe.min.js"></script>
		<script type="text/javascript" src="/js/photoswipe-ui-default.min.js"></script>
	</head>
	<body>
		<div class="main">
			<div class="top_bar">
				<div class="top_bar_left"><a href="javascript:history.back(-1);"><i class="iconfont icon-jiantou1"></i></a></div>
				<div class="top_bar_title">{{ $info->name }}</div>
				<div class="top_bar_right">
				</div>
			</div>

			@if($info->content_type==1)
				@if($filetype=='jpg'||$filetype=='png')
				<div class="fund_pic">
					<figure itemprop="associatedMedia" id="0" >
						<a href="{{ $info->path }}" itemprop="contentUrl" data-size="1000x1000">
							<img id="img0" itemprop="thumbnail"  alt="000" src="{{ $info->path }}"/>
						</a>
					</figure>
				</div>
				<a class="download_btn" href="{{ $url }}" download="000"><span><img src="/images/download.png"/></span><p>下载</p></a>
				@else
					<div class="doc_preview">
						<iframe src="http://view.xdocin.com/xdoc?_xdoc={{ $url }}" class="preview_iframe"></iframe>
						<a class="download_btn" href="{{ $url }}"><span><img src="/images/download.png"/></span><p>下载</p></a>
					</div>
				@endif
			@else
			<div class="inv_projects_details_con">
                <div class="inv_projects_details_con_text">{!! $info->content !!}</div>
            </div>
			@endif
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
