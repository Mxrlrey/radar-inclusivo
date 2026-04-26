<div {{ $attributes->merge(['class' => 'mt-2']) }} role="group" aria-label="Ações da visualização">

    <x-forms.separator />

    <div class="show-footer">
        <div class="show-footer-spacer"></div>

        <div class="show-footer-content">
            {{ $slot }}
        </div>
    </div>
</div>
