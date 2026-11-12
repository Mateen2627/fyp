<?php

namespace App\Http\Middleware\Custom;

use Closure;

/** Keep old school routes from bypassing the LMS authorization rules. */
class LegacyLmsAccess
{
    public function handle($request, Closure $next)
    {
        $action = $request->route()->getActionName();
        if (strpos($action, 'TimeTableController@') !== false) {
            return $next($request); // Scoped authorization is in that controller.
        }
        foreach (['User' => 'admin.users.index', 'StudentRecord' => 'admin.users.index', 'MyClass' => 'admin.classes.index', 'Section' => 'admin.classes.index', 'Promotion' => 'admin.users.index'] as $name => $route) {
            if (strpos($action, '\\'.$name.'Controller@') !== false) {
                abort_unless(in_array($request->user()->user_type, ['admin', 'super_admin'], true) && $request->isMethod('GET'), 403);
                return redirect()->route($route);
            }
        }
        foreach (['Assignment', 'Quiz', 'Attendance', 'Course', 'Subject', 'Mark', 'StudentReport', 'Announcement'] as $name) {
            if (strpos($action, '\\'.$name.'Controller@') !== false) {
                // Retire the duplicated, unscoped academic write endpoints.
                abort_unless($request->isMethod('GET'), 403);
                $role = $request->user()->user_type;
                $routes = ['Assignment' => 'assignments', 'Quiz' => 'quizzes', 'Attendance' => 'attendance', 'Course' => 'courses', 'Subject' => 'courses', 'Mark' => 'subject_marks'];
                if (isset($routes[$name]) && in_array($role, ['teacher', 'student'], true)) {
                    return redirect()->route($role.'.'.$routes[$name].'.index');
                }
                abort(403);
            }
        }
        abort_unless(in_array($request->user()->user_type, ['admin', 'super_admin', 'accountant'], true), 403);
        if ($request->user()->user_type === 'accountant') {
            abort_unless(strpos($action, 'PaymentController@') !== false, 403);
        }
        return $next($request);
    }
}
