@extends('layouts.web')
@section('content')
    <div class="main">
        <div class="my_main">
            <div class="my_body">
                @include('web.common.user_menu')

                <div class="my_my_application_main">
                    <table class="my_fav_table">
                        <tr class="my_application_title">
                            <th class="my_fav_name_item">職種名</th>
                            <th class="my_fav_operation">操作</th>
                        </tr>
                        @if(isset($list) and count($list) > 0)
                            @foreach($list as $k => $v)
                                <tr>
                                    <td class="my_fav_name_text">
                                        <div class="fav_info"><a href="{{ route("job.detail", [$v['job_id']]) }}">{!! $v['position'] !!}</a></div>
                                    </td>

                                    <td>
                                        <p><a href="{{ route("web.job.record", [$v['job_id']]) }}" class="go_btn">応募する</a></p>
                                        <p><a class="del_btn" onclick="fav({{ $v['job_id'] }}, this)">削除する</a></p>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            
                        @endif
                    </table>
                    {{ $list->appends(Request::all())->links('web.common.pagination') }}
                    <div class="clear"></div>
                </div>

            </div>

        </div>
    </div>

    <script>

        var userId = "{{$userInfo->id}}";

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });

        function fav(job_id, obj) {
            layer.open({
                content: '本当に削除しますか？'
                ,btn: ['はい', 'いいえ']
                ,yes: function(index){
                    layer.close(index);
                    $.ajax({
                        type: "post",
                        url: "{{ route('web.favorite.favorite') }}",
                        dataType: "json",
                        data: {'user_id': userId, 'job_id': job_id},
                        success: function(content){
                            layer.open({
                                content: "削除に成功しました。",
                                skin: 'msg',
                                time: 2
                            });
                            $(obj).parent("p").parent("td").parent("tr").remove();
                        },
                        error: function (err){
                            console.log(err);
                        }
                    });
                }
            });
        }

    </script>

@endsection