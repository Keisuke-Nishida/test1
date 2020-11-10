<?php

namespace App\Providers;

use App\Lib\Constant;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 管理画面用のクッキー名称、セッションテーブル名を変更する
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        if (strpos($uri, '/admin/') === 0 || $uri === '/admin') {
            config([
                'session.cookie' => Constant::SESSION_COOKIE_ADMIN,
                'session.table' => Constant::SESSION_TABLE_ADMIN,
            ]);
        }
    }
}
