@php
    $PAGINATE_LINK_NUM = 5;
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
            @if ($paginator->lastPage() > $PAGINATE_LINK_NUM)

                @if ($paginator->currentPage() <= floor($PAGINATE_LINK_NUM / 2))
                    @php
                        $start_page = 1;
                        $end_page = $PAGINATE_LINK_NUM;
                    @endphp
                @elseif ($paginator->currentPage() > $paginator->lastPage() - floor($PAGINATE_LINK_NUM / 2))
                    @php
                        $start_page = $paginator->lastPage() - ($PAGINATE_LINK_NUM - 1);
                        $end_page = $paginator->lastPage();
                    @endphp
                @else
                    @php
                        $start_page = $paginator->currentPage() - (floor(($PAGINATE_LINK_NUM % 2 == 0 ? $PAGINATE_LINK_NUM - 1 : $PAGINATE_LINK_NUM)  / 2));
                        $end_page = $paginator->currentPage() + floor($PAGINATE_LINK_NUM / 2);
                    @endphp
                @endif
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
