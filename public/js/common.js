//rem自适应
function resize() {
	var docEl = document.documentElement;
	var clientWidth = window.innerWidth;
	if (clientWidth <= 320) {
		//docEl.style.fontSize = '43px';
		docEl.style.fontSize = 100 * (clientWidth / 1080) + 'px';

	} else if (clientWidth >= 1025) {
		docEl.style.fontSize = '44.444444px';
		//docEl.style.fontSize = 100 * (clientWidth / 1080) + 'px';

	} else {
		docEl.style.fontSize = 100 * (clientWidth / 1080) + 'px';
	}
}
resize();


$(window).resize(function() {
	resize();
	autoimg();
	$('.item_box_list .img_box').autoIMG();

});







$(document).ready(function() {
	//图片等比缩放
	autoimg();
	$('.item_box_list .img_box').autoIMG();

    });
  
 
	
	


//图片等比缩放
function autoimg() {
	var imgheight1 = $(".item_box_list .img_box").width();
	

	$(".item_box_list .img_box").css('height', imgheight1);
	$(".item_box_list .img_box").css('line-height', imgheight1 + 'px');
	
}
