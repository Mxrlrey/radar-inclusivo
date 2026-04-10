<?php $__env->startSection('title', 'Relatar Barreira'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-5">
        <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
            'Home' => route('dashboard'),
            'Barreiras' => route('barriers.index'),
            'Cadastrar' => null
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            'Home' => route('dashboard'),
            'Barreiras' => route('barriers.index'),
            'Cadastrar' => null
        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
    </div>

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <div>
            <h2 class="text-title">Relatar Barreira de Acessibilidade</h2>
            <p class="text-muted">Registre um ponto de obstrução ou dificuldade encontrada no campus.</p>
        </div>

        <div>
            <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => ''.e(route('barriers.index')).'','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('barriers.index')).'','variant' => 'secondary']); ?>
                <i class="fas fa-times"></i> Cancelar
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $attributes = $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $component = $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <?php if (isset($component)) { $__componentOriginal793f3e81513a4cf44641d9ad56270e14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal793f3e81513a4cf44641d9ad56270e14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.form-card','data' => ['action' => ''.e(route('barriers.store')).'','method' => 'POST','enctype' => 'multipart/form-data']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.form-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['action' => ''.e(route('barriers.store')).'','method' => 'POST','enctype' => 'multipart/form-data']); ?>
            <?php echo csrf_field(); ?>

            <div class="col-lg-5 border-end">

                <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Detalhes da Ocorrência']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detalhes da Ocorrência']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $attributes = $__attributesOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $component = $__componentOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__componentOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>

                <div class="px-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <?php if (isset($component)) { $__componentOriginal4fb6044c7ed6b655352043ff774efcd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input','data' => ['name' => 'name','label' => 'Título do Relato','required' => true,'value' => old('name'),'placeholder' => 'Ex: Calçada irregular']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'name','label' => 'Título do Relato','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('name')),'placeholder' => 'Ex: Calçada irregular']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $attributes = $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $component = $__componentOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <?php if (isset($component)) { $__componentOriginal4fb6044c7ed6b655352043ff774efcd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input','data' => ['type' => 'date','name' => 'identified_at','label' => 'Data','required' => true,'value' => old('identified_at', now()->format('Y-m-d'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','name' => 'identified_at','label' => 'Data','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('identified_at', now()->format('Y-m-d')))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $attributes = $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $component = $__componentOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'priority','label' => 'Prioridade','options' => $priorities,'selected' => old('priority', 'medium')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'priority','label' => 'Prioridade','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($priorities),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('priority', 'medium'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'barrier_category_id','label' => 'Categoria','required' => true,'options' => $categories->pluck('name', 'id'),'selected' => old('barrier_category_id'),'extraAttributes' => 'data-blocks-map-options']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'barrier_category_id','label' => 'Categoria','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($categories->pluck('name', 'id')),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('barrier_category_id')),'extraAttributes' => 'data-blocks-map-options']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'institution_id','id' => 'institution_select','label' => 'Campus / Unidade','required' => true,'options' => $institutions->pluck('name','id'),'selected' => old('institution_id'),'resourceObjects' => $institutions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'institution_id','id' => 'institution_select','label' => 'Campus / Unidade','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($institutions->pluck('name','id')),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('institution_id')),'resourceObjects' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($institutions)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'location_id','id' => 'location_select','label' => 'Local / Ponto de Referência','options' => [],'selected' => old('location_id')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'location_id','id' => 'location_select','label' => 'Local / Ponto de Referência','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('location_id'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                        </div>

                        <div id="location_wrapper"
                             class="<?php echo e(old('institution_id') ? '' : 'd-none'); ?> col-md-12 mb-3 mt-3">
                            <?php if (isset($component)) { $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.textarea','data' => ['name' => 'location_specific_details','label' => 'Complemento','rows' => '3','placeholder' => 'Descreva melhor onde a barreira está localizada...','value' => old('location_specific_details')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'location_specific_details','label' => 'Complemento','rows' => '3','placeholder' => 'Descreva melhor onde a barreira está localizada...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('location_specific_details'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $attributes = $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $component = $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-3 px-4 mt-3">
                    <?php if (isset($component)) { $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.textarea','data' => ['name' => 'description','label' => 'Descrição Detalhada','required' => true,'rows' => '3','placeholder' => 'Explique o problema encontrado...','value' => old('description')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'description','label' => 'Descrição Detalhada','required' => true,'rows' => '3','placeholder' => 'Explique o problema encontrado...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('description'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $attributes = $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $component = $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
                </div>

                <div class="px-4">
                    <div class="bg-light p-3 rounded mb-4 border shadow-sm">
                        <label class="fw-bold text-purple-dark small uppercase mb-3 d-block">Pessoa Impactada</label>
                        <div class="d-flex flex-column gap-2">
                            <?php if (isset($component)) { $__componentOriginala6191e173a29d7cb3002715ea2e926a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6191e173a29d7cb3002715ea2e926a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.checkbox','data' => ['name' => 'is_anonymous','id' => 'is_anonymous','label' => 'Relato Anônimo','checked' => old('is_anonymous')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_anonymous','id' => 'is_anonymous','label' => 'Relato Anônimo','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('is_anonymous'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $attributes = $__attributesOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $component = $__componentOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__componentOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
                            <div id="wrapper_not_applicable">
                                <?php if (isset($component)) { $__componentOriginala6191e173a29d7cb3002715ea2e926a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6191e173a29d7cb3002715ea2e926a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.checkbox','data' => ['name' => 'not_applicable','id' => 'not_applicable','label' => 'Relato Geral','checked' => old('not_applicable')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'not_applicable','id' => 'not_applicable','label' => 'Relato Geral','checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('not_applicable'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $attributes = $__attributesOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $component = $__componentOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__componentOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
                            </div>
                        </div>

                        <div id="identification_fields" class="mt-3">
                            <div id="person_selects" class="<?php echo e(old('not_applicable') ? 'd-none' : ''); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'affected_student_id','label' => 'Estudante','options' => $students,'selected' => old('affected_student_id')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'affected_student_id','label' => 'Estudante','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($students),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('affected_student_id'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                                    </div>

                                    <div class="col-md-6">
                                        <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'affected_professional_id','label' => 'Profissional','options' => $professionals,'selected' => old('affected_professional_id')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'affected_professional_id','label' => 'Profissional','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($professionals),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('affected_professional_id'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div id="manual_person_data" class="<?php echo e(old('not_applicable') ? '' : 'd-none'); ?> mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php if (isset($component)) { $__componentOriginal4fb6044c7ed6b655352043ff774efcd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input','data' => ['name' => 'affected_person_name','label' => 'Nome','value' => old('affected_person_name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'affected_person_name','label' => 'Nome','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('affected_person_name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $attributes = $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $component = $__componentOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (isset($component)) { $__componentOriginal4fb6044c7ed6b655352043ff774efcd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input','data' => ['name' => 'affected_person_role','label' => 'Cargo','value' => old('affected_person_role')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'affected_person_role','label' => 'Cargo','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('affected_person_role'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $attributes = $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $component = $__componentOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-4 px-4">
                    <label class="form-label fw-bold text-purple-dark">Deficiências Relacionadas</label>
                    <div class="d-flex flex-wrap gap-4 p-3 border rounded bg-light max-h-40 overflow-y-auto custom-scrollbar">
                        <?php $__currentLoopData = $deficiencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginala6191e173a29d7cb3002715ea2e926a8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala6191e173a29d7cb3002715ea2e926a8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.checkbox','data' => ['name' => 'deficiencies[]','id' => 'def_'.e($def->id).'','value' => $def->id,'label' => $def->name,'checked' => in_array($def->id, old('deficiencies', [])),'class' => 'mb-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'deficiencies[]','id' => 'def_'.e($def->id).'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($def->id),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($def->name),'checked' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(in_array($def->id, old('deficiencies', []))),'class' => 'mb-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $attributes = $__attributesOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__attributesOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala6191e173a29d7cb3002715ea2e926a8)): ?>
<?php $component = $__componentOriginala6191e173a29d7cb3002715ea2e926a8; ?>
<?php unset($__componentOriginala6191e173a29d7cb3002715ea2e926a8); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <input type="hidden" name="is_active" value="1">
            </div>

            <div class="col-lg-7 bg-light px-0">

                <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Localização no Mapa','id' => 'map-section-title']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Localização no Mapa','id' => 'map-section-title']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $attributes = $__attributesOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $component = $__componentOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__componentOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>

                <div class="sticky-top" style="top:20px; z-index:1;">
                    <div class="mb-4">
                        <div class="mb-3 px-4 d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="btn-toggle-locations" checked style="cursor: pointer;">
                                <label class="form-check-label small text-muted fw-bold" for="btn-toggle-locations" style="cursor: pointer;">
                                    Exibir Locais (Cinza)
                                </label>
                            </div>
                        </div>

                        <div style="position: relative;">
                            <?php if (isset($component)) { $__componentOriginalf079a13fa647ce3c30044477755c2c18 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf079a13fa647ce3c30044477755c2c18 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.maps.barrier','data' => ['institution' => $selectedInstitution,'height' => '450px','label' => 'Localização da Barreira']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.maps.barrier'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['institution' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedInstitution),'height' => '450px','label' => 'Localização da Barreira']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf079a13fa647ce3c30044477755c2c18)): ?>
<?php $attributes = $__attributesOriginalf079a13fa647ce3c30044477755c2c18; ?>
<?php unset($__attributesOriginalf079a13fa647ce3c30044477755c2c18); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf079a13fa647ce3c30044477755c2c18)): ?>
<?php $component = $__componentOriginalf079a13fa647ce3c30044477755c2c18; ?>
<?php unset($__componentOriginalf079a13fa647ce3c30044477755c2c18); ?>
<?php endif; ?>

                            <div id="map-blocked-overlay"
                                 class="d-none"
                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 1000; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #333; pointer-events: none; border-radius: 0.375rem;">
                                <span id="map-blocked-text" class="bg-white p-3 rounded shadow-sm"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">

                        <?php if (isset($component)) { $__componentOriginal289e7d811e995d6c1146b71ee55f1359 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal289e7d811e995d6c1146b71ee55f1359 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.section','data' => ['title' => 'Vistoria Inicial']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Vistoria Inicial']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $attributes = $__attributesOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__attributesOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal289e7d811e995d6c1146b71ee55f1359)): ?>
<?php $component = $__componentOriginal289e7d811e995d6c1146b71ee55f1359; ?>
<?php unset($__componentOriginal289e7d811e995d6c1146b71ee55f1359); ?>
<?php endif; ?>

                        <div class="px-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal7041cc63efd62f0450fe4bb37aadf484 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.select','data' => ['name' => 'status','id' => 'status_select','label' => 'Status Inicial','required' => true,'options' => $barrierStatuses,'selected' => old('status', $defaultStatus)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.select'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','id' => 'status_select','label' => 'Status Inicial','required' => true,'options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($barrierStatuses),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('status', $defaultStatus))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $attributes = $__attributesOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__attributesOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484)): ?>
<?php $component = $__componentOriginal7041cc63efd62f0450fe4bb37aadf484; ?>
<?php unset($__componentOriginal7041cc63efd62f0450fe4bb37aadf484); ?>
<?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal4fb6044c7ed6b655352043ff774efcd0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.input','data' => ['name' => 'inspection_date','label' => 'Data da Vistoria','type' => 'date','required' => true,'value' => old('inspection_date', date('Y-m-d'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'inspection_date','label' => 'Data da Vistoria','type' => 'date','required' => true,'value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('inspection_date', date('Y-m-d')))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $attributes = $__attributesOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__attributesOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0)): ?>
<?php $component = $__componentOriginal4fb6044c7ed6b655352043ff774efcd0; ?>
<?php unset($__componentOriginal4fb6044c7ed6b655352043ff774efcd0); ?>
<?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal2e12cc6bcd99724f07f7413aabc71071 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e12cc6bcd99724f07f7413aabc71071 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.image-uploader','data' => ['name' => 'images[]','label' => 'Fotos de Evidência','existingImages' => old('images', [])]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.image-uploader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'images[]','label' => 'Fotos de Evidência','existingImages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('images', []))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e12cc6bcd99724f07f7413aabc71071)): ?>
<?php $attributes = $__attributesOriginal2e12cc6bcd99724f07f7413aabc71071; ?>
<?php unset($__attributesOriginal2e12cc6bcd99724f07f7413aabc71071); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e12cc6bcd99724f07f7413aabc71071)): ?>
<?php $component = $__componentOriginal2e12cc6bcd99724f07f7413aabc71071; ?>
<?php unset($__componentOriginal2e12cc6bcd99724f07f7413aabc71071); ?>
<?php endif; ?>
                                </div>

                                <div class="col-md-12">
                                    <?php if (isset($component)) { $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.forms.textarea','data' => ['name' => 'inspection_description','id' => 'inspection_description','label' => 'Parecer Técnico / Notas da Vistoria','rows' => '3','placeholder' => 'Descreva detalhes técnicos sobre a obstrução ou estado do local...','value' => old('inspection_description')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('forms.textarea'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'inspection_description','id' => 'inspection_description','label' => 'Parecer Técnico / Notas da Vistoria','rows' => '3','placeholder' => 'Descreva detalhes técnicos sobre a obstrução ou estado do local...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('inspection_description'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $attributes = $__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__attributesOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5)): ?>
<?php $component = $__componentOriginalea7b7095850fe8bc9657025b89ccf5a5; ?>
<?php unset($__componentOriginalea7b7095850fe8bc9657025b89ccf5a5); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-3 border-top pt-4 px-4 pb-4 mt-4 bg-white">
                <?php if (isset($component)) { $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.link-button','data' => ['href' => ''.e(route('barriers.index')).'','variant' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.link-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('barriers.index')).'','variant' => 'secondary']); ?>
                    <i class="fas fa-times"></i> Cancelar
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $attributes = $__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__attributesOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7)): ?>
<?php $component = $__componentOriginal5cd5668654a8f1a77aef15d054cb27b7; ?>
<?php unset($__componentOriginal5cd5668654a8f1a77aef15d054cb27b7); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal8c5c9de60cf694be03f8e7259692a21b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.buttons.submit-button','data' => ['type' => 'submit','class' => 'btn-action new submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('buttons.submit-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'btn-action new submit']); ?>
                    <i class="fas fa-save mr-2"></i> Salvar
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c5c9de60cf694be03f8e7259692a21b)): ?>
<?php $attributes = $__attributesOriginal8c5c9de60cf694be03f8e7259692a21b; ?>
<?php unset($__attributesOriginal8c5c9de60cf694be03f8e7259692a21b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c5c9de60cf694be03f8e7259692a21b)): ?>
<?php $component = $__componentOriginal8c5c9de60cf694be03f8e7259692a21b; ?>
<?php unset($__componentOriginal8c5c9de60cf694be03f8e7259692a21b); ?>
<?php endif; ?>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal793f3e81513a4cf44641d9ad56270e14)): ?>
<?php $attributes = $__attributesOriginal793f3e81513a4cf44641d9ad56270e14; ?>
<?php unset($__attributesOriginal793f3e81513a4cf44641d9ad56270e14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal793f3e81513a4cf44641d9ad56270e14)): ?>
<?php $component = $__componentOriginal793f3e81513a4cf44641d9ad56270e14; ?>
<?php unset($__componentOriginal793f3e81513a4cf44641d9ad56270e14); ?>
<?php endif; ?>
    </div>

    <?php $__env->startPush('styles'); ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            window.categoriesData = <?php echo json_encode($categories->mapWithKeys(fn($cat) => [$cat->id => $cat->blocks_map]), 15, 512) ?>;
            window.institutionsData = <?php echo json_encode($institutions, 15, 512) ?>;
            window.oldLocationId = "<?php echo e(old('location_id')); ?>";
        </script>
    <?php $__env->stopPush(); ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/pages/barriers.js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/resources/views/pages/barriers/create.blade.php ENDPATH**/ ?>