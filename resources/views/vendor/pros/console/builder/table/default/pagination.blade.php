@if($page > 1)
    <li class="page-item previous m-1" data-page="{{ $page - 1 }}"><a href="javascript:;" class="page-link"><i class="previous"></i></a></li>
@endif
@if ((int)$total_pages <= 6)
    @for ($i = 1; $i < (int)$total_pages + 1; $i++)
        <li class="page-item m-1 @if ((int)$i === (int)$page) active @endif" data-page="{{ $i }}"><a href="javascript:;" class="page-link">{{ $i }}</a></li>
    @endfor
@else
    @if ((int)$page < 5)
        @for ($i = 1; $i <= 5; $i++)
            <li class="page-item m-1 @if ((int)$i === (int)$page) active @endif" data-page="{{ $i }}"><a href="javascript:;" class="page-link">{{ $i }}</a></li>
        @endfor
        <li class="page-item m-1 disabled"><a href="javascript:;" class="page-link">...</a></li>
        <li class="page-item m-1" data-page="{{ $total_pages }}"><a href="javascript:;" class="page-link">{{ $total_pages }}</a></li>
    @elseif ((int)$page > ((int)$total_pages - 4))
        <li class="page-item m-1" data-page="1"><a href="javascript:;" class="page-link">1</a></li>
        <li class="page-item m-1 disabled"><a href="javascript:;" class="page-link">...</a></li>
        @for ($i = ((int)$total_pages - 4); $i <= (int)$total_pages; $i++)
            <li class="page-item m-1 @if ((int)$i === (int)$page) active @endif" data-page="{{ $i }}"><a href="javascript:;" class="page-link">{{ $i }}</a></li>
        @endfor
    @else
        <li class="page-item m-1" data-page="1"><a href="javascript:;" class="page-link">1</a></li>
        <li class="page-item m-1 disabled"><a href="javascript:;" class="page-link">...</a></li>
        @for ($i = ((int)$page - 2); $i <= ((int)$page + 2); $i++)
            <li class="page-item m-1 @if ((int)$i === (int)$page) active @endif" data-page="{{ $i }}"><a href="javascript:;" class="page-link">{{ $i }}</a></li>
        @endfor
        <li class="page-item m-1 disabled"><a href="javascript:;" class="page-link">...</a></li>
        <li class="page-item m-1" data-page="{{ $total_pages }}"><a href="javascript:;" class="page-link">{{ $total_pages }}</a></li>
    @endif
    @if ((int)$page < (int)$total_pages && (int)$total_pages >= 2)
        <li class="page-item next m-1" data-page="{{ $page + 1 }}"><a href="javascript:;" class="page-link"><i class="next"></i></a></li>
    @endif
@endif
