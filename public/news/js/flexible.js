;(function flexible(window, document) {
	var docEl = document.documentElement
	var dpr = window.devicePixelRatio || 1
	// adjust body font size
	function setBodyFontSize() {
		if (document.body) {
			document.body.style.fontSize = (12 * dpr) + 'px'
		} else {
			document.addEventListener('DOMContentLoaded', setBodyFontSize)
		}
	}
	setBodyFontSize();
	// set 1rem = viewWidth / 10
// 	console.log(document.getElementsByTagName('html')[0]);
// 	alert('js设置字体大小之前的html字体大小：' + document.getElementsByTagName('html')[0].style.fontSize);
	function setRemUnit() {
		var rem = docEl.clientWidth / 10;
		console.log(docEl);
		// alert('手机屏幕宽度：' + rem*10 + 'px');
		docEl.style.fontSize = rem + 'px';
	}
	setRemUnit();
	// alert('js设置字体大小之后的html字体大小：' + document.getElementsByTagName('html')[0].style.fontSize);
	// reset rem unit on page resize
	window.addEventListener('resize', setRemUnit);
	window.addEventListener('pageshow', function(e) {
		if (e.persisted) {
			setRemUnit()
		}
	})

	// detect 0.5px supports
	if (dpr >= 2) {
		var fakeBody = document.createElement('body')
		var testElement = document.createElement('div')
		testElement.style.border = '.5px solid transparent'
		fakeBody.appendChild(testElement)
		docEl.appendChild(fakeBody)
		if (testElement.offsetHeight === 1) {
			docEl.classList.add('hairlines')
		}
		docEl.removeChild(fakeBody)
	}
}(window, document))
