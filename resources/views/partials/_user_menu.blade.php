<div class="dropdown">
    <button class="nav-user-dropdown dropdown-toggle waves-effect"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
            id="userMenuButton"
            aria-label="Menu do usuário: {{ Auth::user()->name }}">

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

        <i class="ion-android-user-menu"></i>
    </button>

    <ul class="dropdown-menu" aria-labelledby="userMenuButton">
        <li>
            <a class="dropdown-item waves-effect" href="{{ route('profile.edit') }}">
                <i class="ion-ios7-person"></i> Perfil
            </a>
        </li>
        <li>
            <a class="dropdown-item text-danger waves-effect" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="ion-log-out"></i> Sair
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </a>
        </li>
    </ul>
</div>
