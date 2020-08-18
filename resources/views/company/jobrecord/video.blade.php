@extends('layouts.company')
@section('content')

    <div class="main">
        <div class="recording_body">
            <div class="my_title_line"><a href="javascript:history.back()" class="back_btn"><i class="fa fa-chevron-left" aria-hidden="true"></i> 戻る</a></div>
            <div class="video_content" style="display: block;">
                <div class="video_box" style="width: auto">
                    <video width="100%" height="auto" src="{{ $video_url }}" controls="controls"></video>
                </div>
            </div>
        </div>
    </div>

@endsection