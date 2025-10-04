<?php

namespace App\Exceptions;

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

        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Not Found.'
                ], 404);
            }

            $message = 'The requested page was not found. Please check the URL and try again.';
            if ($request->is('admin/*')) {
                $message = 'The requested admin page was not found. Please check your permissions and try again.';
            } elseif ($request->is('employee/*')) {
                $message = 'The requested employee page was not found. Please check your permissions and try again.';
            }

            return redirect()->route('welcome')->with('error', $message);
        });
    }
}
