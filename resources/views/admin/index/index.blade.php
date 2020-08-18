@extends('layouts.admin')
@section('content')
<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <!-- 头部区域 -->
            <ul class="layui-nav layui-layout-left">
                <li class="layui-nav-item layadmin-flexible" lay-unselect>
                    <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                        <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;" layadmin-event="refresh" title="刷新">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </a>
                </li>
<!--                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords="> 
                </li>-->
            </ul>
            <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
<!--                <li class="layui-nav-item" lay-unselect>
                        <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
                        <i class="layui-icon layui-icon-notice"></i>
                        如果有新消息，则显示小圆点 
                        <span class="layui-badge-dot"></span>
                    </a>
                </li>-->
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="theme">
                        <i class="layui-icon layui-icon-theme"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="note">
                        <i class="layui-icon layui-icon-note"></i>
                    </a>
                </li>
                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="fullscreen">
                        <i class="layui-icon layui-icon-screen-full"></i>
                    </a>
                </li>
                <li class="layui-nav-item" lay-unselect>
                    <a href="javascript:;">
                        <cite>{{$user['name']}}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="{{route('account.index')}}">基本资料</a></dd>
                        <dd><a lay-href="{{route('account.changePwd')}}">修改密码</a></dd>
                        <hr>
                        <dd layadmin-event="logout" style="text-align: center;"><a>退出</a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item layui-hide-xs" lay-unselect>
                    <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
                <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                    <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
                </li>
            </ul>
        </div>

        <!-- 侧边菜单 -->
        <div class="layui-side layui-side-menu">
            <div class="layui-side-scroll">
                <div class="layui-logo" data-name="console" lay-href="{{route('console.index')}}">
                    <span>{{config('site.sitename')}}</span>
                </div>
                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    <li class="menu-avatar">
                        <div class="layui-card-body text-center">
                            <div class="layadmin-avatar" style="margin: 0px auto 10px;">
                                <img src="{{asset($user['avatar'])}}" >
                            </div>
                            <h4 style="font-size: 16px;">{{$user['name']}}</h4>
                            @if(!empty($user['remarks']))
                            <p class="text-ellipsis" lay-tips="{{$user['remarks']}}" style="font-size: 12px;">{{ $user['remarks'] }}</p>
                            @endif
                        </div>
                    </li>
                    @foreach($data[0] as $k => $one)
                    @if(Auth::guard('admin')->user()->visible($one->roles) && (empty($one->permission_id) ?: Auth::guard('admin')->user()->canPermission($one->permission_id)))
                    <li data-name="menu_name_{{$one->id}}" class="layui-nav-item @if($k < 1) layui-nav-itemed @endif">
                        <a  @if($one->uri) lay-href="{{asset($one->uri)}}" @else href="javascript:;"  @endif  lay-tips="{{$one->title}}" lay-direction="2">
                            <i class="fa {{$one->icon}}"></i>
                            <cite>{{$one->title}}</cite>
                        </a>
                        @if(count($data[$one->id]) > 0)
                        @include('admin.index.child', ['d' => $data, 'pid' => $one->id])
                        @endif
                    </li>
                    @endif
                    @endforeach
                    <li data-name="logout" class="layui-nav-item">
                        <a href="javascript:;" layadmin-event="logout" lay-tips="退出" lay-direction="2">
                            <i class="fa fa-sign-out"></i>
                            <cite>退出登录</cite>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- 页面标签 -->
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="{{route('console.index')}}" lay-attr="{{route('console.index')}}" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>


        <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="{{route('console.index')}}" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script src="{{asset("/layuiadmin/layui/layui.js")}}"></script>
<script>
layui.config({
    uriHost: '{{asset("/admin")}}/', //项目管理端path
    base: '{{asset("/layuiadmin/")}}/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
}).use('index');
</script>
@endsection