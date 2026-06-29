<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // Pages d'erreur personnalisées
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();
            
            // 404 - Page non trouvée
            if ($statusCode === 404) {
                return response()->view('errors.404', [], 404);
            }
            
            // 403 - Accès interdit
            if ($statusCode === 403) {
                return response()->view('errors.403', [], 403);
            }
            
            // 419 - Session expirée
            if ($exception instanceof TokenMismatchException) {
                return response()->view('errors.419', [], 419);
            }
            
            // 429 - Trop de requêtes
            if ($statusCode === 429) {
                return response()->view('errors.429', [], 429);
            }
            
            // 500 - Erreur serveur
            if ($statusCode === 500) {
                return response()->view('errors.500', [
                    'code' => 500,
                    'title' => 'Erreur interne du serveur',
                    'message' => 'Une erreur inattendue s\'est produite.'
                ], 500);
            }
            
            // 503 - Service indisponible
            if ($statusCode === 503) {
                return response()->view('errors.503', [], 503);
            }
            
            // 401 - Non authentifié
            if ($statusCode === 401) {
                return response()->view('errors.401', [], 401);
            }
        }
        
        // Erreur d'authentification
        if ($exception instanceof AuthenticationException) {
            return redirect()->route('login');
        }
        
        // Erreur d'autorisation
        if ($exception instanceof AuthorizationException) {
            return response()->view('errors.403', [
                'message' => $exception->getMessage() ?: 'Vous n\'avez pas les droits pour effectuer cette action.'
            ], 403);
        }

        return parent::render($request, $exception);
    }
}