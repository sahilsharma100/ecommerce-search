{{-- Created by Sahil Sharma --}}

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination pagination-gutter pagination-primary no-bg">
            {{-- First Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item page-indicator disabled" aria-disabled="true" aria-label="@lang('pagination.first')">
                    <a class="page-link" href="javascript:void()">
                        &laquo; First Page
                    </a>
                </li>
            @else
                <li class="page-item page-indicator" aria-disabled="false" aria-label="@lang('pagination.first')">
                    <a class="page-link" href="{{ \Request::url() }}" rel="prev">
                        &laquo; First Page
                    </a>
                </li>
            @endif

            @if ($paginator->onFirstPage())
                <li class="page-item page-indicator">
                    <a class="page-link" href="javascript:void()">
                        <i class="la la-angle-left"></i>
                    </a>
                </li>
            @else
                <li class="page-item page-indicator">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <i class="la la-angle-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item ">
                        <a class="page-link" href="javascript:void()">{{ $element }}</a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><a class="page-link"
                                    href="javascript:void()">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link"
                                    href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item page-indicator">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="la la-angle-right"></i></a>
                </li>
            @else
                <li class="page-item page-indicator disabled">
                    <a class="page-link" href="javascript:void()" rel="next">
                        <i class="la la-angle-right"></i></a>
                </li>
            @endif

            {{-- Last Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item  page-indicator">
                    <a class="page-link" href="{{ \Request::url() . '?page=' . $paginator->lastPage() }}"
                        rel="last" aria-label="@lang('pagination.last')">Last Page &raquo;</a>
                </li>
            @else
                <li class="page-item  page-indicator disabled" aria-disabled="true" aria-label="@lang('pagination.last')">
                    <span class="page-link" aria-hidden="true">Last Page &raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
