<?php

namespace App\Exceptions;


use Illuminate\Contracts\Debug\ExceptionHandler;
use PHPUnit\Event\Code\Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }
        return parent::render($request, $exception);
    }
}
