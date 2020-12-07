@php
    $link_num = App\Lib\Constant::WEB_PAGINATE_LINK_NUM;
@endphp

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- First Page Link --}}
             <li class="page-item {{ $paginator->onFirstPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">最初</a>
            </li>

            {{-- Previous Page Link --}}
            <li class="page-item {{ $paginator->onFirstPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">前</a>
            </li>


            {{-- Pagination Elements --}}
            {{-- 定数（$link_num）よりページ数が多い場合 --}}
            @if ($paginator->lastPage() > $link_num)

                {{-- 現在のページが表示するリンクの中心位置よりも左の場合 --}}
                @if ($paginator->currentPage() <= floor($link_num / 2))
                    @php
                        $start_page = 1;
                        $end_page = $link_num;
                    @endphp

                {{-- 現在のページが表示するリンクの中心位置よりも右のとき --}}
                @elseif ($paginator->currentPage() > $paginator->lastPage() - floor($link_num / 2))
                    @php
                        $start_page = $paginator->lastPage() - ($link_num - 1);
                        $end_page = $paginator->lastPage();
                    @endphp

                {{-- 現在のページが表示するリンクの中心位置の場合 --}}
                @else
                    @php
                        $start_page = $paginator->currentPage() - (floor(($link_num % 2 == 0 ? $link_num - 1 : $link_num)  / 2));
                        $end_page = $paginator->currentPage() + floor($link_num / 2);
                    @endphp
                @endif

            {{-- 定数（$link_num）よりもページ数が少ない場合 --}}
            @else
                @php
                    $start_page = 1;
                    $end_page = $paginator->lastPage();
                @endphp
            @endif

            {{-- 処理部分 --}}
            @for ($i = $start_page; $i <= $end_page; $i++)
                @if ($i == $paginator->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $i }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                @endif
            @endfor


            {{-- Next Page Link --}}
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">次</a>
            </li>

            {{-- Last Page Link --}}
            <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">最後</a>
            </li>
        </ul>
    </nav>
@endif
