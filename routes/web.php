<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

Route::middleware('web')->group(function () {

    Route::group([], base_path('routes/modules.php'));

    Route::middleware(['auth'])->prefix('relatorios')->group(function () {
        Route::get('/', [ReportController::class, 'builder'])->name('relatorios.index');
        Route::get('/criar', [ReportController::class, 'builder'])->name('relatorios.criar');
        Route::get('/dados-disponiveis', [ReportController::class, 'availableEntities'])->name('relatorios.dados');
        Route::get('/metadados', [ReportController::class, 'meta'])->name('relatorios.meta');
        Route::post('/gerar', [ReportController::class, 'run'])->name('relatorios.gerar');
        Route::post('/exportar-pdf', [ReportController::class, 'exportPdf'])->name('relatorios.exportar.pdf');
    });

    Route::group([], base_path('routes/auth.php'));

    Route::middleware(['auth'])->group(function () {
        Route::get('/notificacoes', [NotificationController::class, 'index'])->name('notificacoes.index');
        Route::get('/notificacoes/quantidade', [NotificationController::class, 'count'])->name('notificacoes.quantidade');
        Route::get('/notificacoes/lista', [NotificationController::class, 'list'])->name('notificacoes.lista');
        Route::post('/notificacoes/{id}/marcar-como-lida', [NotificationController::class, 'markAsRead'])->name('notificacoes.ler');
        Route::post('/notificacoes/marcar-todas', [NotificationController::class, 'markAllAsRead'])->name('notificacoes.ler.todas');
    });

    Route::get('/sobre-nos', fn() => view('pages.about-us'))->name('sobre-nos');
});
