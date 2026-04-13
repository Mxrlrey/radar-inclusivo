<div class="dropdown">
    <button class="nav-user-dropdown dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
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

            <i class="ion-android-user-menu"></i>
        </span>
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
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
