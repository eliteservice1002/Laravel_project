<?php

namespace App\Domains\Core\Services\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Sentry\State\HubInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            if (app()->bound(HubInterface::class) && $this->shouldReport($exception)) {
                app(HubInterface::class)->captureException($exception);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request): RedirectResponse | null {
            if (is_null($request->get('store'))) {
                return null;
            }

            // TODO(ibrasho): Use default locale
            $uri = $request->getUriForPath('/ar'.$request->getPathInfo());

            return redirect()->to($uri, 301);
        });
    }
}
