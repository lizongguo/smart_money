ELEMENT.locale(ELEMENT.lang.ja);
var app = new Vue({
    delimiters: ['<{', '}>'],
    el: '#app',
    data: {
        edit: {
            "id": 0,
            "name": "",
            "name_kana": "",
            "birthday": "",
            "sex": "1",
            "nationality_id": "0",
            "is_nationality": false,
            "nationality": "",
            "address": "1",
            "address_id": 0,
            "address_other": "",
            "address_kana": "",
            "postal_code": "",
            "cell_phone": "",
            "out_cell_phone":"",
            "emergency_contact": "1",
            "emergency_address_id": 0,
            "emergency_address_other": "",
            "emergency_address_kana": "",
            "emergency_postal_code": "",
            "emergency_cell_phone": "",
            "nearest_station": "",
            "email": "",
            "residence_in_japan_year": 0,
            "residence_in_japan_month": 0,
            "photo": "",
            "visa_type": 0,
            "visa_other": "",
            "visa_term": "",
            "video_url": "",
            "pdf_url":"",
            "academic_background": [{
                'country': '',
                'final_education': 0,
                'enrol_date_start': '',
                'enrol_date_end': '',
                'school_name': '',
                'department_name': '',
                'subject_name': '',
                'science_art': ''
            }],
            "is_qualification_and_license": "0",
            "qualification_and_license": [], // json
            'jp_level': "0",
            'en_level': "0",
            "language_skill": [], // json
            "it_skill_os": [],// ,
            "it_skill_office": [],// ,
            "it_skill_graphic": [],// json
            "it_skill_language": [],// json
            "it_skill_db": [],// json
            "it_skill_framework": [], // json
            "commuting_hours": "2",
            "family_members_num": "1",
            "is_spouse": "0",
            "is_spouse_support": "0",
            "desired_place": [], //,
            "desired_place_ids": [], //,
            "pr_other": "",
            "other_expected_types": "",
            "is_experience": "0",
            "job_summary": "",
            "experiences": [] // json
        },
        hideDesiredPlace: false,
        pop1: false,
        showPhoto: '',
        file: null,
        japan_month_disable: false
    },
    computed: {},
    mounted: function () {
        var _this = this;
        if (!!base.item) {
            _this.edit = base.item;
        }

        if (_this.edit.academic_background.length < 1) {
            _this.edit.academic_background.push({
                'country': '',
                'final_education': 0,
                'enrol_date_start': '',
                'enrol_date_end': '',
                'school_name': '',
                'department_name': '',
                'subject_name': '',
                'science_art': ''
            });
            _this.$nextTick(function () {
            })
        }
        if (_this.edit.qualification_and_license.length < 1) {
            _this.edit.qualification_and_license.push({
                'name': 0,
                'level': 0,
                'point': "",
                'certificate_other': '',
                'certificate_date': ''
            });
        }
        if (_this.edit.language_skill.length < 1) {
            _this.edit.language_skill.push({
                'other_level': "0",
                'other_text': ''
            });
        }

        var it_skill = [
            "it_skill_graphic",
            "it_skill_language",
            "it_skill_db",
            "it_skill_framework"
        ];
        for (var i = 0; i < it_skill.length; i++) {
            if (_this.edit[it_skill[i]].length < 1) {
                _this.edit[it_skill[i]].push({
                    'name': '',
                    'other': '',
                    'year': '',
                    'month': ''
                });
            }
        }

        if (_this.edit.experiences.length < 1) {
            _this.edit.experiences.push({
                'country': '',
                'start_date': '',
                'end_date': '',
                'is_now': false,
                'corporate_name': '',
                'post_name': '',
                'industry_name': '',
                'industry_other': '',
                'employees_num': '',
                'employ_type': '',
                'occupation': '',
                'occupation_other': '',
                'annual_income': '',
                'undertake_business': ''
            });
            _this.$nextTick(function () {
            })
        }
        _this.edit.sphoto = _this.edit.photo ? _this.edit.photo : '';
        _this.showPhoto = _this.edit.photo ? _this.edit.photo : '';

        if (_this.edit.commuting_hours == '0') {
            _this.edit.commuting_hours = '2';
        }
        if (_this.edit.family_members_num == '0') {
            _this.edit.family_members_num = '1';
        }
        _this.changeHideDesiredPlace();

        _this.$forceUpdate();
        console.log(_this.edit);
        // 表单验证
        $("#testForm").html5Validate(function () {
            console.log(app.edit);
            _this.submitData();
            return false;
        }, {
            // novalidate: false
        });
        _this.autoText();
        _this.changeYearSelect();
    },
    methods: {
        autoText: function(){
            this.$nextTick(function () {
                $("textarea").autoHeightTextarea();
            })
        },
        GMTToStr: function(time){
            var date = new Date(time)
            var Str=date.getFullYear() + '-' +
                (date.getMonth() + 1) + '-' +
                date.getDate() + ' ' +
                date.getHours() + ':' +
                date.getMinutes() + ':' +
                date.getSeconds()
            return Str
        },
        StrToGMT:function(time){
            var GMT = new Date(time)
            return GMT
        },
        changeEmergencyContactValue:function(){
            if (this.edit.emergency_contact == 1) {
                this.edit.emergency_contact = 0;
            } else {
                this.edit.emergency_contact = 1;
            }
        },
        changeIsNow:function(i)
        {
            if (typeof this.edit.experiences[i] == 'undefined') {
                return ;
            }
            this.edit.experiences[i].is_now = this.edit.experiences[i].is_now.toString() == "1" ? "0" : "1";
            if (this.edit.experiences[i].is_now==1) {
                this.edit.experiences[i].end_date = base.today;
            }
        },
        addressChange:function(e) {
            var _this = this;
            _this.edit.address = e.target.value;
            _this.$forceUpdate();
        },
        changeHideDesiredPlace:function(){
            var _this = this;
            if ($.inArray("3", _this.edit.desired_place) >= 0) {
                _this.edit.desired_place = ['3'];
                _this.edit.desired_place_ids = [];
                _this.hideDesiredPlace = true;
            } else {
                _this.hideDesiredPlace = false;
            }
        },
        handleCheckedDesiredPlace:function(event, value){
            var _this = this;
            console.log(event, value);
            if (value == 3 && event) {
                _this.edit.desired_place = ["3"];
                _this.edit.desired_place_ids = [];
                _this.hideDesiredPlace = true;
            } else {
                _this.hideDesiredPlace = false;
            }
            _this.$forceUpdate();
        },

        //提交数据
        submitData: function () {
            var _this = this;
            //数据保存
            ajaxApi(base.url.create, _this.edit, function (d) {
                if (d.status == 200) {
                    layer.open({
                        content: d.msg,
                        skin: 'msg',
                        time: 2
                    });
                } else {
                    console.log(_this.edit.address);
                    for (var i in d.errors) {
                        iptname = i;
                        break;
                    }
                    strs = iptname.split(".");
                    if (strs.length==3) {
                        iptname = strs[2] + strs[1];
                    }
                    if (_this.edit.address == 2 && iptname == 'cell_phone') {
                        iptname = 'out_cell_phone';
                    }
                    var obj = $("input[name='"+iptname+"']");
                    obj.testRemind(d.msg);
                    obj.focus();
                    obj.select();
                    /*layer.open({
                        content: d.msg,
                        skin: 'msg',
                        time: 3
                    });*/
                }
                _this.$forceUpdate();
            });
        },
        setAttribute:function(k, v) {
            if (typeof this.edit[k] == 'undefined') {
                return;
            }
            this.edit[k] = v;
            this.$nextTick(function () {
                $("#testForm").html5Validate();
            })

            console.log(this.edit);
            this.$forceUpdate();
        },
        changeYear: function(k, i){
            if (typeof this.edit[k] == 'undefined') {
                return;
            }
            if (this.edit[k][i].year == 11)
            {console.log(this.edit[k][i].year);
                this.edit[k][i].month = 1;
            }
        },
        addAcademicBackground:function() {
            if (this.edit.academic_background.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            var templateAcademic = {
                'country': '',
                'final_education': 0,
                'enrol_date_start': '',
                'enrol_date_end': '',
                'school_name': '',
                'department_name': '',
                'subject_name': '',
                'science_art': '',
            };
            this.edit.academic_background.push(templateAcademic);
            this.$nextTick(function () {
                $("#testForm").html5Validate();
            });
            this.$forceUpdate();
        },
        addQualificationAndLicense:function() {
            if (this.edit.qualification_and_license.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            var templateAcademic = {
                'name': '',
                'level': 0,
                'point': "",
                'certificate_other': '',
                'certificate_date': '',
            };
            this.edit.qualification_and_license.push(templateAcademic);
            this.$nextTick(function () {
            });
            this.$forceUpdate();
        },
        addExperience:function() {
            if (this.edit.experiences.length >= 10) {
                showToast({
                    text: '10個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            var templateAcademic = {
                'country': '',
                'start_date': '',
                'end_date': '',
                'is_now': false,
                'corporate_name': '',
                'post_name': '',
                'industry_name': '',
                'industry_other': '',
                'employees_num': '',
                'employ_type': '',
                'occupation': '',
                'occupation_other': '',
                'annual_income': '',
                'undertake_business': ''
            };
            this.edit.experiences.push(templateAcademic);
            this.autoText();
            this.$forceUpdate();
        },
        addLanguageSkill:function() {
            if (this.edit.language_skill.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            var templateAcademic = {
                'other_level': "0",
                'other_text': '',
            };
            this.edit.language_skill.push(templateAcademic);
            this.$nextTick(function () {
            });
            this.$forceUpdate();
        },
        addItSkill:function(k) {
            if (k == 'it_skill_graphic' && this.edit.it_skill_graphic.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            if (k == 'it_skill_language' && this.edit.it_skill_language.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            if (k == 'it_skill_db' && this.edit.it_skill_db.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            if (k == 'it_skill_framework' && this.edit.it_skill_framework.length >= 5) {
                showToast({
                    text: '5個以内にしてください。',
                    bottom: '50%',
                    zindex: 2,
                    speed: 500,
                    time: 3000
                });
                return;
            }
            if (typeof this.edit[k] == 'undefined') {
                return;
            }
            var templateAcademic = {
                'name': '',
                'other': '',
                'year': '',
                'month': '',
            };
            this.edit[k].push(templateAcademic);
            this.$nextTick(function () {
            });
            this.$forceUpdate();
        },
        delObjItem: function(k, i) {
            var _this = this;
            if (typeof _this.edit[k] == "undefined") {
                return;
            }
            var items = _this.edit[k];
            if (items.length - 1 < i) {
                return;
            }
            showConfirm({
                text: '本当に削除しますか？',
                rightText: '　OK　',
                rightBgColor: '#1d398b',
                rightColor: '#fff',
                leftText: 'キャンセル',
                success: function () {
                    _this.edit[k].splice(i, 1);
                    _this.$forceUpdate();
                    _this.autoText();
                    showToast({
                        text: '削除に成功しました。',
                    })
                },
                cancel: function () {
                    //showToast({
                    //text:'调用了失败的回调函数！'
                    //})
                }
            });
        },
        onFileChange: function(e) {
            var _this = this;
            var files = e.target.files || e.dataTransfer.files;
            this.createImage(files[0]);
            if (typeof  files[0] == 'undefined') {
                this.file = null;
                return ;
            }
            this.showPhoto = $("#my_photo").val();
            this.file = e;

            var fileElementId = e.target.id;

            $(document).off('change','#' + fileElementId).on('change','#' + fileElementId, function(e){
                _this.onFileChange(e);
            });
        },
        uploadPhoto:function(){
            var _this = this;
            if (!_this.file) {
                return ;
            }
            if (!(/\.(png|jpeg|gif|jpg|bmp)$/i.test(_this.showPhoto))) {
                layer.open({
                    content: "アップロードフォーマットが間違っています。以下のタイプの写真を選択してください。png、jpeg、gif、jpg、bmp。",
                    skin: 'msg',
                    time: 3,
                    end: function(i){
                        //关闭后的操作
                        _this.edit.sphoto = !!base.item ? base.item.photo : ''
                        _this.showPhoto = !!base.item ? base.item.photo : ''
                    }
                });
                return ;
            }
            var fileElementId = this.file.target.id;
            $.ajaxFileUpload({
                url: base.url.upload,
                secureuri: false,
                fileElementId: fileElementId, //文件上传域的ID，这里是input的ID，而不是img的
                dataType: 'json', //返回值类型 一般设置为json
                type: 'post',
                async : false,
                success: function (data) {
                    if (data.status==200){
                        //设置图片地址
                        _this.edit.sphoto = data.data.thumb_path;
                        _this.edit.photo = data.data.thumb_path;
                        _this.showPhoto = data.data.thumb_path;
                        layer.open({
                            content: 'アップロード完了しました',
                            skin: 'msg',
                            time: 3,
                        });
                    } else {
                        layer.open({
                            content: '縦横4cm×3cmの5MB以内の顔写真データをご選択ください。',
                            skin: 'msg',
                            time: 2,
                            end: function(i){
                                //关闭后的操作
                                _this.edit.sphoto = !!base.item ? base.item.photo : ''
                                _this.showPhoto = !!base.item ? base.item.photo : ''
                            }
                        });
                    }
                    _this.file = null;
                },
                error: function (err){
                    console.log(err);
                }
            });
        },
        createImage: function(file) {
            var reader = new FileReader();
            var _this = this;
            reader.onload = function (e){
                _this.edit.sphoto = e.target.result;
                console.log(e, e.target.result);
                _this.$forceUpdate();
                $('#preview').css('background','none');
            };
            reader.readAsDataURL(file);
        },
        changeYearTitle: function()
        {
            var _this = this;
            if (this.edit.address == 2) {
                // $('.year_title').text("日本滞在年数");
                _this.edit.visa_type = 0;
                _this.edit.visa_other = '';
                _this.edit.visa_term = '';
            } else{
                // $('.year_title').text("来日年数");
            }
        },
        changeYearSelect: function() {
            var _this = this;
            if (_this.edit.residence_in_japan_year == 11) {
                _this.edit.residence_in_japan_month = 0;
                _this.japan_month_disable = true;
                $("#month_select").css('background', '#ccc');
            } else{
                _this.japan_month_disable = false;
                $("#month_select").css('background', '#fff');
            }
        },
        changeValue: function(k, v)
        {
            if (typeof this.edit[k] == 'undefined') {
                return;
            }
            this.edit[k] = v.toString();
        },
        downloadPdf: function()
        {
            var _this = this;
            //数据保存
            ajaxApi(base.url.download, {}, function (d) {
                if (d.status == 200) {
                    var pdf_url = d.data;
                    window.open(pdf_url);
                } else {
                    layer.open({
                        content: d.msg,
                        skin: 'msg',
                        time: 3
                    });
                    return false;
                }
            });
        },
        levelPage:function(url)
        {
            var _this = this;
            layer.open({
                content: 'このページを離れます。データを保存しましたか？'
                ,btn: ['はい', 'いいえ']
                ,yes: function(index){
                    layer.close(index);
                    window.location.href = url;
                }
                ,no: function(index){
                    layer.close(index);
                }
            });
            return false;
        },
        experienceChange(){
            var _this = this;
            // var zt = new $.Zebra_Tooltips($('.zebra_tips1'));
            if (_this.edit.is_experience == 1) {
                // $(".zebra_tips1").click(function() {
                //     zt.show($('.zebra_tips1'), true);
                // });
            }else{
                // $('.Zebra_Tooltip').remove();
            }
            _this.autoText();
        }
    }
});

// 表单本地保存
$(function () {
    //$('.my_introduce').popup();
});
function downloadTips(msg)
{
    layer.open({
        content: msg,
        skin: 'msg',
        time: 3
    });
}
function level(url)
{
    layer.open({
        content: 'このページを離れます。データを保存しましたか？'
        ,btn: ['はい', 'いいえ']
        ,yes: function(index){
            layer.close(index);
            window.location.href = url;
        }
        ,no: function(index){
            layer.close(index);
        }
    });
    return false;
}