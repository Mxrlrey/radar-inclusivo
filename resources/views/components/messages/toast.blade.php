@if(session('success') || session('error') || session('info') || $errors->any())
    <div id="toast-container">
        @php
            $type = 'info';
            $icon = 'fa-info';
            $message = '';

            if (session('success')) {
                $type = 'success';
                $icon = 'fa-check';
                $message = session('success');
            } elseif (session('error')) {
                $type = 'danger';
                $icon = 'fa-warning';
                $message = session('error');
            } elseif ($errors->any()) {
                $type = 'danger';
                $icon = 'fa-exclamation';
                $message = $errors->first();
            } elseif (session('info')) {
                $message = session('info');
            }
        @endphp

        <div class="toast-custom {{ $type }}" id="toast-element">
            <div class="toast-content">
                <i class="fa {{ $icon }}"></i>
                <div class="toast-body-text">
                    {{ $message }}
                </div>
                <button type="button" class="btn-close-toast" onclick="window.closeToast()">×</button>
            </div>
            <div class="toast-progress"></div>
        </div>
    </div>
@endif
