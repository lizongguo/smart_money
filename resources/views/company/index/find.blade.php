@extends('layouts.company')
@section('content')

    <div class="main">
        <div class="login_logo">
            <img src="/images/logo.png" />
        </div>
        <div class="login_box">
            <h3>パスワードをリセット</h3>
            <form id="form">
                <ul>
                    <li class="login_item_title">「findjapanjob.com」アカウントに使用しているメールアドレスを入力すると、パスワードをリセットする手順が送信されます。</li>
                    <li class="login_item_title"><strong>メールアドレス</strong></li>
                    <li><input type="email" name="email" required class="login_input" /></li>

                    <li><input type="submit" value="送信する" class="login_btn"/></li>
                </ul>
            </form>
        </div>
    </div>

    <script>
        $("#form").html5Validate(function() {

            var email = $("input[name='email']").val();
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
            $("body").mLoading({
                text:"送信中...",//加载文字
                icon:"/images/loading.gif",//加载图标
                mask:true//是否显示遮罩
            });
            $.ajax({
                type: "post",
                url: "{{ route('company.index.find') }}",
                dataType: "json",
                data: {"email": email},
                success: function(content){
                    $("body").mLoading("hide");
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            if (content.status == 200) {
                                window.location.href = content.url;
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