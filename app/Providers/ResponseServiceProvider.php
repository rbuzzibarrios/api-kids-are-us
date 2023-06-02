<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\ServiceProvider;
use Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($data = []): JsonResponse {
            if ($data instanceof SupportCollection || $data instanceof Model) {
                $data = ['data' => $data->toArray()];
            }

            if ($data instanceof LengthAwarePaginator || $data instanceof Paginator) {
                $data = $data->toArray();
            }

            if (is_string($data)) {
                $data = ['message' => $data];
            }

            return Response::json([
                'status' => 'success',
            ] + $data, 200);
        });

        Response::macro('error', function (string $message = null, $status = 500, $codeError = null): JsonResponse {
            return Response::json([
                'status' => 'error',
                'message' => $message,
                'code_error' => $codeError,
            ], 500);
        });
    }
}
