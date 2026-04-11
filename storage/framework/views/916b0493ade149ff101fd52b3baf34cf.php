<?php $__env->startSection('title', 'Painel de Controle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-transition">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-title mb-1">Dashboard</h2>
                <p class="text-muted mb-0">
                    Bem-vindo(a) ao sistema Radar Inclsuivo,
                    <strong><?php echo e(auth()->user()->person->name ?? auth()->user()->email); ?></strong>.
                </p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-surface text-primary p-2 px-3 border">
                    <i class="fa fa-calendar"></i> <?php echo e(now()->format('d/m/Y')); ?>

                </span>
            </div>
        </div>

        
        <div class="kpi-grid mb-4">
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Alunos','value' => $totalStudents ?? 0,'icon' => 'ion-android-contact','color' => 'primary','href' => route('students.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Alunos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalStudents ?? 0),'icon' => 'ion-android-contact','color' => 'primary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('students.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Equipe','value' => $totalProfessionals ?? 0,'icon' => 'ion-android-social','color' => 'info','href' => route('professionals.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Equipe','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalProfessionals ?? 0),'icon' => 'ion-android-social','color' => 'info','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('professionals.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Empréstimos','value' => $totalLoans ?? 0,'icon' => 'ion-arrow-swap','color' => 'success','href' => route('loans.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Empréstimos','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalLoans ?? 0),'icon' => 'ion-arrow-swap','color' => 'success','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('loans.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Fila de Espera','value' => $totalWaiting ?? 0,'icon' => 'ion-android-timer','color' => 'warning','href' => route('waitlists.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Fila de Espera','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalWaiting ?? 0),'icon' => 'ion-android-timer','color' => 'warning','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('waitlists.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
        </div>

        
        <div class="kpi-grid kpi-grid--3 mb-4">
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Tecnologias Assistivas','value' => $totalAt ?? 0,'icon' => 'fa fa-microchip','color' => 'primary','href' => route('assistive-technologies.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tecnologias Assistivas','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalAt ?? 0),'icon' => 'fa fa-microchip','color' => 'primary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('assistive-technologies.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Materiais Acessíveis','value' => $totalAem ?? 0,'icon' => 'ion-android-book','color' => 'info','href' => route('accessible-educational-materials.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Materiais Acessíveis','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalAem ?? 0),'icon' => 'ion-android-book','color' => 'info','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('accessible-educational-materials.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
            <?php if (isset($component)) { $__componentOriginalb45570a04b4397e9a75619dfa25dae50 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb45570a04b4397e9a75619dfa25dae50 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-widget','data' => ['title' => 'Barreiras','value' => $totalBarriers ?? 0,'icon' => 'fa fa-ban','color' => 'danger','href' => route('barriers.index')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Barreiras','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalBarriers ?? 0),'icon' => 'fa fa-ban','color' => 'danger','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('barriers.index'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $attributes = $__attributesOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__attributesOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb45570a04b4397e9a75619dfa25dae50)): ?>
<?php $component = $__componentOriginalb45570a04b4397e9a75619dfa25dae50; ?>
<?php unset($__componentOriginalb45570a04b4397e9a75619dfa25dae50); ?>
<?php endif; ?>
        </div>

        
        <div class="kpi-grid kpi-grid--chart mt-1">

            
            <div class="kpi-chart-small card card-custom border-0 shadow-sm p-4">
                <h5 class="text-title mb-4 text-center">Status das Barreiras</h5>
                <div style="height: 250px;">
                    <canvas id="doughnutChartBarriers"></canvas>
                </div>
                <div class="mt-4 pt-3 border-top text-center">
                    <span class="text-muted small">
                        Total Identificado: <strong><?php echo e($totalBarriers ?? 0); ?></strong>
                    </span>
                </div>
            </div>

            
            <div class="kpi-chart-large card card-custom border-0 shadow-sm d-flex flex-column">

                
                <div style="position: relative; flex-grow: 1; min-height: 400px;">
                    <div id="mapDashboard" style="height: 100%; border-radius: 15px 15px 0 0;"></div>

                    
                    <div id="map-blocked-overlay" class="d-none"
                         style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                background: rgba(255,255,255,0.8); z-index: 1000;
                                display: flex; align-items: center; justify-content: center;
                                border-radius: 15px 15px 0 0; cursor: not-allowed;">
                        <span class="bg-white p-3 rounded shadow-sm border text-center">
                            <i class="fas fa-lock text-danger mb-2 d-block"></i>
                            <span id="blocked-message" class="fw-bold text-muted">
                                Mapa não se aplica aos filtros selecionados.
                            </span>
                        </span>
                    </div>
                </div>

                
                <div class="bg-light p-3 border-top d-flex flex-wrap justify-content-center gap-3"
                     style="border-radius: 0 0 15px 15px;">
                    <span class="small fw-bold text-muted w-100 text-center mb-1">VISUALIZAR NO MAPA:</span>

                    <div class="toggle-switch">
                        <input class="toggle-input filter-switch" type="checkbox"
                               id="switch_all" value="all" checked>
                        <label class="toggle-label" for="switch_all">Todas</label>
                    </div>

                    <?php $__currentLoopData = App\Enums\BarrierStatus::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="toggle-switch">
                            <input class="toggle-input filter-switch status-specific"
                                   type="checkbox"
                                   id="switch_<?php echo e($status->value); ?>"
                                   value="<?php echo e($status->value); ?>" checked>
                            <label class="toggle-label toggle-label--<?php echo e($status->color()); ?>"
                                   for="switch_<?php echo e($status->value); ?>">
                                <?php echo e($status->label()); ?>

                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.dashboardData = {
            students: <?php echo e($totalStudents ?? 0); ?>,
            professionals: <?php echo e($totalProfessionals ?? 0); ?>,
            peiTotal: <?php echo e($totalPeis ?? 0); ?>,
            peiFinished: <?php echo e($totalPeisFinished ?? 0); ?>,
            peiNotFinished: <?php echo e($totalPeisNotFinished ?? 0); ?>,
            barrierStatuses: <?php echo json_encode($barrierStatusCounts ?? [], 15, 512) ?>,
            mapBarriers: <?php echo json_encode($mapBarriers ?? [], 15, 512) ?>,
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
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/pages/dashboard.js'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/dashboard.blade.php ENDPATH**/ ?>