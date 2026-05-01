@props([
    'fields' => []
])

<form {{ $attributes->merge(['class' => 'search-wrapper', 'role' => 'search']) }}>
    <div class="search-filters-row">
        @foreach($fields as $field)
            @php
                $accessibleName = $field['label'] ?? 'Filtrar por ' . $field['name'];
            @endphp

            <x-table.filters.group :label="$field['label'] ?? null">
                @if(($field['type'] ?? 'text') === 'select')
                    <x-table.filters.select
                        :name="$field['name']"
                        :options="$field['options']"
                        :ariaLabel="$accessibleName"
                    />
                @else
                    <x-table.filters.input
                        :name="$field['name']"
                        :ariaLabel="$accessibleName"
                    />
                @endif
            </x-table.filters.group>
        @endforeach
        <x-table.filters.clear />
    </div>
</form>
