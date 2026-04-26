<?php

use App\Http\Middleware\CheckAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Exceptions\BusinessRuleException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectTo(
            guests: '/entrar',
            users: '/painel'
        );
        $middleware->alias([
            'admin' => CheckAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Tratamento para Regras de Negócio
        $exceptions->render(function (BusinessRuleException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        });

        // Validação
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Dados inválidos.', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        });

        // Não Autenticado
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Não autenticado.'], 401);
            }
            return redirect()->guest(route('login'))->with('error', 'Faça login para continuar.');
        });

        // Model Not Found (404 de Banco)
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            $msg = 'Recurso não encontrado.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 404)
                : response()->view('errors.404', [], 404);
        });

        // Não Autorizado
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            $msg = 'Acesso não autorizado.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 403)
                : response()->view('errors.403', [], 403);
        });

        // Erro de Banco (Query)
        $exceptions->render(function (QueryException $e, Request $request) {
            logger()->error('Erro de Banco: ' . $e->getMessage());
            $msg = 'Erro interno no servidor.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 500)
                : response()->view('errors.500', ['message' => $msg], 500);
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            logger()->error($e);

            if ($e instanceof HttpExceptionInterface) {
                return null;
            }

            $msg = 'Ocorreu um erro inesperado.';

            return $request->expectsJson()
                ? response()->json(['message' => $msg], 500)
                : response()->view('errors.500', ['message' => $msg], 500);
        });

    })->create();
