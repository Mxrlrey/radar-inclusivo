@extends('layouts.master')

@section('title', 'Painel de Controle')

@section('content')
    <div class="page-transition">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-title mb-1">Dashboard</h2>
                <p class="text-muted mb-0">
                    Bem-vindo(a) ao sistema Radar Inclsuivo,
                    <strong>{{ auth()->user()->person->name ?? auth()->user()->email }}</strong>.
                </p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-surface text-primary p-2 px-3 border">
                    <i class="fa fa-calendar"></i> {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>

        {{-- Primeira fileira de KPIs --}}
        <div class="kpi-grid mb-4">
            <x-stat-widget
                title="Alunos"
                :value="$totalStudents ?? 0"
                icon="ion-android-contact"
                color="primary"
                :href="route('students.index')"
            />
            <x-stat-widget
                title="Equipe"
                :value="$totalProfessionals ?? 0"
                icon="ion-android-social"
                color="info"
                :href="route('professionals.index')"
            />
            <x-stat-widget
                title="Empréstimos"
                :value="$totalLoans ?? 0"
                icon="ion-arrow-swap"
                color="success"
                :href="route('loans.index')"
            />
            <x-stat-widget
                title="Fila de Espera"
                :value="$totalWaiting ?? 0"
                icon="ion-android-timer"
                color="warning"
                :href="route('waitlists.index')"
            />
        </div>

        {{-- Segunda fileira de KPIs --}}
        <div class="kpi-grid kpi-grid--3 mb-4">
            <x-stat-widget
                title="Tecnologias Assistivas"
                :value="$totalAt ?? 0"
                icon="fa fa-microchip"
                color="primary"
                :href="route('assistive-technologies.index')"
            />
            <x-stat-widget
                title="Materiais Acessíveis"
                :value="$totalAem ?? 0"
                icon="ion-android-book"
                color="info"
                :href="route('accessible-educational-materials.index')"
            />
            <x-stat-widget
                title="Barreiras"
                :value="$totalBarriers ?? 0"
                icon="fa fa-ban"
                color="danger"
                :href="route('barriers.index')"
            />
        </div>

        {{-- Gráfico + Mapa --}}
        <div class="kpi-grid kpi-grid--chart mt-1">

            {{-- Gráfico de Pizza --}}
            <div class="kpi-chart-small card card-custom border-0 shadow-sm p-4">
                <h5 class="text-title mb-4 text-center">Status das Barreiras</h5>
                <div style="height: 250px;">
                    <canvas id="doughnutChartBarriers"></canvas>
                </div>
                <div class="mt-4 pt-3 border-top text-center">
                    <span class="text-muted small">
                        Total Identificado: <strong>{{ $totalBarriers ?? 0 }}</strong>
                    </span>
                </div>
            </div>

            {{-- Mapa com Overlay de Bloqueio e Switches --}}
            <div class="kpi-chart-large card card-custom border-0 shadow-sm d-flex flex-column">

                {{-- Container do Mapa --}}
                <div style="position: relative; flex-grow: 1; min-height: 400px;">
                    <div id="mapDashboard" style="height: 100%; border-radius: 15px 15px 0 0;"></div>

                    {{-- Overlay de bloqueio --}}
                    <div id="map-blocked-overlay" class="d-none"
                         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                background: rgba(255,255,255,0.8); z-index: 1000;
                                display: flex; align-items: center; justify-content: center;
                                border-radius: 15px 15px 0 0; cursor: not-allowed;">
                        <span class="bg-white p-3 rounded shadow-sm border text-center">
                            <i class="fa fa-lock text-danger mb-2 d-block"></i>
                            <span id="blocked-message" class="fw-bold text-muted">
                                Mapa não se aplica aos filtros selecionados.
                            </span>
                        </span>
                    </div>
                </div>

                {{-- Barra de Filtros --}}
                <div class="bg-light p-3 border-top d-flex flex-wrap justify-content-center gap-3"
                     style="border-radius: 0 0 15px 15px;">
                    <span class="small fw-bold text-muted w-100 text-center mb-1">VISUALIZAR NO MAPA:</span>

                    <div class="toggle-switch">
                        <input class="toggle-input filter-switch" type="checkbox"
                               id="switch_all" value="all" checked>
                        <label class="toggle-label" for="switch_all">Todas</label>
                    </div>

                    @foreach(App\Enums\BarrierStatus::cases() as $status)
                        <div class="toggle-switch">
                            <input class="toggle-input filter-switch status-specific"
                                   type="checkbox"
                                   id="switch_{{ $status->value }}"
                                   value="{{ $status->value }}" checked>
                            <label class="toggle-label toggle-label--{{ $status->color() }}"
                                   for="switch_{{ $status->value }}">
                                {{ $status->label() }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.dashboardData = {
            students: {{ $totalStudents ?? 0 }},
            professionals: {{ $totalProfessionals ?? 0 }},
            peiTotal: {{ $totalPeis ?? 0 }},
            peiFinished: {{ $totalPeisFinished ?? 0 }},
            peiNotFinished: {{ $totalPeisNotFinished ?? 0 }},
            barrierStatuses: @json($barrierStatusCounts ?? []),
            mapBarriers: @json($mapBarriers ?? []),
            colors: {
                primary: '#4D44B5',
                secondary: '#6c63ff',
                success: '#28c76f',
                warning: '#ff9f43',
                info: '#00cfe8',
                danger: '#ea5455',
                muted: '#6c757d'
            }
        };
    </script>
    @vite('resources/js/pages/dashboard.js')
@endpush
