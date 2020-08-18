@extends('layouts.agent')
@section('content')
    <div class="main">
        <div class="login_logo">
            <img src="/images/logo.png" />
        </div>
        <div class="login_box">
            <h3>ログイン</h3>
            <form id="form">
                {{csrf_field()}}
                <ul>
                    <li class="login_item_title">メールアドレス</li>
                    <li><input type="email" name="email" class="login_input" required /></li>
                    <li class="login_item_title">パスワード</li>
                    <li><input type="password" name="password" data-min="6" data-max="12" class="login_input" required /></li>
                    <li><span><input name="remember" value="1" type="checkbox" id="checkbox1"><label for="checkbox1" class="checkbox1-text">ログインしたままにする。</label></span></li>
                    <li><input type="submit" value="ログイン" class="login_btn"/></li>
                    <li><p><a href="{{route("agent.index.find")}}">パスワードをお忘れですか？</a></p></li>
                </ul>
            </form>
        </div>
    </div>
    <script>
        //弹框
        $('#popupTrigger2').popup();

        //复选框,单选框
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
        $("#form").html5Validate(function() {
            $.ajax({
                type: "post",
                url: $("#form").attr("action"),
                dataType: "json",
                data: $("#form").serialize(),
                success: function(content){
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            if (content.status == 200) {
                                window.location.href = "{{ route("agent.index.index") }}";
                            }
                        }
                    });
                },
                error: function (err){
                    console.log(err);
                }
            });
        }, {

        });
    </script>

@endsection