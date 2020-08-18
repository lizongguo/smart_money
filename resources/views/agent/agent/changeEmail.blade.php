@extends('layouts.agent')
@section('content')

    <div class="main">
        <div class="my_setting">
            <div class="my_setting_info">
                <h2>{{ $email }}のメールアドレス変更</h2>
            </div>
            <form id="resume_add">
                <ul class="my_setting_list">
                    <li>
                        <p>新しいメールアドレス:</p>
                        <p><input type="email" class="input_text" name="email" placeholder="" required /></p>
                    </li>
                    <li>
                        <p>現在のパスワード:</p>
                        <p><input type="password" class="input_text" data-min="6" data-max="12" name="password" placeholder="" required /></p>
                    </li>
                    <div class="pop_btn">
                        <input type="button" value="変更をキャンセル" onclick="window.history.back();" class="pop_cancel_btn" />
                        <input type="submit" value="メールアドレスを保存" class="pop_ok_btn" />
                    </div>
                </ul>
            </form>
        </div>
    </div>

    <script>
        $("#resume_add").html5Validate(function() {
            var email = $("input[name='email']").val();
            var password = $("input[name='password']").val();

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
            $.ajax({
                type: "post",
                url: "{{ route('agent.user.changeEmail') }}",
                dataType: "json",
                data: {"email":email, "password":password},
                success: function(content){
                    layer.open({
                        content: content.msg,
                        btn: 'OK',
                        shadeClose: false,
                        yes: function(index) {
                            layer.close(index);
                            if (content.status == 200) {
                                window.location.href = "{{ route('agent.user.account') }}";
                                //window.history.back();
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
        //弹框
        $('#popupTrigger2').popup();
        $('#popupTrigger3').popup();
        //复选框,单选框
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
    </script>

@endsection