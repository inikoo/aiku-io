<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Inertia\Inertia;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e) && app()->bound('sentry') && config('app.env') == 'production') {
                app('sentry')->captureException($e);
            }
        });
    }


    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);




        if (!app()->environment(['local', 'testing']) && in_array($response->status(), [500, 503, 404, 403, 422])) {
            return Inertia::render(
                'error',
                array_merge(['status'      => $response->status()],
                    match ($response->status()) {
                        403 => [
                            'title'       => __('Forbidden'),
                            'description' => __('Sorry, you are forbidden from accessing this page.')
                        ],
                        404 => [
                            'title'       => __('Page Not Found'),
                            'description' => __('Sorry, the page you are looking for could not be found.')
                        ],
                        422 => [
                            'title'       => __('Unprocessable request'),
                            'description' => __('Sorry, is impossible to process this page.')
                        ],
                        503 => [
                            'title'       => __('Service Unavailable'),
                            'description' => __('Sorry, we are doing some maintenance. Please check back soon.')
                        ],
                        default => [
                            'title'       => __('Server Error'),
                            'description' => __('Whoops, something went wrong on our servers.')
                        ]
                    })
            )
                ->toResponse($request)
                ->setStatusCode($response->status());
        } elseif ($response->status() === 419) {
            return back()->with([
                                    'message' => 'The page expired, please try again.',
                                ]);
        }

        return $response;
    }

}
