
<dl class="layui-nav-child">
    @foreach($d[$pid] as $one)
    @if(Auth::guard('admin')->user()->visible($one->roles) && (empty($one->permission_id) ?: Auth::guard('admin')->user()->canPermission($one->permission_id)))
    <dd data-name="menu_name_{{$one->id}}">
        <a @if($one->uri) lay-href="{{asset($one->uri)}}" @else href="javascript:;"  @endif lay-tips="{{$one->title}}">
            <i class="fa {{$one->icon}}"></i>
            {{$one->title}}
        </a>
        @if(count($data[$one->id]) > 0)
        @include('admin.index.child', ['d' => $d, 'pid' => $one->id])
        @endif
    </dd>
    @endif
    @endforeach
</dl>