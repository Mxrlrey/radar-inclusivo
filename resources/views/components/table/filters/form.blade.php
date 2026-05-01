@props([
    'fields' => []
])

<form {{ $attributes->merge(['class' => 'search-wrapper', 'role' => 'search']) }}>
    <div class="search-filters-row">
        @foreach($fields as $field)
            @php
                $accessibleName = $field['label'] ?? 'Filtrar por ' . $field['name'];
                $placeholder = $field['placeholder']
                    ?? (str_starts_with($accessibleName, 'Filtrar')
                        ? $accessibleName
                        : 'Filtrar por ' . lcfirst($accessibleName) . '...');
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
                        :placeholder="$placeholder"
                    />
                @endif
            </x-table.filters.group>
        @endforeach
        <x-table.filters.clear />
    </div>
</form>
