<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // ※管理者用、ユーザー用それぞれのログイン画面の振り分け
        if (! $request->expectsJson()) {
            if($request->route()->getPrefix() == "/admin"){
                return route('admin/login');
            }
            return route('login');
        }
    }
}
