<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HoneypotMiddleware
{
    /**
     * Minimum time (in seconds) a human would need to fill the form.
     */
    protected int $minSubmitTime = 3;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check honeypot field - must be empty (bots auto-fill hidden fields)
        if ($request->filled('website_url')) {
            // Silently reject - don't give feedback to bots
            return redirect()->back();
        }

        // Check timestamp - form must take at least X seconds to submit
        if ($request->has('_honeypot_time')) {
            $submittedTime = (int) $request->input('_honeypot_time');
            $currentTime = time();

            if (($currentTime - $submittedTime) < $this->minSubmitTime) {
                // Too fast - likely a bot
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
