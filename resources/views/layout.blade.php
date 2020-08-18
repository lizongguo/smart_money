<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>{{$code['site_title']}}</title>
    <meta content="{{$code['site_title']}}" name="description" />
    <meta content="kbftech" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{asset('assets/images/faviicon.png')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('assets/css/icons.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('assets/css/style.css')}}?v=1.1" type="text/css" rel="stylesheet"/>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>

    <script src="{{asset('assets/plugins/layer/layer.js')}}"></script>
    <style media="print" type="text/css">
        .button-items{display: none;}
    </style>
</head>


<body class="fixed-left">

<!-- Loader -->
<div id="preloader"><div id="status"><div class="spinner"></div></div></div>

<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
            <i class="ion-close"></i>
        </button>

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="text-center">
                <!--<a href="index.html" class="logo">Admiry</a>-->
                <a href="{{asset('/admin')}}" class="logo"><img src="{{asset('assets/images/index/logo-icon.png')}}" height="55" alt="logo"></a>
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">

            <div class="user-details">
                <div class="text-center">
                    <img src="@if($user['uicon']){{ asset($user['uicon']) }}@else{{asset('assets/images/users/avatar-0.jpg')}}@endif" alt="" class="rounded-circle">
                </div>
                <div class="user-info">
                    <h4 class="font-16 text-white">{{ $user['user_name'] }}</h4>
                </div>
            </div>

            <div id="sidebar-menu">
                <ul>
                    <li class="menu-title text-white"><h6>洋富柜儿管理平台</h6></li>
                    
                    <?php
                    if ($currentMenu) {
                        $menuKey = explode('|', $currentMenu);
                    }
                    ?>
                    @foreach($menu as $k => $m)
                        <?php 
                        if (isset($m['denyRole']) && array_search($user['role'], $m['denyRole']) !== false) {
                            continue;
                        }
                        ?>
                        <li class="@if(isset($m['menu']) && count($m['menu'])) has_sub @endif active @if($menuKey[0] == $k) nav-active @endif">
                            <a href="@if(isset($m['uri']) && !empty($m['uri'])){{ URL::route($m['uri']) }}@else{{'javascript:void(0);'}}@endif" class="waves-effect @if($menuKey[0] == $k) active @endif"><i class="{{ $m['icon'] }}"></i><span> {{ $m['name'] }} </span> @if(isset($m['menu']) && count($m['uri']))<span class="pull-right"><i class="mdi mdi-chevron-right"></i></span>@endif</a>
                            @if(isset($m['menu']) && count($m['menu']))
                            <ul class="list-unstyled" @if($menuKey[0] == $k) style="display: block;" @endif>
                                @foreach($m['menu'] as $v)
                                    <?php 
                                    if(isset($v['denyRole']) && array_search($user['role'], $v['denyRole']) !== false) {
                                        continue;
                                    }
                                    ?>
                                    <li @if($menuKey[1] == $v['uri']) class="active" @endif><a href="{{ URL::route($v['uri']) }}">{{ $v['name'] }}</a></li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->

    <!-- Start right Content here -->

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="topbar">
                <nav class="navbar-custom">
                    <ul class="list-inline menu-left mb-0">
                        <li class="list-inline-item">
                            <button type="button" class="button-menu-mobile open-left waves-effect">
                                <i class="ion-navicon"></i>
                            </button>
                        </li>
                        <li class="hide-phone list-inline-item app-search">
                            <h3 class="page-title">{{$code['site_title']}}</h3>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </nav>
            </div>
            <!-- Top Bar Start -->
            <!-- Top Bar End -->

            @yield('content')
            <div class="modal fade" id="_pd_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
        </div> <!-- content -->

        <footer class="footer">
            © 2018 Upcube - Crafted with <i class="mdi mdi-heart text-danger"></i> by kbf.
        </footer>

    </div>
    <!-- End Right content here -->

</div>
<!-- END wrapper -->

<!-- jQuery  -->

<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.slimscroll.js')}}"></script>
<!--<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>-->
<!--<script src="{{asset('assets/js/jquery.scrollTo.min.js')}}"></script>-->


<script src="{{asset('assets/plugins/dropzone/dist/dropzone.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
<script>
$(function(){
    @if(isset($errorSession) && $errorSession)
    var msg = "<?php echo $errorSession['msg'] ?>";
    layer.open({
        content: msg,
        btn: '确定'
    });
    @endif
    $('#printPage').click(function() {
        window.print();
    });
});
</script>
</body>
</html>