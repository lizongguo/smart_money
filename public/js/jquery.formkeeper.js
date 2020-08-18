/*
 *  formkeeper - v1.1.0
 *  Using localStorage backup and restore form data
 *  
 *
 *  Made by 
 *  Under NoLicense License
 */
// this plugin is based on https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
;(function (defaults, $, window, document, undefined) {

	// Default options
	var pluginName = "formKeeper";

	// The actual plugin constructor
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({
			backupForm: $.fn.formKeeper.backupForm,
			restoreForm: $.fn.formKeeper.restoreForm,

			backupData: $.fn.formKeeper.backupData,
			restoreData: $.fn.formKeeper.restoreData,
			clearData: $.fn.formKeeper.clearData
		}, defaults, options);

		if ("string" === typeof this.options.formId) {
			this.formId = this.options.formId;
		} else if ("string" === typeof $(this.element).data("formid")) {
			// $.data not retrieve HTML5 data-* https://api.jquery.com/jquery.data/
			this.formId =  $(this.element).data("formid");
		} else {
			this.formId = location.pathname.replace(/\//g, "_");
		}
		this.namespace = pluginName + "." + this.formId;
       

		this._defaults = defaults;
			
		this._name = pluginName;
        
		
		this.init();
	
	}

	// Plugin functions
	Plugin.prototype = {
		init: function () {
			var that = this;

			if (this.options.restoreAtInit) {
				$(function () {
					that.restore();
				});
			}

			if (this.options.backupAtLeave) {
				$(window).on("beforeunload." + this.namespace, function () {
					that.backup();
				});
			}

			if (this.options.clearOnSubmit) {
				this.element.on("submit." + this.namespace, function () {
					that.clear();
				});
			}
		},
		destroy: function () {
			if (this.options.backupAtLeave) {
				$(window).off("beforeunload." + this.namespace);
			}

			if (this.options.clearOnSubmit) {
				this.element.off("submit." + this.namespace);
			}
		},

		backup: function () {
			this.options.backupData(this.formId, this.options.backupForm(this.element));
			
		},

		restore: function () {
			var data = this.options.restoreData(this.formId);
			if (!!data) {
				this.options.restoreForm(this.element, data);
				
			}
		},

		clear: function () {
			this.options.clearData(this.formId);
		}
	};

	// extends $.fn
	$.fn[pluginName] = function (options) {
		var args = arguments;

		if (options === undefined || typeof options === "object") {
			return this.each(function () {
				if (!$.data(this, "plugin_" + pluginName)) {
					$.data(this, "plugin_" + pluginName,
					new Plugin( this, options ));
				}
			});
		} else if (typeof options === "string" && options[0] !== "_" && options !== "init") {
			var returns;
			this.each(function () {
				var instance = $.data(this, "plugin_" + pluginName);
				if (instance instanceof Plugin && typeof instance[options] === "function") {
					returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
				}

				if (options === "destroy") {
					$.data(this, "plugin_" + pluginName, null);
					
				}
			});

			return returns !== undefined ? returns : this;
		}
	};

	// form reader and saver
	$.fn[pluginName].backupForm = function (form) {
		var data = {};

		$(form).find(":input").each(function () {
			var input = $(this), name = input.prop("name");
			if (!name) {
				return;
			}

			if (input.is(":radio")) {
				if (!input.prop("checked")) {
					return;
				}

				data[name] = input.val();
				
			} else if (input.is(":checkbox")) {
				if (!input.prop("checked")) {
					return;
				}

				if (!data[name]) {
					data[name] = [];
				}
				data[name].push(input.val());
			} else {
				data[name] = input.val();
				
			}
		});

		return data;
		
	};

	$.fn[pluginName].restoreForm = function (form, data) {
		$(form).find(":input").each(function () {
			var input = $(this), name = input.prop("name");
			if (!name || !data[name]) {
				return;
			}

			if (input.is(":radio")) {
				if (data[name] === input.val()) {
					input.prop("checked", true);
					input.parent().addClass('checked');
					//var radio_id=input.attr('id');
					if ($("#radio1").prop("checked")==true){
					$('.select_list3').show();
					$('.select_middle').show();
					}
					if ($("#radio2").prop("checked")==true){
					$('.select_list4').show();
					$('.select_middle').show();
					}
					if ($("#radio3").prop("checked")==true){
					$('.select_list5').show();
					$('.select_middle').show();
					}
					
					
					if ($("#radio4").prop("checked")==true){
					$('.residence_select').show();
					$('.stagnate_select').show();	
					
					}
					if ($("#radio21").prop("checked")==true){
					$('.other_text1').show();	
					}
					if ($("#in_jp_radio").prop("checked")==true){
					$('.in_jp').show();	
					}
					if ($("#out_jp_radio").prop("checked")==true){
					$('.out_jp').show();	
					}
					if ($("#have").prop("checked")==true){
					$('.certificate_box_list').show();
					$('.certificate_add_btn').show();
					}
					
					if ($("#work_experience_have").prop("checked")==true){
					$('.experience_body').show();
					}

				}
			} else if (input.is(":checkbox")) {
				if (-1 !== data[name].indexOf(input.val())) {
					input.prop("checked", true);
					input.parent().addClass('checked');
					
					if ($(".checkbox_list #checkbox3").prop("checked")==true){
					$(".checkbox_list #checkbox1").parent().addClass('disabled');
					$(".checkbox_list #checkbox2").parent().addClass('disabled');
					}
					
					if ($("#checkbox20").prop("checked")==true){
					$('.other_text2').show();
					}
					if ($("#checkbox43").prop("checked")==true){
					$('.other_text3').show();
					}
					if ($("#checkbox59").prop("checked")==true){
					$('.other_text4').show();
					}
					
					
					
					
					if ($("#con_checkbox1").prop("checked")==true){
					$('#con_checkbox2').removeAttr('required');
					}
					if ($("#con_checkbox2").prop("checked")==true){
					$('#con_checkbox1').removeAttr('required');
					}
					if ($("#con_checkbox3").prop("checked")==true){
					$('#con_checkbox4').removeAttr('required');
					}
					if ($("#con_checkbox4").prop("checked")==true){
					$('#con_checkbox3').removeAttr('required');
					}
					if ($("#con_checkbox5").prop("checked")==true){
					$('#con_checkbox6').removeAttr('required');
					}
					if ($("#con_checkbox6").prop("checked")==true){
					$('#con_checkbox5').removeAttr('required');
					}
				}
			} else {
				input.val(data[name]);
			}
		});
		
	};

	// localStorage support
	$.fn[pluginName].supportLocalStorage = "object" === typeof window.localStorage;



	$.fn[pluginName].backupData = function (formId, data) {
		if (!$.fn[pluginName].supportLocalStorage) {
			return;
		}
        
		window.localStorage.setItem(pluginName + "." + formId, JSON.stringify(data));
		alert(stringify(data));
	};

	$.fn[pluginName].restoreData = function (formId) {
		return JSON.parse(window.localStorage.getItem(pluginName + "." + formId));

	};

	$.fn[pluginName].clearData = function (formId) {
		window.localStorage.removeItem(pluginName + "." + formId);
	};

})({
	
	restoreAtInit: true,
	backupAtLeave: true,
	clearOnSubmit: false
}, jQuery, window, document);
