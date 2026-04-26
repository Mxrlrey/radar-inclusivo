<aside class="sidebar" id="app-sidebar" aria-label="Menu lateral principal">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <span class="sidebar-brand-icon" aria-hidden="true">
                <i class="fa fa-podcast"></i>
            </span>

            <span class="sidebar-brand-text">
                <span class="sidebar-brand-title">Radar</span>
                <span class="sidebar-brand-accent">Inclusivo</span>
            </span>
        </a>
    </div>

    <div class="sidebar-body">
        <div id="sidebar-menu">
            <ul class="sidebar-menu">
                <li class="menu-divider"><span>Navegação</span></li>

                <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->is('painel') ? 'active' : '' }}">
                <span class="icon"><i class="ion-speedometer"></i></span>
                <span class="text">Dashboard</span>
            </a>
                </li>

                <li>
            <a href="{{ route('notificacoes.index') }}"
               class="{{ request()->routeIs('notificacoes.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-ios7-bell"></i></span>
                <span class="text">Notificações</span>
            </a>
                </li>

                <li class="menu-divider"><span>Gestão</span></li>

                @can('student.view')
                    <li>
                <a href="{{ route('estudantes.index') }}"
                   class="{{ request()->routeIs('estudantes.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-contact"></i></span>
                    <span class="text">Alunos</span>
                </a>
                    </li>
                @endcan

                @can('professional.view')
                    <li>
                <a href="{{ route('profissionais.index') }}"
                   class="{{ request()->routeIs('profissionais.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-social"></i></span>
                    <span class="text">Equipe</span>
                </a>
                    </li>
                @endcan

                @can('assistive-technology.index')
                    <li>
                <a href="{{ route('tecnologias-assistivas.index') }}"
                   class="{{ request()->routeIs('tecnologias-assistivas.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-gear-a"></i></span>
                    <span class="text">Tecnologias Assistivas</span>
                </a>
                    </li>
                @endcan

                @can('material.index')
                    <li>
                <a href="{{ route('materiais-pedagogicos-acessiveis.index') }}"
                   class="{{ request()->routeIs('materiais-pedagogicos-acessiveis.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-book"></i></span>
                    <span class="text">Materiais Pedagógicos</span>
                </a>
                    </li>
                @endcan

                @can('loan.index')
                    <li>
                <a href="{{ route('emprestimos.index') }}"
                   class="{{ request()->routeIs('emprestimos.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-arrow-swap"></i></span>
                    <span class="text">Empréstimos</span>
                </a>
                    </li>
                @endcan

                @can('waitlist.index')
                    <li>
                <a href="{{ route('filas-de-espera.index') }}"
                   class="{{ request()->routeIs('filas-de-espera.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-android-timer"></i></span>
                    <span class="text">Fila de Espera</span>
                </a>
                    </li>
                @endcan

                @can('barriers.index')
                    <li>
                <a href="{{ route('barreiras.index') }}"
                   class="{{ request()->routeIs('barreiras.*') ? 'active' : '' }}">
                    <span class="icon"><i class="fa fa-ban"></i></span>
                    <span class="text">Barreiras</span>
                </a>
                    </li>
                @endcan

                @can('institutional-event.index')
                    <li>
                <a href="{{ route('agenda-institucional.index') }}"
                   class="{{ request()->routeIs('agenda-institucional.*') ? 'active' : '' }}">
                    <span class="icon"><i class="ion-calendar"></i></span>
                    <span class="text">Agenda Institucional</span>
                </a>
                    </li>
                @endcan

                <li class="menu-divider"><span>Cadastros</span></li>

                <li>
            <a href="{{ route('cargos.index') }}"
               class="{{ request()->routeIs('cargos.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-briefcase"></i></span>
                <span class="text">Cargos</span>
            </a>
                </li>

                <li>
            <a href="{{ route('deficiencias.index') }}"
               class="{{ request()->routeIs('deficiencias.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-medkit"></i></span>
                <span class="text">Deficiências</span>
            </a>
                </li>

                <li>
            <a href="{{ route('recursos-de-acessibilidade.index') }}"
               class="{{ request()->routeIs('recursos-de-acessibilidade.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-man"></i></span>
                <span class="text">Recursos de Acessibilidade</span>
            </a>
                </li>

                <li>
            <a href="{{ route('categorias-de-barreiras.index') }}"
               class="{{ request()->routeIs('categorias-de-barreiras.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-grid"></i></span>
                <span class="text">Categorias de Barreiras</span>
            </a>
                </li>

                <li>
            <a href="{{ route('instituicoes.index') }}"
               class="{{ request()->routeIs('instituicoes.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-university"></i></span>
                <span class="text">Instituições</span>
            </a>
                </li>

                <li>
            <a href="{{ route('localizacoes.index') }}"
               class="{{ request()->routeIs('localizacoes.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-location"></i></span>
                <span class="text">Localizações</span>
            </a>
                </li>

                <li class="menu-divider"><span>Sistema</span></li>

                <li>
            <a href="{{ route('relatorios.index') }}"
               class="{{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-stats-bars"></i></span>
                <span class="text">Relatórios</span>
            </a>
                </li>

                <li>
            <a href="{{ route('copias-seguranca.index') }}"
               class="{{ request()->routeIs('copias-seguranca.*') ? 'active' : '' }}">
                <span class="icon"><i class="ion-ios7-cloud-download"></i></span>
                <span class="text">Backups</span>
            </a>
                </li>

                <li>
            <a href="{{ route('sobre-nos') }}">
                <span class="icon"><i class="bi bi-info-circle"></i></span>
                <span class="text">Sobre o Sistema</span>
            </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
