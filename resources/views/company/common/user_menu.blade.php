<div class="company_menu">
    <ul>
        @foreach($web_user_menu as $k => $v)
            <li @if(isset($menu_active) && $k == $menu_active) class="active" @endif><a href="{{ $v['url'] }}">{{ $v['name'] }}</a></li>
        @endforeach
    </ul>
</div>