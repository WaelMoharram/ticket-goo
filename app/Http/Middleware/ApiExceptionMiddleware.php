<?php

namespace App\Http\Middleware;

use App\Exceptions\Handler;
use Closure;
use Throwable;

class ApiExceptionMiddleware
{
    protected $handler;

    public function __construct()
    {
        $this->handler = new Handler();
    }

    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (Throwable $exception) {
            return $this->handler->handle($exception, $request);
        }
    }
}
