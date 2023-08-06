<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponse;

class ApiAuthenticate
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set("Accept", "application/json");

        $token = $request->bearerToken() ?: $request->get('token');
        $apiAuthToken = env('API_AUTH_TOKEN', null);

        if (!empty($token) && $token === $apiAuthToken) {
            return $next($request);
        }

        return $this->apiResponseError(["You are not logged in!"], 401);
    }
}
