<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// COPIAS DE SEGURANCA
// ============================================================

use App\Http\Controllers\BackupController;

Route::middleware(['auth'])->prefix('copias-seguranca')->name('copias-seguranca.')->group(function () {
    Route::get('/', [BackupController::class, 'index'])->name('index');
    Route::post('/salvar', [BackupController::class, 'store'])->name('salvar');
    Route::get('/{id}', [BackupController::class, 'show'])->name('visualizar');
    Route::get('/{id}/baixar', [BackupController::class, 'download'])->name('baixar');
    Route::delete('/{id}', [BackupController::class, 'destroy'])->name('excluir');
    Route::post('/{id}/restaurar', [BackupController::class, 'restore'])->name('restaurar');
    Route::post('/enviar', [BackupController::class, 'upload'])->name('enviar');
});

// ============================================================
// RADAR INCLUSIVO
// ============================================================

use App\Http\Controllers\AccessibilityFeatureController;
use App\Http\Controllers\AccessibleEducationalMaterialController;
use App\Http\Controllers\AssistiveTechnologyController;
use App\Http\Controllers\BarrierCategoryController;
use App\Http\Controllers\BarrierController;
use App\Http\Controllers\InstitutionalEventController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\Logs\AssistiveTechnologyLogController;
use App\Http\Controllers\Logs\AccessibleEducationalMaterialLogController;

// --- Administracao ---
Route::middleware(['auth', 'admin'])->prefix('administracao')->group(function () {

    // Categorias de Barreiras
    Route::prefix('categorias-de-barreiras')->name('categorias-de-barreiras.')->group(function () {
        Route::get('/', [BarrierCategoryController::class, 'index'])->name('index');
        Route::get('/criar', [BarrierCategoryController::class, 'create'])->name('criar');
        Route::post('/salvar', [BarrierCategoryController::class, 'store'])->name('salvar');
        Route::get('/{barrierCategory}', [BarrierCategoryController::class, 'show'])->name('visualizar');
        Route::get('/{barrierCategory}/editar', [BarrierCategoryController::class, 'edit'])->name('editar');
        Route::put('/{barrierCategory}', [BarrierCategoryController::class, 'update'])->name('atualizar');
        Route::delete('/{barrierCategory}', [BarrierCategoryController::class, 'destroy'])->name('excluir');
    });

    // Instituicoes
    Route::prefix('instituicoes')->name('instituicoes.')->group(function () {
        Route::get('/', [InstitutionController::class, 'index'])->name('index');
        Route::get('/criar', [InstitutionController::class, 'create'])->name('criar');
        Route::post('/salvar', [InstitutionController::class, 'store'])->name('salvar');
        Route::get('/{institution}', [InstitutionController::class, 'show'])->name('visualizar');
        Route::get('/{institution}/editar', [InstitutionController::class, 'edit'])->name('editar');
        Route::put('/{institution}', [InstitutionController::class, 'update'])->name('atualizar');
        Route::delete('/{institution}', [InstitutionController::class, 'destroy'])->name('excluir');
    });

    // Localizacoes
    Route::prefix('localizacoes')->name('localizacoes.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::get('/criar', [LocationController::class, 'create'])->name('criar');
        Route::post('/salvar', [LocationController::class, 'store'])->name('salvar');
        Route::get('/{location}', [LocationController::class, 'show'])->name('visualizar');
        Route::get('/{location}/editar', [LocationController::class, 'edit'])->name('editar');
        Route::put('/{location}', [LocationController::class, 'update'])->name('atualizar');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('excluir');
    });

    // Recursos de Acessibilidade
    Route::prefix('recursos-de-acessibilidade')->name('recursos-de-acessibilidade.')->group(function () {
        Route::get('/', [AccessibilityFeatureController::class, 'index'])->name('index');
        Route::get('/criar', [AccessibilityFeatureController::class, 'create'])->name('criar');
        Route::post('/salvar', [AccessibilityFeatureController::class, 'store'])->name('salvar');
        Route::get('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'show'])->name('visualizar');
        Route::get('/{accessibilityFeature}/editar', [AccessibilityFeatureController::class, 'edit'])->name('editar');
        Route::put('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'update'])->name('atualizar');
        Route::delete('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'destroy'])->name('excluir');
    });
});

// --- Operacional ---
Route::middleware(['auth'])->group(function () {

    // Tecnologias Assistivas
    Route::prefix('tecnologias-assistivas')->name('tecnologias-assistivas.')->group(function () {
        Route::get('/', [AssistiveTechnologyController::class, 'index'])->name('index')->middleware('can:assistive-technology.index');
        Route::get('/criar', [AssistiveTechnologyController::class, 'create'])->name('criar')->middleware('can:assistive-technology.create');
        Route::post('/salvar', [AssistiveTechnologyController::class, 'store'])->name('salvar')->middleware('can:assistive-technology.store');
        Route::get('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'show'])->name('visualizar')->middleware('can:assistive-technology.show');
        Route::get('/{assistiveTechnology}/inspecao/{inspection}', [AssistiveTechnologyController::class, 'showInspection'])->name('inspecao.visualizar')->middleware('can:assistive-technology.inspection.show');
        Route::get('/{assistiveTechnology}/editar', [AssistiveTechnologyController::class, 'edit'])->name('editar')->middleware('can:assistive-technology.edit');
        Route::put('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'update'])->name('atualizar')->middleware('can:assistive-technology.update');
        Route::delete('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'destroy'])->name('excluir')->middleware('can:assistive-technology.destroy');
        Route::get('/{assistiveTechnology}/pdf', [AssistiveTechnologyController::class, 'generatePdf'])->name('pdf')->middleware('can:assistive-technology.pdf');
        Route::get('/{assistiveTechnology}/registros', [AssistiveTechnologyLogController::class, 'index'])->name('registros')->middleware('can:assistive-technology.logs');
    });

    // Barreiras
    Route::prefix('barreiras')->name('barreiras.')->group(function () {
        Route::get('/', [BarrierController::class, 'index'])->name('index')->middleware('can:barrier.index');
        Route::get('/criar', [BarrierController::class, 'create'])->name('criar')->middleware('can:barrier.create');
        Route::post('/salvar', [BarrierController::class, 'store'])->name('salvar')->middleware('can:barrier.store');
        Route::get('/{barrier}', [BarrierController::class, 'show'])->name('visualizar')->middleware('can:barrier.show');
        Route::get('/{barrier}/inspecao/{inspection}', [BarrierController::class, 'showInspection'])->name('inspecao.visualizar')->middleware('can:barrier.inspection.show');
        Route::get('/{barrier}/editar', [BarrierController::class, 'edit'])->name('editar')->middleware('can:barrier.edit');
        Route::put('/{barrier}', [BarrierController::class, 'update'])->name('atualizar')->middleware('can:barrier.update');
        Route::delete('/{barrier}', [BarrierController::class, 'destroy'])->name('excluir')->middleware('can:barrier.destroy');
        Route::get('/{barrier}/pdf', [BarrierController::class, 'generatePdf'])->name('pdf')->middleware('can:barrier.pdf');
    });

    // Materiais Pedagogicos Acessiveis
    Route::prefix('materiais-pedagogicos-acessiveis')->name('materiais-pedagogicos-acessiveis.')->group(function () {
        Route::get('/', [AccessibleEducationalMaterialController::class, 'index'])->name('index')->middleware('can:material.index');
        Route::get('/criar', [AccessibleEducationalMaterialController::class, 'create'])->name('criar')->middleware('can:material.create');
        Route::post('/salvar', [AccessibleEducationalMaterialController::class, 'store'])->name('salvar')->middleware('can:material.store');
        Route::get('/{material}', [AccessibleEducationalMaterialController::class, 'show'])->name('visualizar')->middleware('can:material.show');
        Route::get('/{material}/inspecao/{inspection}', [AccessibleEducationalMaterialController::class, 'showInspection'])->name('inspecao.visualizar')->middleware('can:material.inspection.show');
        Route::get('/{material}/editar', [AccessibleEducationalMaterialController::class, 'edit'])->name('editar')->middleware('can:material.edit');
        Route::put('/{material}', [AccessibleEducationalMaterialController::class, 'update'])->name('atualizar')->middleware('can:material.update');
        Route::delete('/{material}', [AccessibleEducationalMaterialController::class, 'destroy'])->name('excluir')->middleware('can:material.destroy');
        Route::get('/{material}/pdf', [AccessibleEducationalMaterialController::class, 'generatePdf'])->name('pdf')->middleware('can:material.pdf');
        Route::get('/{material}/registros', [AccessibleEducationalMaterialLogController::class, 'index'])->name('registros')->middleware('can:material.logs');
    });

    // Agenda Institucional
    Route::prefix('agenda-institucional')->name('agenda-institucional.')->group(function () {
        Route::get('/', [InstitutionalEventController::class, 'index'])->name('index')->middleware('can:institutional-event.index');
        Route::get('/criar', [InstitutionalEventController::class, 'create'])->name('criar')->middleware('can:institutional-event.create');
        Route::post('/salvar', [InstitutionalEventController::class, 'store'])->name('salvar')->middleware('can:institutional-event.store');
        Route::get('/{event}', [InstitutionalEventController::class, 'show'])->name('visualizar')->middleware('can:institutional-event.show');
        Route::get('/{event}/editar', [InstitutionalEventController::class, 'edit'])->name('editar')->middleware('can:institutional-event.edit');
        Route::put('/{event}', [InstitutionalEventController::class, 'update'])->name('atualizar')->middleware('can:institutional-event.update');
        Route::delete('/{event}', [InstitutionalEventController::class, 'destroy'])->name('excluir')->middleware('can:institutional-event.destroy');
        Route::get('/{event}/pdf', [InstitutionalEventController::class, 'generatePdf'])->name('pdf')->middleware('can:institutional-event.pdf');
    });

    // Emprestimos
    Route::prefix('emprestimos')->name('emprestimos.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index')->middleware('can:loan.index');
        Route::get('/criar', [LoanController::class, 'create'])->name('criar')->middleware('can:loan.create');
        Route::post('/salvar', [LoanController::class, 'store'])->name('salvar')->middleware('can:loan.store');
        Route::get('/{loan}', [LoanController::class, 'show'])->name('visualizar')->middleware('can:loan.show');
        Route::get('/{loan}/editar', [LoanController::class, 'edit'])->name('editar')->middleware('can:loan.edit');
        Route::put('/{loan}', [LoanController::class, 'update'])->name('atualizar')->middleware('can:loan.update');
        Route::patch('/{loan}/devolver', [LoanController::class, 'returnItem'])->name('devolver')->middleware('can:loan.return');
        Route::delete('/{loan}', [LoanController::class, 'destroy'])->name('excluir')->middleware('can:loan.destroy');
        Route::get('/{loan}/pdf', [LoanController::class, 'generatePdf'])->name('pdf')->middleware('can:loan.pdf');
    });

    // Fila de Espera
    Route::prefix('filas-de-espera')->name('filas-de-espera.')->group(function () {
        Route::get('/', [WaitlistController::class, 'index'])->name('index')->middleware('can:waitlist.index');
        Route::get('/criar', [WaitlistController::class, 'create'])->name('criar')->middleware('can:waitlist.create');
        Route::post('/salvar', [WaitlistController::class, 'store'])->name('salvar')->middleware('can:waitlist.store');
        Route::get('/{waitlist}', [WaitlistController::class, 'show'])->name('visualizar')->middleware('can:waitlist.show');
        Route::get('/{waitlist}/editar', [WaitlistController::class, 'edit'])->name('editar')->middleware('can:waitlist.edit');
        Route::put('/{waitlist}', [WaitlistController::class, 'update'])->name('atualizar')->middleware('can:waitlist.update');
        Route::delete('/{waitlist}', [WaitlistController::class, 'destroy'])->name('excluir')->middleware('can:waitlist.destroy');
        Route::patch('/{waitlist}/cancelar', [WaitlistController::class, 'cancel'])->name('cancelar')->middleware('can:waitlist.cancel');
        Route::get('/{waitlist}/pdf', [WaitlistController::class, 'generatePdf'])->name('pdf')->middleware('can:waitlist.pdf');
    });
});

// ============================================================
// ATENDIMENTO EDUCACIONAL ESPECIALIZADO (AEE)
// ============================================================

use App\Http\Controllers\DeficiencyController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\StudentController;

// --- Administracao ---
Route::middleware(['auth', 'admin'])->prefix('administracao')->group(function () {

    // Pessoas
    Route::prefix('pessoas')->name('pessoas.')->group(function () {
        Route::get('/', [PersonController::class, 'index'])->name('index')->middleware('can:people.view');
        Route::get('/criar', [PersonController::class, 'create'])->name('criar')->middleware('can:people.create');
        Route::post('/salvar', [PersonController::class, 'store'])->name('salvar')->middleware('can:people.create');
        Route::get('/{person}/editar', [PersonController::class, 'edit'])->name('editar')->middleware('can:people.update');
        Route::put('/{person}', [PersonController::class, 'update'])->name('atualizar')->middleware('can:people.update');
        Route::delete('/{person}', [PersonController::class, 'destroy'])->name('excluir')->middleware('can:people.delete');
    });

    // Deficiencias
    Route::prefix('deficiencias')->name('deficiencias.')->group(function () {
        Route::get('/', [DeficiencyController::class, 'index'])->name('index')->middleware('can:deficiency.view');
        Route::get('/criar', [DeficiencyController::class, 'create'])->name('criar')->middleware('can:deficiency.create');
        Route::post('/salvar', [DeficiencyController::class, 'store'])->name('salvar')->middleware('can:deficiency.create');
        Route::get('/{deficiency}/visualizar', [DeficiencyController::class, 'show'])->name('visualizar')->middleware('can:deficiency.view');
        Route::get('/{deficiency}/editar', [DeficiencyController::class, 'edit'])->name('editar')->middleware('can:deficiency.update');
        Route::put('/{deficiency}', [DeficiencyController::class, 'update'])->name('atualizar')->middleware('can:deficiency.update');
        Route::patch('/{deficiency}/desativar', [DeficiencyController::class, 'toggleActive'])->name('desativar')->middleware('can:deficiency.update');
        Route::delete('/{deficiency}', [DeficiencyController::class, 'destroy'])->name('excluir')->middleware('can:deficiency.delete');
    });

    // Cargos
    Route::prefix('cargos')->name('cargos.')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('index')->middleware('can:position.view');
        Route::get('/criar', [PositionController::class, 'create'])->name('criar')->middleware('can:position.create');
        Route::post('/salvar', [PositionController::class, 'store'])->name('salvar')->middleware('can:position.create');
        Route::get('/{position}/visualizar', [PositionController::class, 'show'])->name('visualizar')->middleware('can:position.view');
        Route::get('/{position}/editar', [PositionController::class, 'edit'])->name('editar')->middleware('can:position.update');
        Route::put('/{position}', [PositionController::class, 'update'])->name('atualizar')->middleware('can:position.update');
        Route::patch('/{position}/desativar', [PositionController::class, 'toggleActive'])->name('desativar')->middleware('can:position.update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('excluir')->middleware('can:position.delete');
    });
});

// --- Operacional ---
Route::middleware(['auth'])->group(function () {

    // Estudantes
    Route::prefix('estudantes')->name('estudantes.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('can:student.view');
        Route::get('/criar', [StudentController::class, 'create'])->name('criar')->middleware('can:student.create');
        Route::post('/salvar', [StudentController::class, 'store'])->name('salvar')->middleware('can:student.create');
        Route::get('/{student}/visualizar', [StudentController::class, 'show'])->name('visualizar')->middleware('can:student.view');
        Route::get('/{student}/editar', [StudentController::class, 'edit'])->name('editar')->middleware('can:student.update');
        Route::put('/{student}', [StudentController::class, 'update'])->name('atualizar')->middleware('can:student.update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('excluir')->middleware('can:student.delete');
    });

    // Profissionais
    Route::prefix('profissionais')->name('profissionais.')->group(function () {
        Route::get('/', [ProfessionalController::class, 'index'])->name('index')->middleware('can:professional.index');
        Route::get('/criar', [ProfessionalController::class, 'create'])->name('criar')->middleware('can:professional.create');
        Route::post('/salvar', [ProfessionalController::class, 'store'])->name('salvar')->middleware('can:professional.create');
        Route::get('/{professional}/visualizar', [ProfessionalController::class, 'show'])->name('visualizar')->middleware('can:professional.show');
        Route::get('/{professional}/editar', [ProfessionalController::class, 'edit'])->name('editar')->middleware('can:professional.update');
        Route::put('/{professional}', [ProfessionalController::class, 'update'])->name('atualizar')->middleware('can:professional.update');
        Route::delete('/{professional}', [ProfessionalController::class, 'destroy'])->name('excluir')->middleware('can:professional.delete');
    });
});
