<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// BACKUP
// ============================================================

use App\Http\Controllers\BackupController;

Route::middleware(['auth'])->prefix('backups')->name('backups.')->group(function () {
    Route::get('/', [BackupController::class, 'index'])->name('index');
    Route::post('/store', [BackupController::class, 'store'])->name('store');
    Route::get('/{id}', [BackupController::class, 'show'])->name('show');
    Route::get('/{id}/download', [BackupController::class, 'download'])->name('download');
    Route::delete('/{id}', [BackupController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/restore', [BackupController::class, 'restore'])->name('restore');
    Route::post('/upload', [BackupController::class, 'upload'])->name('upload');
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

// --- Admin ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Categorias de Barreiras
    Route::prefix('barrier-categories')->name('barrier-categories.')->group(function () {
        Route::get('/', [BarrierCategoryController::class, 'index'])->name('index');
        Route::get('/create', [BarrierCategoryController::class, 'create'])->name('create');
        Route::post('/store', [BarrierCategoryController::class, 'store'])->name('store');
        Route::get('/{barrierCategory}', [BarrierCategoryController::class, 'show'])->name('show');
        Route::get('/{barrierCategory}/edit', [BarrierCategoryController::class, 'edit'])->name('edit');
        Route::put('/{barrierCategory}', [BarrierCategoryController::class, 'update'])->name('update');
        Route::delete('/{barrierCategory}', [BarrierCategoryController::class, 'destroy'])->name('destroy');
    });

    // Instituições
    Route::prefix('institutions')->name('institutions.')->group(function () {
        Route::get('/', [InstitutionController::class, 'index'])->name('index');
        Route::get('/create', [InstitutionController::class, 'create'])->name('create');
        Route::post('/store', [InstitutionController::class, 'store'])->name('store');
        Route::get('/{institution}', [InstitutionController::class, 'show'])->name('show');
        Route::get('/{institution}/edit', [InstitutionController::class, 'edit'])->name('edit');
        Route::put('/{institution}', [InstitutionController::class, 'update'])->name('update');
        Route::delete('/{institution}', [InstitutionController::class, 'destroy'])->name('destroy');
    });

    // Localizações
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::get('/create', [LocationController::class, 'create'])->name('create');
        Route::post('/store', [LocationController::class, 'store'])->name('store');
        Route::get('/{location}', [LocationController::class, 'show'])->name('show');
        Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('edit');
        Route::put('/{location}', [LocationController::class, 'update'])->name('update');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
    });

    // Recursos de Acessibilidade
    Route::prefix('accessibility-features')->name('accessibility-features.')->group(function () {
        Route::get('/', [AccessibilityFeatureController::class, 'index'])->name('index');
        Route::get('/create', [AccessibilityFeatureController::class, 'create'])->name('create');
        Route::post('/store', [AccessibilityFeatureController::class, 'store'])->name('store');
        Route::get('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'show'])->name('show');
        Route::get('/{accessibilityFeature}/edit', [AccessibilityFeatureController::class, 'edit'])->name('edit');
        Route::put('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'update'])->name('update');
        Route::delete('/{accessibilityFeature}', [AccessibilityFeatureController::class, 'destroy'])->name('destroy');
    });
});

// --- Operacional ---
Route::middleware(['auth'])->group(function () {

    // Tecnologias Assistivas
    Route::prefix('assistive-technologies')->name('assistive-technologies.')->group(function () {
        Route::get('/', [AssistiveTechnologyController::class, 'index'])->name('index')->middleware('can:assistive-technology.index');
        Route::get('/create', [AssistiveTechnologyController::class, 'create'])->name('create')->middleware('can:assistive-technology.create');
        Route::post('/store', [AssistiveTechnologyController::class, 'store'])->name('store')->middleware('can:assistive-technology.store');
        Route::get('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'show'])->name('show')->middleware('can:assistive-technology.show');
        Route::get('/{assistiveTechnology}/inspection/{inspection}', [AssistiveTechnologyController::class, 'showInspection'])->name('inspection.show')->middleware('can:assistive-technology.inspection.show');
        Route::get('/{assistiveTechnology}/edit', [AssistiveTechnologyController::class, 'edit'])->name('edit')->middleware('can:assistive-technology.edit');
        Route::put('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'update'])->name('update')->middleware('can:assistive-technology.update');
        Route::delete('/{assistiveTechnology}', [AssistiveTechnologyController::class, 'destroy'])->name('destroy')->middleware('can:assistive-technology.destroy');
        Route::get('/{assistiveTechnology}/pdf', [AssistiveTechnologyController::class, 'generatePdf'])->name('pdf')->middleware('can:assistive-technology.pdf');
        Route::get('/{assistiveTechnology}/logs', [AssistiveTechnologyLogController::class, 'index'])->name('logs')->middleware('can:assistive-technology.logs');
    });

    // Barreiras
    Route::prefix('barriers')->name('barriers.')->group(function () {
        Route::get('/', [BarrierController::class, 'index'])->name('index')->middleware('can:barrier.index');
        Route::get('/create', [BarrierController::class, 'create'])->name('create')->middleware('can:barrier.create');
        Route::post('/store', [BarrierController::class, 'store'])->name('store')->middleware('can:barrier.store');
        Route::get('/{barrier}', [BarrierController::class, 'show'])->name('show')->middleware('can:barrier.show');
        Route::get('/{barrier}/inspection/{inspection}', [BarrierController::class, 'showInspection'])->name('inspection.show')->middleware('can:barrier.inspection.show');
        Route::get('/{barrier}/edit', [BarrierController::class, 'edit'])->name('edit')->middleware('can:barrier.edit');
        Route::put('/{barrier}', [BarrierController::class, 'update'])->name('update')->middleware('can:barrier.update');
        Route::delete('/{barrier}', [BarrierController::class, 'destroy'])->name('destroy')->middleware('can:barrier.destroy');
        Route::get('/{barrier}/pdf', [BarrierController::class, 'generatePdf'])->name('pdf')->middleware('can:barrier.pdf');
    });

    // Materiais Pedagógicos Acessíveis
    Route::prefix('accessible-educational-materials')->name('accessible-educational-materials.')->group(function () {
        Route::get('/', [AccessibleEducationalMaterialController::class, 'index'])->name('index')->middleware('can:material.index');
        Route::get('/create', [AccessibleEducationalMaterialController::class, 'create'])->name('create')->middleware('can:material.create');
        Route::post('/store', [AccessibleEducationalMaterialController::class, 'store'])->name('store')->middleware('can:material.store');
        Route::get('/{material}', [AccessibleEducationalMaterialController::class, 'show'])->name('show')->middleware('can:material.show');
        Route::get('/{material}/inspection/{inspection}', [AccessibleEducationalMaterialController::class, 'showInspection'])->name('inspection.show')->middleware('can:material.inspection.show');
        Route::get('/{material}/edit', [AccessibleEducationalMaterialController::class, 'edit'])->name('edit')->middleware('can:material.edit');
        Route::put('/{material}', [AccessibleEducationalMaterialController::class, 'update'])->name('update')->middleware('can:material.update');
        Route::delete('/{material}', [AccessibleEducationalMaterialController::class, 'destroy'])->name('destroy')->middleware('can:material.destroy');
        Route::get('/{material}/pdf', [AccessibleEducationalMaterialController::class, 'generatePdf'])->name('pdf')->middleware('can:material.pdf');
        Route::get('/{material}/logs', [AccessibleEducationalMaterialLogController::class, 'index'])->name('logs')->middleware('can:material.logs');
    });

    // Agenda Institucional
    Route::prefix('institutional-events')->name('institutional-events.')->group(function () {
        Route::get('/', [InstitutionalEventController::class, 'index'])->name('index')->middleware('can:institutional-event.index');
        Route::get('/create', [InstitutionalEventController::class, 'create'])->name('create')->middleware('can:institutional-event.create');
        Route::post('/store', [InstitutionalEventController::class, 'store'])->name('store')->middleware('can:institutional-event.store');
        Route::get('/{event}', [InstitutionalEventController::class, 'show'])->name('show')->middleware('can:institutional-event.show');
        Route::get('/{event}/edit', [InstitutionalEventController::class, 'edit'])->name('edit')->middleware('can:institutional-event.edit');
        Route::put('/{event}', [InstitutionalEventController::class, 'update'])->name('update')->middleware('can:institutional-event.update');
        Route::delete('/{event}', [InstitutionalEventController::class, 'destroy'])->name('destroy')->middleware('can:institutional-event.destroy');
        Route::get('/{event}/pdf', [InstitutionalEventController::class, 'generatePdf'])->name('pdf')->middleware('can:institutional-event.pdf');
    });

    // Empréstimos
    Route::prefix('loans')->name('loans.')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('index')->middleware('can:loan.index');
        Route::get('/create', [LoanController::class, 'create'])->name('create')->middleware('can:loan.create');
        Route::post('/store', [LoanController::class, 'store'])->name('store')->middleware('can:loan.store');
        Route::get('/{loan}', [LoanController::class, 'show'])->name('show')->middleware('can:loan.show');
        Route::get('/{loan}/edit', [LoanController::class, 'edit'])->name('edit')->middleware('can:loan.edit');
        Route::put('/{loan}', [LoanController::class, 'update'])->name('update')->middleware('can:loan.update');
        Route::patch('/{loan}/return', [LoanController::class, 'returnItem'])->name('return')->middleware('can:loan.return');
        Route::delete('/{loan}', [LoanController::class, 'destroy'])->name('destroy')->middleware('can:loan.destroy');
        Route::get('/{loan}/pdf', [LoanController::class, 'generatePdf'])->name('pdf')->middleware('can:loan.pdf');
    });

    // Fila de Espera
    Route::prefix('waitlists')->name('waitlists.')->group(function () {
        Route::get('/', [WaitlistController::class, 'index'])->name('index')->middleware('can:waitlist.index');
        Route::get('/create', [WaitlistController::class, 'create'])->name('create')->middleware('can:waitlist.create');
        Route::post('/store', [WaitlistController::class, 'store'])->name('store')->middleware('can:waitlist.store');
        Route::get('/{waitlist}', [WaitlistController::class, 'show'])->name('show')->middleware('can:waitlist.show');
        Route::get('/{waitlist}/edit', [WaitlistController::class, 'edit'])->name('edit')->middleware('can:waitlist.edit');
        Route::put('/{waitlist}', [WaitlistController::class, 'update'])->name('update')->middleware('can:waitlist.update');
        Route::delete('/{waitlist}', [WaitlistController::class, 'destroy'])->name('destroy')->middleware('can:waitlist.destroy');
        Route::patch('/{waitlist}/cancel', [WaitlistController::class, 'cancel'])->name('cancel')->middleware('can:waitlist.cancel');
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

// --- Admin ---
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Pessoas
    Route::prefix('people')->name('people.')->group(function () {
        Route::get('/', [PersonController::class, 'index'])->name('index')->middleware('can:people.view');
        Route::get('/create', [PersonController::class, 'create'])->name('create')->middleware('can:people.create');
        Route::post('/store', [PersonController::class, 'store'])->name('store')->middleware('can:people.create');
        Route::get('/{person}/edit', [PersonController::class, 'edit'])->name('edit')->middleware('can:people.update');
        Route::put('/{person}', [PersonController::class, 'update'])->name('update')->middleware('can:people.update');
        Route::delete('/{person}', [PersonController::class, 'destroy'])->name('destroy')->middleware('can:people.delete');
    });

    // Deficiências
    Route::prefix('deficiencies')->name('deficiencies.')->group(function () {
        Route::get('/', [DeficiencyController::class, 'index'])->name('index')->middleware('can:deficiency.view');
        Route::get('/create', [DeficiencyController::class, 'create'])->name('create')->middleware('can:deficiency.create');
        Route::post('/store', [DeficiencyController::class, 'store'])->name('store')->middleware('can:deficiency.create');
        Route::get('/{deficiency}/show', [DeficiencyController::class, 'show'])->name('show')->middleware('can:deficiency.view');
        Route::get('/{deficiency}/edit', [DeficiencyController::class, 'edit'])->name('edit')->middleware('can:deficiency.update');
        Route::put('/{deficiency}', [DeficiencyController::class, 'update'])->name('update')->middleware('can:deficiency.update');
        Route::patch('/{deficiency}/deactivate', [DeficiencyController::class, 'toggleActive'])->name('deactivate')->middleware('can:deficiency.update');
        Route::delete('/{deficiency}', [DeficiencyController::class, 'destroy'])->name('destroy')->middleware('can:deficiency.delete');
    });

    // Cargos
    Route::prefix('positions')->name('positions.')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('index')->middleware('can:position.view');
        Route::get('/create', [PositionController::class, 'create'])->name('create')->middleware('can:position.create');
        Route::post('/store', [PositionController::class, 'store'])->name('store')->middleware('can:position.create');
        Route::get('/{position}/show', [PositionController::class, 'show'])->name('show')->middleware('can:position.view');
        Route::get('/{position}/edit', [PositionController::class, 'edit'])->name('edit')->middleware('can:position.update');
        Route::put('/{position}', [PositionController::class, 'update'])->name('update')->middleware('can:position.update');
        Route::patch('/{position}/deactivate', [PositionController::class, 'toggleActive'])->name('deactivate')->middleware('can:position.update');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('destroy')->middleware('can:position.delete');
    });
});

// --- Operacional ---
Route::middleware(['auth'])->group(function () {

    // Estudantes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('can:student.view');
        Route::get('/create', [StudentController::class, 'create'])->name('create')->middleware('can:student.create');
        Route::post('/store', [StudentController::class, 'store'])->name('store')->middleware('can:student.create');
        Route::get('/{student}/show', [StudentController::class, 'show'])->name('show')->middleware('can:student.view');
        Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('can:student.update');
        Route::put('/{student}', [StudentController::class, 'update'])->name('update')->middleware('can:student.update');
        Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy')->middleware('can:student.delete');
    });

    // Profissionais
    Route::prefix('professionals')->name('professionals.')->group(function () {
        Route::get('/', [ProfessionalController::class, 'index'])->name('index')->middleware('can:professional.view');
        Route::get('/create', [ProfessionalController::class, 'create'])->name('create')->middleware('can:professional.create');
        Route::post('/store', [ProfessionalController::class, 'store'])->name('store')->middleware('can:professional.create');
        Route::get('/{professional}/show', [ProfessionalController::class, 'show'])->name('show')->middleware('can:professional.view');
        Route::get('/{professional}/edit', [ProfessionalController::class, 'edit'])->name('edit')->middleware('can:professional.update');
        Route::put('/{professional}', [ProfessionalController::class, 'update'])->name('update')->middleware('can:professional.update');
        Route::delete('/{professional}', [ProfessionalController::class, 'destroy'])->name('destroy')->middleware('can:professional.delete');
    });
});
