{{-- 🔔 Partials: Notificações --}}
@auth
    @php
        $unreadCount = Auth::user()->unreadNotifications()->count();
        $displayCount = $unreadCount > 99 ? '99+' : $unreadCount;
        $notifications = Auth::user()->unreadNotifications()->take(5)->get();
    @endphp

    <div class="dropdown">
        <button class="btn-notif-circle position-relative waves-effect"
                type="button"
                id="dropdownNotif"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                title="{{ $unreadCount > 0 ? 'Ver ' . $unreadCount . ' notificações não lidas' : 'Nenhuma notificação nova' }}">
            <i class="fa fa-bell" aria-hidden="true"></i>
            @if($unreadCount > 0)
                <span id="notif-count" class="notification-badge">
            {{ $displayCount }}
            <span class="visually-hidden">notificações não lidas</span>
        </span>
            @endif
        </button>

        <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="dropdownNotif">
            <div class="notification-header">
                Notificações
                @if($unreadCount > 0)
                    <span class="badge bg-primary ms-2">{{ $unreadCount }} novas</span>
                @endif
            </div>

            {{-- Lista de notificações --}}
            <div class="notification-scroll">
                @forelse($notifications as $notification)
                    @php $data = $notification->data; @endphp
                    <a href="{!! $data['url'] ?? '#' !!}" class="notification-item {{ !$notification->read_at ? 'unread' : '' }}">
                        <div class="notification-icon">
                            <i class="ion-information"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">{{ $data['title'] ?? 'Nova atualização' }}</div>
                            <div class="notification-text">{{ $data['message'] ?? '' }}</div>
                            <div class="notification-time">
                                <i class="fa fa-clock-o me-1"></i>
                                {{ \Carbon\Carbon::parse($data['created_at'] ?? now())->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="fa fa-bell-slash text-muted d-block mb-2 notif-empty-icon"></i>
                        <span class="text-muted small">Você está em dia! Nenhuma notificação.</span>
                    </div>
                @endforelse
            </div>

            {{-- Rodapé --}}
            <div class="notification-footer">
                <a href="{{ route('notifications.index') }}">Ver todas as notificações</a>
            </div>
        </div>
    </div>
@endauth
