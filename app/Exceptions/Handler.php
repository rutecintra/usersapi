<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e): Response
    {
        if ($request->is('api/*')) {

            return response()->json([
                'success' => false,
                'message' => $this->getErrorMessage($e),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], $this->getStatusCode($e));
        }

        return parent::render($request, $e);
    }

    private function getStatusCode(Throwable $e): int
    {
        return match(true) {
            method_exists($e, 'getStatusCode') => $e->getStatusCode(),
            $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 404,
            $e instanceof \Illuminate\Validation\ValidationException => 422,
            default => 500
        };
    }

    private function getErrorMessage(Throwable $e): string
    {
        return match(true) {
            $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException => 'Resource not found',
            $e instanceof \Illuminate\Validation\ValidationException => 'Validation error',
            default => 'Internal server error'
        };
    }
}