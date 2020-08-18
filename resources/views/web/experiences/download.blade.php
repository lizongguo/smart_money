@extends('layouts.web')
@section('content')
    <script type="text/javascript">
        $(function(){
            if (window.parent.downloadTips) {
                window.parent.downloadTips('{{$msg}}');
            } else{
                layer.open({
                    content: '{{$msg}}',
                    skin: 'msg',
                    time: 3
                });
            }
        })
    </script>
@endsection