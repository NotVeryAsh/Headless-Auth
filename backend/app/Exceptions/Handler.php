<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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

    private array $noneHiddenRoutes = [
        'api/auth/*',
        'api/user',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (AuthenticationException $e) {

            if (request()->is('api/*') && ! request()->is(...$this->noneHiddenRoutes)) {

                // Return 404 for routes with resources - so users can't guess ids and such of resources
                return response()->json([], 404);
            }

            return response()->json([], 401);
        });
    }
}
