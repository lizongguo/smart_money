@if ($paginator->hasPages())
    @php
    $pageArr = [];
    foreach($elements as $element) {
       if(is_array($element)) {
           $pageArr = $pageArr + $element;
       }
    }

    @endphp
    <div class="pages">
        {{--@if ($paginator->currentPage() == 1)--}}
            {{--<strong>首页</strong>--}}
        {{--@else--}}
            {{--<a href="{{ $pageArr[1] }}" class="next">首页</a>--}}
        {{--@endif--}}

        @if ($paginator->onFirstPage())

        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="next">前へ</a>
        @endif

            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <a href="javascript:void(0)">{{ $element }}</a>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <strong>{{ $page }}</strong>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="next">次へ</a>
        @else
        @endif

        {{--@if ($paginator->currentPage() == $paginator->lastPage())--}}
            {{--<strong>末页</strong>--}}
        {{--@else--}}
            {{--<a href="{{ $pageArr[$paginator->lastPage()] }}" class="next">末页</a>--}}
        {{--@endif--}}
        <div class="clear"></div>
    </div>
@endif
