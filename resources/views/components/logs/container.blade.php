@props(['logs'])

<div class="cd-container">
    <div id="cd-timeline">
        @forelse($logs as $log)
            <x-logs.item :log="$log" />
        @empty
            <div class="empty-history text-center py-5">
                <div class="empty-history-icon mb-3">
                    <i class="fa fa-clipboard-list"></i>
                </div>
                <h6 class="mb-1 text-primary">Nenhum registro encontrado</h6>
                <p class="text-muted mb-0">Não há alterações registradas para este item.</p>
            </div>
        @endforelse
    </div>

    @if($logs->hasPages())
        <div class="pt-4 mt-4 border-top">
            {{ $logs->links() }}
        </div>
    @endif
</div>
