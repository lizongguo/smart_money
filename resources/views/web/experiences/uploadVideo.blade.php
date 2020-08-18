@extends('layouts.web')
@section('content')
    <script src="{{asset('/js/ajaxfileupload.js')}}"></script>
    <script src="{{asset('/js/new/RecordRTC/RecordRTC.js')}}"></script>
    <!-- ../libs/DBML.js to fix video seeking issues -->
    <script src="{{asset('/js/new/RecordRTC/EBML.js')}}"></script>
    <!-- for Edge/FF/Chrome/Opera/etc. getUserMedia support -->
    <script src="{{asset('/js/new/adapter-latest.js')}}"></script>
    <script src="{{asset('/js/new/RecordRTC/DetectRTC.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/new/common.js')}}?v={{date('Y-m-d')}}"></script>

    <style>
        .video_time
        {
            text-align: center;margin-bottom: 20px;
            margin-top: 30px;
        }
        .video_time1
        {
            text-align: center;margin-bottom: 20px;
        }
        @media (min-width: 1025px){
            .video_time
            {
                display: none;
            }
        }
        @media (max-width: 1024px){
            .video_time1
            {
                display: none;
            }
        }
    </style>
    <div class="main" id="app">
        <div class="upload_video_body" v-if="!is_support">
            <div class="my_title_line">
                <a href="javascript:history.go(-1);" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i>戻る</a>
                <p style="text-align: center;color: #1d398b;padding-top: 10px;padding-right: 55px;">自己紹介映像のアップロード</p>
                </div>
            <div class="no_support_text"style="display: none;">
{{--                <p>大変申し訳ございませんが、映像録画機能が該当ブラウザに対応していません。</p>--}}
{{--                <p>ローカルで自己紹介の映像を撮影してからここでアップロードしてください。</p>--}}
{{--                <p>現在のブラウザはオンラインビデオの録画に対応していません。Chromeブラウザを変えて録画してください。</p>--}}
{{--                <p>または直接ビデオファイルをアップロードしてください。 </p>--}}
                
            </div>

            <p class="upload_sucess" v-if="complate"><i class="fa fa-check-circle" aria-hidden="true"></i> アップロード完了しました</p>
            <div class="my_item_input_file">
                <div class="input_text video_text" v-if="showFile"><{showFile}></div>
                <div class="input_text video_text" style="color: #777" v-else>MP4フォーマットを100MB以内</div>
                <input type="button" class="upload_btn active" value="ファイル選択" style="width: 130px" />
                <input type="file"  name="attachment" @change="onFileChange" class="upload_file my_video" id="my_video" />
            </div>
            <span v-if="file" class="video_upload_btn" @click="uploadVideoFile"><i class="fa fa-upload" aria-hidden="true"></i>アップロード</span>
            <div class="video_content">
                <div class="video_box"><video v-if="video_path!=''" autoplay playsinline width="100%" height="auto" class="video_preview" :src="video_path" controls="controls" style="display: block"></video></div>
                <div class="video_btn">
                    <div class="video_btn_box">
                        <span v-if="file" class="video_upload_btn" @click="uploadVideoFile"><i class="fa fa-upload" aria-hidden="true"></i>アップロード</span>
                        <p class="upload_sucess" v-if="complate"><i class="fa fa-check-circle" aria-hidden="true"></i> アップロード完了しました</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="recording_body" v-if="is_support">
            <div class="my_title_line"><a href="{{route('web.experiences.my')}}" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a></div>
            <div class="video_content">
                <div class="video_box">
                    <video autoplay id="showVideo" playsinline width="100%" height="auto" :src="video_path" controls="controls"></video>
                </div>
                <div class="video_btn">
                    <template v-if="step==2">
                        <div class="video_time" style="display: block">映像は1分間以内録画してください。 　   <span><{showTime}></span>/<span><{time-1}></span>s</div>
                    </template>
                        <div class="video_btn_box">
                        <template v-if="step==1">
                            <span class="video_start_btn" @click="startVedio"><i class="fa fa-video-camera" aria-hidden="true"></i>録音</span>
                        </template>
                        <template v-if="step==2">
                            <span class="video_stop_btn" @click="stopVideo"><i class="fa fa-stop-circle-o" aria-hidden="true"></i>録音</span>
{{--                            <span class="video_start_btn" @click="alignVedio"><i class="fa fa-reply" aria-hidden="true"></i>再録音</span>--}}
                        </template>
                        <template v-if="step==3">
                            <span class="video_start_btn" @click="alignVedio"><i class="fa fa-reply" aria-hidden="true"></i>再録音</span>
                            <span class="video_upload_btn" @click="uploadVideo"><i class="fa fa-upload" aria-hidden="true"></i>アップロード</span>
                        </template>
                        <template v-if="step==4">
                            <span class="video_start_btn" @click="alignVedio"><i class="fa fa-reply" aria-hidden="true"></i>再録音</span>
                            <p class="upload_sucess"><i class="fa fa-check-circle" aria-hidden="true"></i> アップロード完了しました</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var base = {
            url: {
                create: "{{URL::route('web.experiences.storeVideo')}}", //save
                upload: "{{ URL::route('upload.save',['object' => 'video']) }}"
            },
            upload: {{$upload}},
            item: @if($experience){!! json_encode($experience) !!}@else null @endif
        };
    </script>
    <script src="{{asset('/js/new/vuePage/uploadVideo.js')}}?v={{ date('YmdHis') }}"></script>
@endsection