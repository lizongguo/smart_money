@extends('layouts.agent')
@section('content')

    <div class="main">
        <div class="login_logo">
            <img src="/images/logo.png" />
        </div>
        <div class="login_box">
            <h3>パスワードをリセット</h3>
            <ul>
                <li class="login_item_title">「findjapanjob.com」アカウントをお持ちの場合、パスワードをリセットするための手順が<strong>{{ $email }}</strong>
                    に送信されます。</li>
                <li>
                    <p><a href="{{ route("agent.index.login") }}">ログイン</a></p>
                </li>
            </ul>
        </div>
    </div>

@endsection