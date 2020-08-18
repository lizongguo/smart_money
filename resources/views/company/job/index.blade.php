@extends('layouts.company')
@section('content')

    <div class="main">
        <div class="company_main">
            <div class="company_body">
                @include("company.common.user_menu")

                <div class="c_m_item">
                    <span class="c_m_add_btn"><a href="{{ route("company.job.input") }}"><i class="fa fa-plus" aria-hidden="true"></i> 求人票新規作成</a></span>
                    <span class="c_m_update_btn"><a href="javascript:void(0)" onclick="refresh()"><i class="fa fa-refresh" aria-hidden="true"></i> リスト更新</a></span>
                </div>
                <div class="company_management_main">
                    <table class="company_management_table">
                        <thead>
                            <tr class="company_management_title">
                                <th class="c_m_id">求人管理ID</th>
                                <th class="c_m_id">システム管理ID</th>
                                <th class="c_m_name">募集職種名</th>
                                <th class="c_m_state">状況</th>
                                {{--<th class="c_m_date">掲載期間</th>--}}
                                <th class="c_m_update">更新日</th>
                                <th class="c_m_set_up">作成日</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $v)
                                <tr>
                                    <td>
                                        {{ $v['account_code'] ? $v['account_code'] : $v['job_id'] }}
                                    </td>
                                    <td>
                                        {{ $v['job_code'] }}
                                    </td>
                                    <td>
                                        <p>
                                            <a href="{{ route("company.job.input", [$v['job_id']]) }}">{{ $v['job_name'] }}</a>
                                            <a class="view_btn" target="_blank" title="プレビュー" href="{{ route('job.detail', [$v->job_id]) . '?preview=1' }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        </p>
                                    </td>
                                    <td>
                                        @if($v['job_period_type'] == 1)
                                            @if($v['job_period_start'] <= date("Y-m-d") && $v['job_period_end'] >= date("Y-m-d"))
                                                掲載中
                                            @elseif($v['job_period_start'] > date("Y-m-d"))
                                                掲載準備中
                                            @elseif($v['job_period_end'] < date("Y-m-d"))
                                                掲載終了
                                            @endif
                                        @elseif($v['job_period_type'] == 2)
                                            掲載中
                                        @else
                                            掲載中止
                                        @endif
                                    </td>
                                    {{--<td class="c_m_date_text">--}}
                                        {{--@if($v['job_period_type'] == 1)--}}
                                            {{--<p>{{ $v['job_period_start'] }}</p><p>-</p><p>{{ $v['job_period_end'] }}</p>--}}
                                        {{--@elseif($v['job_period_type'] == 2)--}}
                                            {{--無期限--}}
                                        {{--@else--}}
                                            {{--掲載中止--}}
                                        {{--@endif--}}
                                    {{--</td>--}}
                                    <td>
                                        {{ date("Y-m-d", strtotime($v['updated_at'])) }}
                                    </td>
                                    <td>
                                        {{ date("Y-m-d", strtotime($v['created_at'])) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                {{ $list->appends(Request::all())->links('company.common.pagination') }}
                <div class="clear"></div>
            </div>

        </div>
    </div>

    <script>

        $(document).ready(function() {
            $('.company_management_table').dataTable({
                "searching": false,
                "paging": false,
                "lengthChange": false,
                "info": false,
                "columnDefs": [{
                    "targets": [2],
                    "orderable": false
                }],
                "order": [],
                "language": {
                    "emptyTable": "該当応募者がいません。",
                },
            });
        });
        
        function refresh() {
            window.location.reload();
        }

    </script>

@endsection