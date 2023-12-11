<?php

namespace App\Exceptions;

use App\Common\CommonsFunctions;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'status' => 403,
                'message' => 'Você não possui permissão para realizar esta ação.',
                'timestamp' => CommonsFunctions::formatarDataTimeZonaAmericaSaoPaulo(now()),
            ], 403);
        }
    
        return parent::render($request, $exception);
    }
    
}
