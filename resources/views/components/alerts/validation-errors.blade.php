@if($errors->any())
    <div class="alert alert-danger mb-4" role="alert">
        <p class="fw-bold mb-2">
            <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
            <strong>Atenção:</strong> Existem erros no preenchimento.
        </p>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
