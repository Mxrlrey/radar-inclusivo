<div class="card-custom overflow-hidden">
    @php
        $method = strtoupper($attributes->get('method', 'POST'));
        $formMethod = in_array($method, ['GET', 'POST']) ? $method : 'POST';
    @endphp

    <form {{ $attributes->merge(['method' => $formMethod, 'novalidate' => true]) }} class="p-0">

        @if($formMethod !== 'GET')
            @csrf
        @endif

        @if(! in_array($method, ['GET','POST']))
            @method($method)
        @endif

        <div>
            {{ $slot }}
        </div>
    </form>
</div>
