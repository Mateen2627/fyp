<?php

namespace App\Http\Middleware\Custom;

use App\Services\LmsAccess;
use Closure;

class DepartmentRole
{
    public function handle($request, Closure $next, $role = 'leader')
    {
        abort_unless($request->user() && LmsAccess::departmentIds($role)->isNotEmpty(), 403);
        return $next($request);
    }
}
