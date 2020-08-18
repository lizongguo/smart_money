function addStreamStopListener(stream, callback) {
    stream.addEventListener('ended', function () {
        callback();
        callback = function () {
        };
    }, false);
    stream.addEventListener('inactive', function () {
        callback();
        callback = function () {
        };
    }, false);
    stream.getTracks().forEach(function (track) {
        track.addEventListener('ended', function () {
            callback();
            callback = function () {
            };
        }, false);
        track.addEventListener('inactive', function () {
            callback();
            callback = function () {
            };
        }, false);
    });
}

if (navigator.mediaDevices === undefined) {
    navigator.mediaDevices = {};
}

if (navigator.mediaDevices.getUserMedia === undefined) {
    navigator.mediaDevices.getUserMedia = function (constraints) {

        // 首先，如果有getUserMedia的话，就获得它
        var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

        // 一些浏览器根本没实现它 - 那么就返回一个error到promise的reject来保持一个统一的接口
        if (!getUserMedia) {
            return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
        }

        // 否则，为老的navigator.getUserMedia方法包裹一个Promise
        return new Promise(function (resolve, reject) {
            getUserMedia.call(navigator, constraints, resolve, reject);
        });
    }
}

function getBrowserInfo() {
    var Sys = {
        browser: 'other',
        version: ''
    };
    var ua = navigator.userAgent.toLowerCase();

    var isIE = ua.indexOf("compatible") > -1 && ua.indexOf("msie") > -1; //判断是否IE<11浏览器
    var isEdge = ua.indexOf("edge") > -1 && !isIE; //判断是否IE的Edge浏览器
    var isIE11 = ua.indexOf('trident') > -1 && ua.indexOf("rv:11") > -1;
    if (isIE || isIE11 || isEdge) {
        var re = /(edge|msie|rv).*?([\d.]+)/i;
        var m = ua.match(re);
        if (!!m) {
            m[1] = m[1] != "edge" ? 'ie' : 'edge';
        } else {
            m = ['', 'ie', ''];
        }
    } else {
        var re = /(firefox|chrome|opera|version).*?([\d.]+)/;
        var m = ua.match(re);
    }
    if (!!m) {
        Sys.browser = m[1].replace(/version/, "safari");
        Sys.version = m[2];
    }
    return Sys;
}

function getFileObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL != undefined && window.URL.createObjectURL != undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

var app = new Vue({
    delimiters: ['<{', '}>'],
    el: '#app',
    data: {
        step: 1,
        video_url: base.item ? base.item.video_url : '',
        video_path: '',
        video_bold: null,
        video: null,
        recorder: null,
        cameraObj: null,
        stream: null,
        is_support: base.upload > 0 ? false : true,
        loading: null,
        time: 61,
        commonConfig: {},
        timer: null,
        showTime: 60,
        file: null,
        showFile: null,
        complate: false
    },
    computed: {},
    mounted: function () {
        var _this = this;
        _this.checkMobile();
        if (_this.video_url) {
            _this.video_path = _this.video_url;
        }
        _this.video = document.querySelector('video');
        var commonconfig = {
            onMediaCaptured: function (stream) {
                _this.stream = stream;
            },
            onMediaStopped: function () {
                if (_this.stream && _this.stream.stop) {
                    _this.stream.stop();
                } else if (_this.stream instanceof Array) {
                    _this.stream.forEach(function (stream) {
                        stream.stop();
                    });
                }
                _this.stream = null;
            },
            onMediaCapturingFailed: function (error) {
                console.error('onMediaCapturingFailed:', error);
                if (error.toString().indexOf('no audio or video tracks available') !== -1) {
                    _this.is_support = false;
                    // layer.open({
                    //     content: '利用可能なオーディオまたはビデオトラックがないため、RecordRTCを開始できませんでした。'
                    //     , btn: 'OK'
                    //     , yes: function () {
                    //         _this.is_support = false;
                    //     }
                    //     , time: 3
                    // });
                } else if (error.name === 'PermissionDeniedError' && DetectRTC.browser.name === 'Firefox') {
                    _this.is_support = false;
                    // layer.open({
                    //     content: 'Firefoxには、52以上のバージョンが必要です。FirefoxにはHTTPも必要です。'
                    //     , btn: 'OK'
                    //     , yes: function () {
                    //         _this.is_support = false;
                    //     }
                    //     , time: 3
                    // });
                } else {
                    _this.is_support = false;
                    // layer.open({
                    //     content: 'ブラウザはビデオの録画をサポートしていません。'
                    //     , btn: 'OK'
                    //     , yes: function () {
                    //         _this.is_support = false;
                    //     }
                    //     , time: 3
                    // });
                }
                this.onMediaStopped();
                _this.is_support = false;
            }
        }
        _this.commonConfig = commonconfig;
    },
    methods: {
        //获取授权
        captureCamera: function (callback) {
            var _this = this;
            navigator.mediaDevices.getUserMedia({audio: true, video: true}).then(function (audioVideoStream) {
                _this.commonConfig.onMediaCaptured(audioVideoStream);
                if (audioVideoStream instanceof Array) {
                    audioVideoStream.forEach(function (stream) {
                        addStreamStopListener(stream, function () {
                            _this.commonConfig.onMediaStopped();
                        });
                    });
                    return;
                }
                addStreamStopListener(audioVideoStream, function () {
                    _this.commonConfig.onMediaStopped();
                });
                callback(audioVideoStream);
            }).catch(function (error) {
                console.error(error);
                _this.commonConfig.onMediaCapturingFailed(error);
            });
        },
        //检测手机浏览器
        checkMobile: function () {
            var _this = this;
            //获取当前的浏览器信息
            console.log(navigator.userAgent);
            var sys = getBrowserInfo();
            console.log("browser:"+sys.browser + " version:" + sys.version);
            // if ($.inArray(sys.browser, ['chrome', 'firefox']) < 0) {
            //     layer.open({
            //         content: 'ブラウザはビデオの録画をサポートしていません。'
            //         , btn: 'OK'
            //         , end: function () {
            //         }
            //         , time: 3
            //     });
            //     _this.is_support = false;
            // }
        },
        //开始录音
        startVedio: function () {
            var _this = this;
            this.captureCamera(function (camera) {
                _this.video.muted = true;
                _this.video.volume = 0;
                _this.video.srcObject = camera;
                _this.recorder = RecordRTC(camera, {
                    type: 'video'
                });
                _this.recorder.startRecording();
                _this.recorder.camera = camera;
                _this.step = 2;
                _this.showTime = _this.time - 1;
                _this.timer = setInterval(function () {
                    _this.showTime = _this.showTime - 1;
                    if (_this.showTime <= 0) {
                        _this.stopVideo();
                    }
                }, 1000);
            });
        },
        //关闭定时器
        clearTimer: function () {
            if (this.timer) {
                console.log('stop timer');
                clearInterval(this.timer)
            }
        },
        //再次录音
        alignVedio: function () {
            var _this = this;
            // _this.video.play();
            if (!_this.recorder) {
                _this.step = 1;
                return;
            }
            _this.clearTimer();
            _this.clearCamera();
            _this.recorder.stopRecording();
            _this.recorder.destroy();
            _this.recorder = null;
            _this.step = 1;
        },
        //停止录视频
        stopVideo: function () {
            var _this = this;
            _this.clearTimer();
            if (!_this.recorder) {
                _this.step = 1;
                return;
            }
            try {
                _this.recorder.stopRecording(_this.stopCallback);
            } catch (e) {
                _this.clearCamera();
                _this.is_support = false;
                return;
            }
        },
        clearCamera: function () {
            var _this = this;
            if (!_this.recorder) {
                return;
            }
            if (_this.recorder.camera && _this.recorder.camera.stop) {
                _this.recorder.camera.stop();
            } else if (_this.recorder.camera instanceof Array) {
                _this.recorder.camera.forEach(function (stream) {
                    stream.stop();
                });
            }
            _this.recorder.camera = null;
        },
        stopCallback: function () {
            var _this = this;
            try {
                _this.video.src = _this.video.srcObject = null;
                _this.video.muted = false;
                _this.video.volume = 1;
                _this.video.src = getFileObjectURL(_this.recorder.getBlob());
                // $('#showVideo').removeAttr('autoplay');
                _this.video.pause();
                _this.clearCamera();
            } catch (e) {
                _this.clearCamera();
                _this.is_support = false;
                return;
            }
            _this.step = 3;
        },
        uploadVideo: function () {
            var _this = this;
            if (!_this.recorder) {
                _this.step = 1;
                return;
            }
            console.log("upload start");

            _this.loading = layer.open({
                type: 2
                , content: 'アップロード中……',
                shadeClose: false,
                shade: "background-color: rgba(0,0,0,.3)"
            });
            try {
                _this.uploadToServer(_this.recorder, function (progress, fileURL) {
                    console.log(progress, fileURL);
                    if (progress === 'ended') {
                        _this.recorder.destroy();
                        _this.recorder = null;
                        _this.video_url = fileURL;
                        console.log(fileURL);
                        _this.submitData();
                        return;
                    }
                });
            } catch (e) {
                _this.clearCamera();
                if (_this.recorder && _this.recorder.destroy) {
                    _this.recorder.destroy();
                }
                _this.recorder = null;
                _this.is_support = false;
                _this.closeLoding();
                return;
            }
        },
        closeLoding: function () {
            console.log('close xxx', this.loading);
            if (this.loading !== null) {
                layer.close(this.loading);
                this.loading = null;
            }
        },
        uploadToServer: function (recordRTC, callback) {
            var _this = this;
            var blob = recordRTC instanceof Blob ? recordRTC : recordRTC.blob;
            var fileType = blob.type.split('/')[0] || 'audio';
            var fileName = (Math.random() * 1000).toString().replace('.', '');

            function getRandomString() {
                if (window.crypto && window.crypto.getRandomValues && navigator.userAgent.indexOf('Safari') === -1) {
                    var a = window.crypto.getRandomValues(new Uint32Array(3)),
                        token = '';
                    for (var i = 0, l = a.length; i < l; i++) {
                        token += a[i].toString(36);
                    }
                    return token;
                } else {
                    return (Math.random() * new Date().getTime()).toString(36).replace(/\./g, '');
                }
            }

            function getFileName(fileExtension) {
                var d = new Date();
                var year = d.getUTCFullYear();
                var month = d.getUTCMonth();
                var date = d.getUTCDate();
                return 'RecordRTC-' + year + month + date + '-' + getRandomString() + '.' + fileExtension;
            }

            blob = new File([blob], getFileName('webm'), {
                type: "video/webm"
            });

            // create FormData
            var formData = new FormData();
            formData.append('filename', fileName);
            formData.append('attachment', blob);

            callback('Uploading ' + fileType + ' recording to server.');

            var upload_url = base.url.upload;

            _this.makeXMLHttpRequest(upload_url, formData, function (progress, data) {
                if (progress !== 'upload-ended') {
                    callback(progress);
                    return;
                }
                callback('ended', data.file_path);
            });
        },
        makeXMLHttpRequest: function (url, data, callback) {
            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var d = JSON.parse(request.responseText);
                    console.log(d);
                    callback('upload-ended', d.data);
                }
            };
            request.upload.onloadstart = function () {
                callback('Upload started...');
            };

            request.upload.onprogress = function (event) {
                callback('Upload Progress ' + Math.round(event.loaded / event.total * 100) + "%");
            };

            request.upload.onload = function () {
                callback('progress-about-to-end');
            };

            request.upload.onload = function () {
                callback('progress-ended');
            };

            request.upload.onerror = function (error) {
                callback('Failed to upload to server');
                console.error('XMLHttpRequest failed', error);
            };
            request.upload.onabort = function (error) {
                callback('Upload aborted.');
                console.error('XMLHttpRequest aborted', error);
            };

            request.open('POST', url);
            request.send(data);
        },
        //保存数据
        submitData: function () {
            var _this = this;
            //数据保存
            ajaxApi(base.url.create, {video_url: _this.video_url}, function (d) {
                if (d.status == 200) {
                    if (_this.is_support) {
                        _this.step = 4;
                    } else {
                        _this.complate = true;
                    }
                    _this.video.play();
                    layer.open({
                        content: d.msg,
                        skin: 'msg',
                        time: 2
                    });
                } else {
                    layer.open({
                        content: d.msg,
                        skin: 'msg',
                        time: 3
                    });
                    _this.complate = false;
                    return false;
                }
                _this.closeLoding();
            }, function (d) {
                _this.complate = false;
                _this.closeLoding();
            });
        },
        onFileChange: function (e) {
            var _this = this;
            var files = e.target.files || e.dataTransfer.files;
            if (typeof files[0] == 'undefined') {
                this.file = null;
                return;
            }
            var name = files[0].name;
            if (!(/\.(webm|mp4|ogg|mov)$/i.test(name))) {
                layer.open({
                    content: "アップロードフォーマットが間違っています。以下のタイプのビデオを選択してください。webm、mp4、ogg、mov。",
                    skin: 'msg',
                    time: 3,
                    end: function (i) {
                        //关闭后的操作
                        _this.video_url = !!base.item ? base.item.video_url : '';
                    }
                });
                return;
            }
            _this.createImage(files[0]);
            _this.video_path = getFileObjectURL(files[0]);
            this.showPhoto = $("#my_video").val();
            this.file = e;
            _this.showFile = this.showPhoto;
            var fileElementId = e.target.id;

            $(document).off('change', '#' + fileElementId).on('change', '#' + fileElementId, function (e) {
                _this.onFileChange(e);
            });
        },
        uploadVideoFile: function () {
            var _this = this;
            if (!_this.file) {
                return;
            }

            var fileElementId = this.file.target.id;
            _this.loading = layer.open({
                type: 2
                , content: 'アップロード中……',
                shadeClose: false,
                shade: "background-color: rgba(0,0,0,.3)"
            });
            $.ajaxFileUpload({
                url: base.url.upload,
                secureuri: false,
                fileElementId: fileElementId, //文件上传域的ID，这里是input的ID，而不是img的
                dataType: 'json', //返回值类型 一般设置为json
                type: 'post',
                async: false,
                success: function (data) {
                    if (data.status == 200) {
                        //设置图片地址
                        _this.video_url = data.data.file_path;
                        _this.video_path = data.data.file_path;
                        _this.showFile = data.data.file_path;
                        _this.submitData();
                    } else {
                        layer.open({
                            content: data.msg,
                            skin: 'msg',
                            time: 2,
                            end: function () {
                                //关闭后的操作
                                _this.video_url = !!base.item ? base.item.video_url : '';
                            }
                        });
                    }
                    _this.file = null;
                    _this.closeLoding();
                },
                error: function (err) {
                    console.log(err);
                    _this.closeLoding();
                }
            });
        },
        createImage: function (file) {
            var reader = new FileReader();
            var _this = this;
            reader.onload = function (e) {
                _this.video_path = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
});