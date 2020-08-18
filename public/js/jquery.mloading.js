/* Author：mingyuhisoft@163.com
 * Github:https://github.com/imingyu/jquery.mloading
 * Npm:npm install jquery.mloading.js
 * Date：2016-7-4
 */

;(function (root, factory) {
    'use strict';

    if (typeof module === 'object' && typeof module.exports === 'object') {
        factory(require('jquery'),root);
    } if(typeof define ==="function"){
        if(define.cmd){
            define(function(require, exports, module){
                var $ = require("jquery");
                factory($,root);
            });
        }else{
            define(["jquery"],function($){
                factory($,root);
            });
        }
    }else {
        factory(root.jQuery,root);
    }
} (typeof window !=="undefined" ? window : this, function ($, root, undefined) {
    'use strict';
    if(!$){
        $ = root.jQuery || null;
    }
    if(!$){
        throw new TypeError("必须引入jquery库方可正常使用！");
    }

    var arraySlice = Array.prototype.slice,
        comparison=function (obj1,obj2) {
            var result=true;
            for(var pro in obj1){
                if(obj1[pro] !== obj2[obj1]){
                    result=true;
                    break;
                }
            }
            return result;
        }

    function MLoading(dom,options) {
        options=options||{};
        this.dom=dom;
        this.options=$.extend(true,{},MLoading.defaultOptions,options);
        this.curtain=null;
        this.render().show();
    }
    MLoading.prototype={
        constructor:MLoading,
        initElement:function () {
            var dom=this.dom,
                ops=this.options;
            var curtainElement=dom.children(".mloading"),
                bodyElement = curtainElement.children('.mloading-body'),
                barElement = bodyElement.children('.mloading-bar'),
                iconElement = barElement.children('.mloading-icon'),
                textElement = barElement.find(".mloading-text");
            if (curtainElement.length == 0) {
                curtainElement = $('<div class="mloading"></div>');
                dom.append(curtainElement);
            }
            if (bodyElement.length == 0) {
                bodyElement = $('<div class="mloading-body"></div>');
                curtainElement.append(bodyElement);
            }
            if (barElement.length == 0) {
                barElement = $('<div class="mloading-bar"></div>');
                bodyElement.append(barElement);
            }
            if (iconElement.length == 0) {
                var _iconElement=document.createElement(ops.iconTag);
                iconElement = $(_iconElement);
                iconElement.addClass("mloading-icon");
                barElement.append(iconElement);
            }
            if (textElement.length == 0) {
                textElement = $('<span class="mloading-text"></span>');
                barElement.append(textElement);
            }
            
            this.curtainElement=curtainElement;
            this.bodyElement = bodyElement;
            this.barElement = barElement;
            this.iconElement = iconElement;
            this.textElement = textElement;
            return this;
        },
        render:function () {
            var dom=this.dom,
                ops=this.options;
            this.initElement();
            if(dom.is("html") || dom.is("body")){
                this.curtainElement.addClass("mloading-full");
            }else{
                this.curtainElement.removeClass("mloading-full");

                if(!dom.hasClass("mloading-container")){
                    dom.addClass("mloading-container");
                }
            }
            if(ops.mask){
                this.curtainElement.addClass("mloading-mask");
            }else{
                this.curtainElement.removeClass("mloading-mask");
            }
            if(ops.content!="" && typeof ops.content!="undefined"){
                if(ops.html){
                    this.bodyElement.html(ops.content);
                }else{
                    this.bodyElement.text(ops.content);
                }
            }else{
                this.iconElement.attr("src",ops.icon);
                if(ops.html){
                    this.textElement.html(ops.text);
                }else{
                    this.textElement.text(ops.text);
                }
            }

            return this;
        },
        setOptions:function (options) {
            options=options||{};
            var oldOptions = this.options;
            this.options = $.extend(true,{},this.options,options);
            if(!comparison(oldOptions,this.options)) this.render();
        },
        show:function () {
            var dom=this.dom,
                ops=this.options,
                barElement=this.barElement;
            this.curtainElement.addClass("active");
			$("body").addClass("loading_active");
            barElement.css({
                "marginTop":"-"+barElement.outerHeight()/2+"px",
                "marginLeft":"-"+barElement.outerWidth()/2+"px"
            });

            return this;
        },
        hide:function () {
            var dom=this.dom,
                ops=this.options;
            this.curtainElement.removeClass("active");
            if(!dom.is("html") && !dom.is("body")){
                dom.removeClass("mloading-container");
            }
            return this;
        },
        destroy:function () {
            var dom=this.dom,
                ops=this.options;
            this.curtainElement.remove();
			 $("body").removeClass("loading_active");
            if(!dom.is("html") && !dom.is("body")){
                dom.removeClass("mloading-container");
            }
            dom.removeData(MLoading.dataKey);
            return this;
        }
    };
    MLoading.dataKey="MLoading";
    MLoading.defaultOptions = {
        text:"loading...",
        iconTag:"img",
        icon:"data:image/gif;base64,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",
        html:false,
        content:"",//设置content后，text和icon设置将无效
        mask:true//是否显示遮罩（半透明背景）
    };

    $.fn.mLoading=function (options) {
        var ops={},
            funName="",
            funArgs=[];
        if(typeof options==="object"){
            ops = options;
        }else if(typeof options ==="string"){
            funName=options;
            funArgs = arraySlice.call(arguments).splice(0,1);
        }
        return this.each(function (i,element) {
            var dom = $(element),
                plsInc=dom.data(MLoading.dataKey);
            if(!plsInc){
                plsInc=new MLoading(dom,ops);
            }

            if(funName){
                var fun = plsInc[funName];
                if(typeof fun==="function"){
                    fun.apply(plsInc,funArgs);
                }
            }
        });
    }
}));

var st_timer = null;
$(function(){
	showInit();
});


function showInit(){
	//透明黑色背景
	checkShowInit = true;
	$("<div id='st_mask' onclick='closeMask()'></div>").appendTo("body").css({
		'width' : '100%',
		'height' : '100%',
		'background' : 'rgba(0,0,0,.4)',
		'position' : 'fixed',
		'left' : '0','top' : '0',
		'display' : 'none',
		'z-index' : '9996'
	});
	//--------------------------------在body最后添加Confirm的节点
	$("<div id='st_confirmBox'></div>").appendTo("body").css({
		'width' : '80%',
		'max-width' : '480px',
		'position' : 'fixed',
		'left' : '0',
		'right' : '0',
		'margin' : '0 auto',
		'top' : '34%',
		'text-align' : 'center',
		'display' : 'none',
		'z-index' : '9999',
	});
	$("<div id='st_confirm'></div>").appendTo("#st_confirmBox").css({
		'width' : '100%',
		'margin' : '0 auto',
		'background' : '#fff',
		'border-radius' : '10px',
		'overflow' : 'hidden',
		'padding' : '20px',
		'text-align' : 'center',
		'box-sizing' : 'border-box',

	});
	$("<span id='st_confirm_close'><i class='fa fa-times' aria-hidden='true'></i></span>").appendTo("#st_confirm").css({
		
		
		
	});
	$("<span id='st_confirm_text'></span>").appendTo("#st_confirm").css({
		'background' : '#fff',
		'overflow' : 'hidden',
		'text-align' : 'center',
		'display' : 'block',
		'padding' : '50px 30px',
		'font-size' : '18px',
		
	});
	$("<div id='st_confirm_btn_box'></div>").appendTo("#st_confirm").css({
		'width' : '100%',
		'text-align' : 'center',
		'display' : 'flex',
		
	
	});
	$("<span class='st_confirm_btn cancel'></span>").appendTo("#st_confirm_btn_box").css({
		'background' : '#fff',
		'color' : '#8d8d8d',
		'padding' : '10px',
		'text-align' : 'center',
		'display' : 'block',
		'margin' : '0 auto',
		'float' : 'left',
		'box-sizing' : 'border-box',
		'border' : '1px solid #cfcfcf',
		'overflow' : 'hidden',
		'text-overflow' : 'ellipsis',
		'white-space' : 'nowrap',
		'border-radius' : '40px'
	});
	$("<span class='st_confirm_btn success'></span>").appendTo("#st_confirm_btn_box").css({
		'background' : '#1b79f8',
		'color' : '#fff',
		'padding' : '10px',
		'text-align' : 'center',
		'display' : 'block',

		'margin' : '0 auto',
		'float' : 'left',
		'box-sizing' : 'border-box',
		'border-top' : '1px solid #1b79f8',
		'overflow' : 'hidden',
		'text-overflow' : 'ellipsis',
		'white-space' : 'nowrap',
		'border-radius' : '40px'
	});
	$("<div></div>").appendTo("#st_confirm").css({
		'clear' : 'both',
		'display' : 'block',
	});

	//--------------------------------在body最后添加Alert节点
	$("<div id='st_alertBox'></div>").appendTo("body").css({
		'width' : '100%',
		'position' : 'fixed',
		'left' : '0',
		'top' : '34%',
		'text-align' : 'center',
		'display' : 'none',
		'z-index' : '9999',
	});
	$("<div id='st_alert'></div>").appendTo("#st_alertBox").css({
		'width' : '80%',
		'margin' : '0 auto',
		'background' : '#fff',
		'border-radius' : '2px',
		'overflow' : 'hidden',
		'padding-top' : '20px',
		'text-align' : 'center',
	});
	$("<span id='st_alert_text'></span>").appendTo("#st_alert").css({
		'background' : '#fff',
		'overflow' : 'hidden',
		'padding-top' : '20px',
		'text-align' : 'center',
		'display' : 'block',
		'padding' : '15px 8px 30px',
	});
	$("<span id='st_alert_btn' onclick='closeMask()'></span>").appendTo("#st_alert").css({
		'background' : '#1b79f8',
		'color' : '#fff',
		'padding' : '8px',
		'text-align' : 'center',
		'display' : 'block',
		'width' : '72%',
		'margin' : '0 auto',
		'margin-bottom' : '20px',
		'border-radius' : '2px',
		'overflow' : 'hidden',
		'text-overflow' : 'ellipsis',
		'white-space' : 'nowrap'
	});

	//---------------------------------在body最后添加Toast节点
	$("<div id='st_toastBox'></div>").appendTo("body").css({
		'width' : '100%',
		'position' : 'fixed',
		'left' : '0',
		'bottom' : '50%',
		'text-align' : 'center',
		'display' : 'none',
		
	});
	$("<span id='st_toastContent'></span>").appendTo("#st_toastBox").css({
		'color' : '#fff',
		'background' : 'rgba(0,0,0,.8)',
		'padding' : '20px 40px',
		'font-weight' : 'bold',
		'border-radius' : '50px',
		'max-width' : '80%',
		'display' : 'inline-block'
	});

}
function showToast(obj){
	if(!obj.text){
		return false;
	}
	clearTimeout(st_timer);
	$('#st_toastBox').hide();

	var text = obj.text;
	var time = parseInt(obj.time ? obj.time : 2300);
	var speed = obj.speed ? obj.speed : 'normal';
	var bottom = obj.bottom ? obj.bottom : '50%';
	if(obj.zindex){
		var zindex = parseInt(obj.zindex);
		$('#st_mask').css({'z-index':zindex-1});
		$('#st_toastBox').css({'z-index' : 9999});
	}else{
		$('#st_mask').css({'z-index' : 9996});
		$('#st_toastBox').css({'z-index' : 9999});
	}

	$('#st_toastBox').css({'bottom' : bottom});

	$('#st_toastContent').text(text);
	$('#st_toastBox').fadeIn(speed);
	st_timer = setTimeout(function(){
		$('#st_toastBox').fadeOut();
	},time);
	
}

function showAlert(obj){
	
	if(!obj.text){
		return false;
	}else{
		var text = obj.text;
		var bgColor = obj.bgColor ? obj.bgColor : '#1b79f8';
		var color = obj.color ? obj.color : '#fff';
		var btnText = obj.btnText ? obj.btnText : '确定';
		var top = obj.top ? obj.top : '34%';

		if(obj.zindex){
			var zindex = parseInt(obj.zindex);
			$('#st_mask').css({'z-index':zindex-1});
			$('#st_alertBox').css({'z-index' : zindex});
		}else{
			$('#st_mask').css({'z-index' : 9996});
			$('#st_alertBox').css({'z-index' : 9999});
		}

		$('#st_alert_text').text(text);
		$('#st_alert_btn').css({'background' : bgColor});
		$('#st_alert_btn').css({'color':color});
		$('#st_alert_btn').text(btnText);
		$('#st_alertBox').css({'top' : top});
		$('#st_mask,#st_alertBox').show();

		if(obj.success){
			$('#st_alert_btn').off('click').on('click',function(){
				obj.success();
			});
		}
	}

}
function showConfirm(obj){
	if(!obj.text){
		return false;
	}
	var text = obj.text;
	var rightText = obj.rightText ? obj.rightText : '确定';
	var rightBgColor = obj.rightBgColor ? obj.rightBgColor : '#1b79f8';
	var rightColor = obj.rightColor ? obj.rightColor : '#fff';

	var leftText = obj.leftText ? obj.leftText : '取消';
	var top = obj.top ? obj.top : '34%';
	if(obj.zindex){
		var zindex = parseInt(obj.zindex);
		$('#st_mask').css({'z-index':zindex-1});
		$('#st_confirmBox').css({'z-index' : zindex});
	}else{
		$('#st_mask').css({'z-index' : 9996});
		$('#st_confirmBox').css({'z-index' : 9999});
	}

	$('#st_confirm_text').text(text);
	$('.st_confirm_btn.cancel').text(leftText);
	$('.st_confirm_btn.success').text(rightText);
	$('.st_confirm_btn.success').css({
		'background' : rightBgColor,
		'color' : rightColor,
		'border-top' : '1px solid '+rightBgColor,
	});
	$('#st_confirmBox').css({'top' : top});
	$('#st_mask,#st_confirmBox').show();

	if(obj.cancel){
		$('.st_confirm_btn.cancel,#st_confirm_close').off('click').on('click',function(){
			closeMask();
			obj.cancel();
		})
	}else{
		$('.st_confirm_btn.cancel,#st_confirm_close').off('click').on('click',function(){
			closeMask();
		});
	}
	if(obj.success){
		$('.st_confirm_btn.success').off('click').on('click',function(){
			closeMask();
			obj.success();
		})
	}else{
		$('.st_confirm_btn.success').off('click').on('click',function(){
			closeMask();
		});
	}
}


		

function closeMask(){
	$('#st_mask,#st_alertBox,#st_confirmBox').hide();
}
