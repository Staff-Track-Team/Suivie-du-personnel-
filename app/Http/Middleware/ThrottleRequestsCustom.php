<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequestsCustom
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        // Appliquer uniquement aux requêtes POST
        if (!$request->isMethod('POST')) {
            return $next($request);
        }

        $key = $prefix . $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }

    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }

    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        // Calculer le temps en minutes et secondes pour un message plus lisible
        $minutes = floor($retryAfter / 60);
        $seconds = $retryAfter % 60;

        if ($minutes > 0) {
            $timeMessage = $minutes . ' minute' . ($minutes > 1 ? 's' : '') .
                        ($seconds > 0 ? ' et ' . $seconds . ' seconde' . ($seconds > 1 ? 's' : '') : '');
        } else {
            $timeMessage = $seconds . ' seconde' . ($seconds > 1 ? 's' : '');
        }

        $errorMessage = 'Trop de tentatives. Veuillez réessayer dans ' . $timeMessage . '.';

        // Pour les requêtes AJAX/JSON, retourner une réponse JSON
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $errorMessage
            ], 429);
        }

        // Pour les requêtes normales, rediriger avec un message flash
        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => $errorMessage,
                'email' => $errorMessage
            ])
            ->with('error', $errorMessage);
    }

    protected function addHeaders(Response $response, $maxAttempts, $remainingAttempts)
    {
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => $remainingAttempts,
        ]);

        return $response;
    }

    protected function calculateRemainingAttempts($key, $maxAttempts)
    {
        return $maxAttempts - $this->limiter->attempts($key);
    }
}
