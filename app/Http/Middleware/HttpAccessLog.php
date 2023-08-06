<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class HttpAccessLog {

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null) {
        return $next($request);
    }

    /**
     * @param Request $request
     * @param JsonResponse|Response $response
     */
    public function terminate(Request $request, $response) {

        $responseBody = '';

        if ($response instanceof JsonResponse) {
            $responseBody =  $response->getData(true);
        }

        if ($response instanceof Response) {
            $responseBody =  $response->getContent();
        }

        Log::info('', [
            'type' => 'access_log',
            'request' => [
                'ips' => $request->ips(),
                'method' => $request->getMethod(),
                'url' => $request->getUri(),
                'headers' => $request->headers->all(),
                'body' => $request->all()
            ],
            'response' => [
                'headers' => $response->headers->all(),
                'status' => $response->getStatusCode(),
                'body' => $responseBody
            ]
        ]);
    }
}
