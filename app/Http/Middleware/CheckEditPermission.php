<?php

// app/Http/Middleware/CheckEditPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;

class CheckEditPermission
{
    public function handle($request, Closure $next, $ability, $modelId)
    {
        if (Gate::denies($ability, $modelId)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
