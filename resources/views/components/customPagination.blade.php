@if ($paginator->hasPages())

    <nav aria-label="Page navigation example">

        <ul class="pagination justify-content-center">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link"> < </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}"> < </a>
                </li>
            @endif

            {{-- Pages --}}
            @foreach($elements as $element)

                @foreach ($element as $page => $url)

                    @if ($page == $paginator->currentPage())
                        <li class="page-item disabled">
                            <a class="page-link" href="#">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif

                @endforeach
            @endforeach

            {{-- Next --}}
            @if (!$paginator->hasMorePages())
                <li class="page-item disabled">
                    <a class="page-link"> > </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}"> > </a>
                </li>
            @endif
        </ul>

    </nav>

@endif
