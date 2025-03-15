<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    // Capture IP e User-Agent
    $ip = $request->ip();
    $userAgent = $request->header('User-Agent');

    // Armazene o log
    \App\Models\AccessLog::create([
        'ip_address' => $ip,
        'user_agent' => $userAgent
    ]);

    return $next($request);
}

}
