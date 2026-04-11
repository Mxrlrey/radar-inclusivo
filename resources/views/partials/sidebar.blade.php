<aside class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo2.png') }}" class="sidebar-logo" alt="Logo">
        <span class="sidebar-title">NAI</span>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->is('auth/dashboard') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-speedometer2"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/inicio') }}"
               class="{{ request()->is('inicio') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="text">Início</span>
            </a>
        </li>

        @can('report.reports.index')
        <li>
            <a href="{{ route('reports.index') }}"
            class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-bar-chart"></i></span>
                <span class="text">Relatórios</span>
            </a>
        </li>
        @endcan

        <li>
            <a href="{{ route('notifications.index') }}"
                class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <span class="icon"><i class="fa fa-regular fa-bell"></i></span>
                <span class="text">Notificações</span>
            </a>
        </li>

        <li>
            <a href="{{ route('backups.index') }}"
                class="{{ request()->routeIs('backups.*') ? 'active' : '' }}">
                <span class="icon"><i class="fas fa-cloud-download"></i></span>
                <span class="text">Backups</span>
            </a>
        </li>

        @auth
            @if(auth()->user()->is_admin)
                <li>
                    <a href="{{ route('deficiencies.index') }}"
                       class="{{ request()->routeIs('deficiencies.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-heart-pulse"></i></span>
                        <span class="text">Deficiências</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('positions.index') }}"
                       class="{{ request()->routeIs('positions.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-briefcase"></i></span>
                        <span class="text">Cargos</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('accessibility-features.index') }}"
                       class="{{ request()->routeIs('accessibility-features.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-universal-access"></i></span>
                        <span class="text">Recursos de Acessibilidade</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('barrier-categories.index') }}"
                       class="{{ request()->routeIs('barrier-categories.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-grid"></i></span>
                        <span class="text">Categorias de Barreiras</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('institutions.index') }}"
                       class="{{ request()->routeIs('institutions.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-building-fill"></i></span>
                        <span class="text">Instituições</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('locations.index') }}"
                       class="{{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <span class="icon"><i class="bi bi-geo-alt"></i></span>
                        <span class="text">Localizações</span>
                    </a>
                </li>

            @endif
        @endauth

        @can('student.view')
        <li class="nav-item">
            <a href="{{ route('students.index') }}"
                class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-people"></i></span>
                <span class="text">Alunos</span>
            </a>
        </li>
        @endcan

        @can('professional.view')
        <li>
            <a href="{{ route('professionals.index') }}"
            class="{{ request()->routeIs('professionals.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-person-badge"></i></span>
                <span class="text">Equipe</span>
            </a>
        </li>
        @endcan

        @can('assistive-technology.index')
        <li>
            <a href="{{ route('assistive-technologies.index') }}"
               class="{{ request()->routeIs('assistive-technologies.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-cpu"></i></span>
                <span class="text">Tecnologias Assistivas</span>
            </a>
        </li>
        @endcan

        @can('material.index')
            <li>
                <a href="{{ route('accessible-educational-materials.index') }}"
                   class="{{ request()->routeIs('accessible-educational-materials.*') ? 'active' : '' }}">
                    <span class="icon"><i class="bi bi-book"></i></span>
                    <span class="text">Materiais Pedagógicos</span>
                </a>
            </li>
        @endcan

        @can('barriers.index')
        <li>
            <a href="{{ route('barriers.index') }}"
               class="{{ request()->routeIs('barriers.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-slash-circle"></i></span>
                <span class="text">Barreiras</span>
            </a>
        </li>
        @endcan

        @can('loan.index')
        <li>
            <a href="{{ route('loans.index') }}"
               class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <span class="icon"><i class="bi bi-arrow-left-right"></i></span>
                <span class="text">Empréstimos</span>
            </a>
        </li>
        @endcan

        @can('waitlist.index')
            <li>
                <a href="{{ route('waitlists.index') }}"
                   class="{{ request()->routeIs('waitlists.*') ? 'active' : '' }}">
                    <span class="icon"><i class="bi bi-hourglass-split"></i></span>
                    <span class="text">Fila de Espera</span>
                </a>
            </li>
        @endcan

        @can('institutional-event.index')
            <li>
                <a href="{{ route('institutional-events.index') }}"
                   class="{{ request()->routeIs('inclusive-radar.institutional-events.*') ? 'active' : '' }}">
                    <span class="icon"><i class="fa-solid fa-calendar-day"></i></span>
                    <span class="text">Agenda Institucional</span>
                </a>
            </li>
        @endcan

{{--        <li class="menu-divider">Outros</li>--}}

{{--        <li>--}}
{{--            <a href="{{ url('/acessibilidade') }}"--}}
{{--               class="{{ request()->is('acessibilidade*') ? 'active' : '' }}">--}}
{{--                <span class="icon"><i class="bi bi-universal-access"></i></span>--}}
{{--                <span class="text">Acessibilidade</span>--}}
{{--            </a>--}}
{{--        </li>--}}

{{--        <li>--}}
{{--            <a href="{{ url('/sobre') }}"--}}
{{--               class="{{ request()->is('sobre*') ? 'active' : '' }}">--}}
{{--                <span class="icon"><i class="bi bi-info-circle"></i></span>--}}
{{--                <span class="text">Sobre</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <br>
        <br>
        <br>
    </ul>
</aside>
