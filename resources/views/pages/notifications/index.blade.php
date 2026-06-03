@extends('layouts.app')

@section('content')

    <div class="mb-4">
        <x-breadcrumb :items="[
        'Home' => route('dashboard'),
        'Notificações' => null
    ]" />
    </div>

    <div class="page-header">
        <div class="page-header-title">
            <h1>Notificações</h1>
            <p>Histórico completo das suas notificações do sistema.</p>
        </div>

        @can('notification.read-all')
            <form action="{{ route('notificacoes.ler.todas') }}" method="POST">
                @csrf
                <x-buttons.submit-button variant="primary">
                    <i class="fa fa-check"></i>
                    Marcar todas como lidas
                </x-buttons.submit-button>
            </form>
        @endcan
    </div>

    @forelse($notifications as $notification)

        @php
            $data = $notification->data;
            $isUnread = is_null($notification->read_at);
        @endphp

        <div class="card-custom notification-card mb-3 {{ $isUnread ? 'notification-unread' : '' }}">

            <div class="d-flex align-items-start w-100">

                <div class="notification-icon">
                    <i class="fa fa-bell"></i>
                </div>

                <div class="notification-content ms-3">

                    <div class="notification-title">
                        {{ $data['title'] ?? 'Notificação' }}
                    </div>

                    <div class="notification-text">
                        {{ $data['message'] ?? '' }}
                    </div>

                    <div class="notification-time">
                        <i class="fa fa-clock-o"></i>
                        {{ $notification->created_at->locale('pt_BR')->diffForHumans() }}
                    </div>

                </div>

                <div class="d-flex flex-column gap-2 align-items-end ms-auto">

                    @if(isset($data['url']))
                        <x-buttons.link-button
                            href="{{ $data['url'] }}"
                            variant="success"
                            size="xs"
                        >
                            Abrir
                        </x-buttons.link-button>
                    @endif

                    @can('notification.read')
                    @if($isUnread)
                        <form action="{{ route('notificacoes.ler', $notification->id) }}" method="POST">
                            @csrf

                            <x-buttons.submit-button
                                type="submit"
                                variant="primary"
                                size="xs"
                            >
                                Lido
                            </x-buttons.submit-button>
                        </form>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="card-custom text-center py-5 text-muted">
            <div class="notif-empty-icon mb-3">
                <i class="far fa-bell"></i>
            </div>
            Nenhuma notificação encontrada
        </div>
    @endforelse

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
@endsection
