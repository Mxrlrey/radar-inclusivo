<div class="dropdown">
    <button class="nav-user-dropdown dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            aria-label="Abrir menu do usuário"
            id="userMenuButton"
            style="overflow: visible !important;"
            title="Menu do usuário: {{ Auth::user()->name }}">

        <span class="waves-effect d-flex align-items-center w-100 h-100" style="padding: 0 10px;">

            <div class="user-info-text me-2 d-none d-md-block">
                <span class="user-name">
                    {{ Str::words(Auth::user()?->name ?? 'Convidado', 1, '') }}
                </span>
                <span class="user-role">
                    @if (Auth::user()->professional)
                        {{ Auth::user()->professional?->position?->name }}
                    @else
                        Admin
                    @endif
                </span>
            </div>

            <i class="ion-android-user-menu" aria-hidden="true"></i>
        </span>
    </button>

    <ul class="dropdown-menu" aria-labelledby="userMenuButton">
        @can('profile.update')
            <li>
                <a class="dropdown-item waves-effect" href="{{ route('profile.edit') }}">
                    <i class="ion-ios7-person" aria-hidden="true"></i> Perfil
                </a>
            </li>
        @endcan
        @can('auth.logout')
            <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit"
                            class="dropdown-item text-danger waves-effect w-100 text-start border-0 bg-transparent"
                            aria-label="Sair da conta">
                        <i class="ion-log-out" aria-hidden="true"></i> Sair
                    </button>
                </form>
            </li>
        @endcan
    </ul>
</div>
