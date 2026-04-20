<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::middleware(['guest'])->group(function () {
    Route::get('/entrar', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/entrar', [LoginController::class, 'login']);
    Route::get('/esqueci-minha-senha', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/esqueci-minha-senha', [ForgotPasswordController::class, 'sendLink'])->name('password.email');
    Route::get('/redefinir-senha/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/redefinir-senha', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/painel', [LoginController::class, 'index'])->name('dashboard');
    Route::post('/sair', [LoginController::class, 'logout'])->name('logout');
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/impersonar/sair', [AdminController::class, 'leaveImpersonate'])->name('admin.impersonate.leave');
    Route::post('/impersonar/{user}', [AdminController::class, 'impersonate'])->name('admin.impersonate')->middleware('admin');
});
