<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use League\OAuth2\Server\Exception\OAuthServerException;
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
        $this->reportable(function (\League\OAuth2\Server\Exception\OAuthServerException $e) {
            if ($e->getCode() == 9)
                return false;
        });

        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json([
                'status' => 'error',
                'data' => ['errorList' => ['unauthorized' => 'unauthorized']]
            ], 200);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'data' => ['errorList' => ['Internal Server Error2']]
                ], 200);
            }
            if ($request->is('*')) {
                return Redirect::to('/');
            }
        });
    }
}
