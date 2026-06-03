<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

Route::middleware('web')->group(function () {

    Route::group([], base_path('routes/modules.php'));

    Route::middleware(['auth'])->prefix('relatorios')->group(function () {
        Route::get('/', [ReportController::class, 'builder'])->name('relatorios.index')->middleware('can:report.index');
        Route::get('/criar', [ReportController::class, 'builder'])->name('relatorios.criar')->middleware('can:report.index');
        Route::get('/dados-disponiveis', [ReportController::class, 'availableEntities'])->name('relatorios.dados')->middleware('can:report.available-data');
        Route::get('/metadados', [ReportController::class, 'meta'])->name('relatorios.meta')->middleware('can:report.meta');
        Route::post('/gerar', [ReportController::class, 'run'])->name('relatorios.gerar')->middleware('can:report.run');
        Route::post('/exportar-pdf', [ReportController::class, 'exportPdf'])->name('relatorios.exportar.pdf')->middleware('can:report.export.pdf');
    });

    Route::group([], base_path('routes/auth.php'));

    Route::middleware(['auth'])->group(function () {
        Route::get('/notificacoes', [NotificationController::class, 'index'])->name('notificacoes.index')->middleware('can:notification.index');
        Route::get('/notificacoes/quantidade', [NotificationController::class, 'count'])->name('notificacoes.quantidade')->middleware('can:notification.count');
        Route::get('/notificacoes/lista', [NotificationController::class, 'list'])->name('notificacoes.lista')->middleware('can:notification.list');
        Route::post('/notificacoes/{id}/marcar-como-lida', [NotificationController::class, 'markAsRead'])->name('notificacoes.ler')->middleware('can:notification.read');
        Route::post('/notificacoes/marcar-todas', [NotificationController::class, 'markAllAsRead'])->name('notificacoes.ler.todas')->middleware('can:notification.read-all');
    });

    Route::get('/sobre-nos', fn() => view('pages.about-us'))->name('sobre-nos');
});
