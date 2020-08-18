<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta charset="utf-8">
  <title>{{$page_title ? $page_title : config('site.sitename')}}</title>
  <link type="text/css" href="{{asset('/css/font-awesome.min.css')}}" rel="stylesheet">
  <link type="text/css" href="{{asset('/css/icheck.css')}}" rel="stylesheet">
  <link type="text/css" href="{{asset('/css/style.css')}}" rel="stylesheet" />
  <link type="text/css" href="{{asset('/css/foundation-datepicker.css')}}" rel="stylesheet" />
  <link type="text/css" href="{{asset('/css/jquery.mloading.css')}}" rel="stylesheet" />
  <script type="text/javascript" src="{{asset('/js/jquery.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/vintage-popup.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/pgwmenu.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/icheck.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/jquery-html5Validate.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/jquery.formkeeper.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/foundation-datepicker.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/foundation-datepicker.ja.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/jquery.mloading.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/jquery.dataTables.js')}}"></script>
  <script type="text/javascript" src="{{asset('/js/new/common.js')}}?v={{date('Y-m-d')}}"></script>

  <script src="{{asset('/js/new/vue.js')}}"></script>
  <script src="{{asset('/js/new/axios.min.js')}}"></script>
  <script src="{{asset('/js/new/VeeValidate/vee-validate.js')}}"></script>
  <script src="{{asset('/js/new/VeeValidate/ja.js')}}"></script>
  <script src="{{asset('/js/new/layer/mobile/layer.js')}}"></script>

</head>
<body>
<div class="top_nav">
  <div class="top_nav_main">
    <div class="user_menu">
      @if ($userInfo)
        <div class="login_link"><a href="javascript:void(0)"><i class="fa fa-user-circle-o"></i> {{ $userInfo->email }} <i class="fa fa-angle-down arrow"></i></a></div>
        <ul class="user_sub_menu">
          <li>
            <ul>
              @foreach($web_user_menu as $k => $v)
                <li><a href="{{ $v['url'] }}"><i class="{{ $v['class'] }}"></i> {{ $v['name'] }}</a></li>
              @endforeach
              <li><a href="{{ route("agent.index.info") }}"><i class="fa fa-newspaper-o"></i> エージェント会社情報</a></li>
              <li><a href="{{ route("agent.user.account") }}"><i class="fa fa-cog"></i> アカウント設定</a></li>
              <li><a href="{{ route("agent.index.logout") }}"><i class="fa fa-sign-out "> </i> ログアウト</a></li>
            </ul>
          </li>
        </ul>
      @else
      <div class="login_link"><a href="{{ route("company.index.login") }}"><i class="fa fa-user-circle-o"></i>ログイン</a></div>
      @endif
    </div>
    <script>
        var myDiv = $(".user_sub_menu");
        $(function ()
        {
            $(".login_link").click(function (event)
            {

                showDiv();
                $(".login_link").toggleClass("open");
                $(document).one("click", function ()
                {
                    $(myDiv).stop().slideToggle(100);
                    $(".login_link").toggleClass("open");
                });

                event.stopPropagation();
            });
            $(myDiv).click(function (event)
            {

                event.stopPropagation();
            });
        });
        function showDiv()
        {
            $(myDiv).stop().slideToggle(100);
        }
    </script>
  </div>
</div>
@yield('content')

<div class="footer">
  <div class="footer_main">
    <p><a href="javascript:void(0)" id="popupTrigger2" data-popup-target="aboutPopup">findjapanjobについて</a>
      {{--<span><a href="javascript:void(0)">エージェント様はこちら</a></span>--}}
    </p>
  </div>
</div>

<div class="popup" tabindex="-2" role="dialog" data-popup-id="aboutPopup">
  <div class="popup__container">
    <div class="popup__close"><span></span><span></span></div>
    <div class="popup__content">
      <div class="pop_content" role="dialog" data-popup-id="demoPopup">
        <h3>findjapanjobについて</h3>
        <p>運営会社 </p>
        <p>ハイナビス株式会社(Hinabiz Inc.)</p>
        <p>〒105-0004 東京都港区新橋5-12-11天翔新橋5丁目ビル702</p>
        <p>TEL  03-6161-6202</p>

      </div>
    </div>
  </div>
</div>

<script>

    $('.pgwMenu').pgwMenu();
    //弹框
    $('#popupTrigger2').popup();
</script>

</body>
</html>


