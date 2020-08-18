@extends('layouts.company')
@section('content')

    <div class="main">
        <div class="my_setting">
            <div class="my_setting_info">
                <h2>{{ $email }}のパスワード変更</h2>
            </div>
            <form id="resume_add">
                <ul class="my_setting_list">
                    <li>
                        <p>現在のパスワード:</p>
                        <p><input type="password" class="input_text" value="" name="password_old" data-min="6" data-max="12" placeholder="" required autocomplete="new-password"/></p>
                    </li>
                    <li>
                        <p>新しいパスワード:</p>
                        <p><input type="password" class="input_text" value="" name="password_new" data-min="6" data-max="12"  placeholder="" required autocomplete="new-password"/></p>
                    </li>
                    <li>
                        <p>新しいパスワード (再入力):</p>
                        <p><input type="password" class="input_text" value="" name="password_new_confirm" data-min="6" data-max="12" placeholder="" required autocomplete="new-password"/></p>
                    </li>
                    <div class="pop_btn">
                        <input type="button" value="変更をキャンセル" onclick="window.history.back();" class="pop_cancel_btn" />
                        <input type="submit" value="確認" class="pop_ok_btn" />
                    </div>
                </ul>
            </form>
        </div>
    </div>

    <script>
        $("#resume_add").html5Validate(function() {
            var password_old = $("input[name='password_old']").val();
            var password_new = $("input[name='password_new']").val();
            var password_new_confirm = $("input[name='password_new_confirm']").val();

            if (password_new_confirm != password_new) {
                layer.open({
                    content: "2回入力したパスワードが違います。",
                    skin: 'msg',
                    time: 2
                });
            } else {
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
                });
                $.ajax({
                    type: "post",
                    url: "{{ route('company.user.changePassword') }}",
                    dataType: "json",
                    data: {"password_old":password_old, "password_new":password_new},
                    success: function(content){
                        layer.open({
                            content: content.msg,
                            btn: 'OK',
                            shadeClose: false,
                            yes: function(index) {
                                layer.close(index);
                                if (content.status == 200) {
                                    //window.location.href = "{{ route('company.user.index') }}";
                                    window.history.back();
                                }
                            }
                        });
                    },
                    error: function (err){
                        console.log(err);
                    }
                });
            }
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