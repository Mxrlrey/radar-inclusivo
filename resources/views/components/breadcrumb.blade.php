@props(['items'])

<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom">
        @foreach($items as $label => $url)
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                @if(!$loop->last)
                    <a href="{{ $url }}">{{ $label }}</a>
                @else
                    <span aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
