<aside class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo2.png') }}" class="sidebar-logo" alt="Logo">
        <span class="sidebar-title">Radar</span>
        <span class="sidebar-title">Inclusivo</span>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->is('auth/dashboard') ? 'active' : '' }}">
                <span class="icon"><i class="ion-speedometer"></i></span>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/inicio') }}"
               class="{{ request()->is('inicio') ? 'active' : '' }}">
                <span class="icon"><i class="ion-home"></i></span>
                <span class="text">Início</span>
            </a>
        </li>

        <li>
            <a href="{{ route('notifications.index') }}"
               class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-ios7-bell"></i></span>
                <span class="text">Notificações</span>
            </a>
        </li>

        @can('student.view')
            <li>
                <a href="{{ route('students.index') }}"
                   class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-contact"></i></span>
                    <span class="text">Alunos</span>
                </a>
            </li>
        @endcan

        @can('professional.view')
            <li>
                <a href="{{ route('professionals.index') }}"
                   class="{{ request()->routeIs('professionals.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-social"></i></span>
                    <span class="text">Equipe</span>
                </a>
            </li>
        @endcan

        @can('assistive-technology.index')
            <li>
                <a href="{{ route('assistive-technologies.index') }}"
                   class="{{ request()->routeIs('assistive-technologies.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-gear-a"></i></span>
                    <span class="text">Tecnologias Assistivas</span>
                </a>
            </li>
        @endcan

        @can('material.index')
            <li>
                <a href="{{ route('accessible-educational-materials.index') }}"
                   class="{{ request()->routeIs('accessible-educational-materials.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-book"></i></span>
                    <span class="text">Materiais Pedagógicos</span>
                </a>
            </li>
        @endcan

        @can('barriers.index')
            <li>
                <a href="{{ route('barriers.index') }}"
                   class="{{ request()->routeIs('barriers.*') ? 'active' : '' }}">
                    <span class="icon"><i class="fa fa-ban"></i></span>
                    <span class="text">Barreiras</span>
                </a>
            </li>
        @endcan

        @can('loan.index')
            <li>
                <a href="{{ route('loans.index') }}"
                   class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-arrow-swap"></i></span>
                    <span class="text">Empréstimos</span>
                </a>
            </li>
        @endcan

        @can('waitlist.index')
            <li>
                <a href="{{ route('waitlists.index') }}"
                   class="{{ request()->routeIs('waitlists.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-timer"></i></span>
                    <span class="text">Fila de Espera</span>
                </a>
            </li>
        @endcan

        @can('institutional-event.index')
            <li>
                <a href="{{ route('institutional-events.index') }}"
                   class="{{ request()->routeIs('institutional-events.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-calendar"></i></span>
                    <span class="text">Agenda Institucional</span>
                </a>
            </li>
        @endcan

        @auth
            @if(auth()->user()->is_admin)

                <li>
                    <a href="{{ route('deficiencies.index') }}"
                       class="{{ request()->routeIs('deficiencies.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-medkit"></i></span>
                        <span class="text">Deficiências</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('accessibility-features.index') }}"
                       class="{{ request()->routeIs('accessibility-features.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-man"></i></span>
                        <span class="text">Recursos de Acessibilidade</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('barrier-categories.index') }}"
                       class="{{ request()->routeIs('barrier-categories.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-grid"></i></span>
                        <span class="text">Categorias de Barreiras</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('positions.index') }}"
                       class="{{ request()->routeIs('positions.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-briefcase"></i></span>
                        <span class="text">Cargos</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('institutions.index') }}"
                       class="{{ request()->routeIs('institutions.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-university"></i></span>
                        <span class="text">Instituições</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('locations.index') }}"
                       class="{{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-location"></i></span>
                        <span class="text">Localizações</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('backups.index') }}"
                       class="{{ request()->routeIs('backups.*') ? 'active' : '' }}">
                        <span class="icon"><i class="ion-ios7-cloud-download"></i></span>
                        <span class="text">Backups</span>
                    </a>
                </li>
            @endif
        @endauth

        @can('report.reports.index')
            <li>
                <a href="{{ route('reports.index') }}"
                   class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-stats-bars"></i></span>
                    <span class="text">Relatórios</span>
                </a>
            </li>
        @endcan
        <br><br><br>
    </ul>
</aside>
