<div class="card-custom overflow-hidden">
    @php
        $method = strtoupper($attributes->get('method', 'POST'));
        $formMethod = in_array($method, ['GET', 'POST']) ? $method : 'POST';
        $formAttributes = $attributes->except('class');
        $formClasses = trim('p-0 ' . $attributes->get('class', ''));
    @endphp

    <form {{ $formAttributes->merge(['method' => $formMethod, 'novalidate' => true, 'class' => $formClasses]) }}>

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
