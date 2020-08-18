@extends('layouts.agent')
@section('content')

    <div class="main">
        <div class="my_setting">
            <div class="my_setting_info">
                <h2>アカウント設定</h2>
            </div>
            <ul class="my_setting_list">
                <li>
                    <p>メールアドレス:</p>
                    <p><span><a href="{{ route("agent.user.changeEmail") }}"><i class="fa fa-envelope" aria-hidden="true"></i> メールアドレスを変更</a></span><b>{{ $email }}</b></p>
                </li>
                <li>
                    <p>パスワード:</p>
                    <p><span><a href="{{ route("agent.user.changePassword") }}"><i class="fa fa-lock" aria-hidden="true"></i> パスワードを変更</a></span><b>************</b></p>
                </li>
            </ul>
        </div>
    </div>



@endsection