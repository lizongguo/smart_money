<ol class="dd-list">
    @foreach($d[$pid] as $menu)
    <li class="dd-item" data-id="{{$menu->id}}">
        <div class="dd-handle">
            <i class="fa @if(!empty($menu->icon)){{$menu->icon}}@endif"></i>
            <strong>{{$menu->title}}</strong>
            @if(!empty($menu->uri))
            <a layer-href="{{asset($menu->uri)}}" class="dd-nodrag">{{$menu->uri}}</a>
            @endif
            <div class="dd-nodrag" style="float: right">
                <a href="javascript:;" title="删除" class="layuiadmin-btn-admin tree_branch_delete" data-type="del" data-id="{{$menu->id}}" style="float: right;margin-left:15px"><i class="layui-icon">&#xe640;</i></a>
                <a href="javascript:;" title="编辑" class="layuiadmin-btn-admin" data-type="edit" data-id="{{$menu->id}}" style="float: right; margin-left:15px"><i class="layui-icon">&#xe642;</i></a>
            </div>
        </div>
        @if(count($d[$menu->id]) > 0)
            @include('admin.menu.tree', ['d' => $d, 'pid' => $menu->id])
        @endif
    </li>
    @endforeach
</ol>