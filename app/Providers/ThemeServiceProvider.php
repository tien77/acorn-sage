<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;
use Livewire\LivewireServiceProvider;
// use App\Http\Kernel;;
use Illuminate\Support\Facades\Route;
use Illuminate\Pagination\Paginator;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // BIND LivewireServiceProvider
        $this->app->register(LivewireServiceProvider::class);
        config(['livewire.layout' => 'layouts.app']); // đặt layout mặc định cho Livewire

        // BIND Kernel 
        // $this->app->singleton(\Illuminate\Contracts\Http\Kernel::class, Kernel::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Load routes
        $this->loadRoutesFrom(base_path('routes/web.php'));

        // nạp api routes với group 'api' + prefix 'api'
        Route::middleware('api')
            ->prefix('api')
            ->group(function () {
                // load file api.php
                $this->loadRoutesFrom(base_path('routes/api.php'));
            });

        $this->loadMigrationsFrom(base_path('database/migrations'));
        Paginator::useTailwind(); // hoặc useBootstrap() nếu dùng Bootstrap CSS

        // Bỏ Binding Middleware trong Kernel.php vì ta sẽ đăng ký thủ công ở đây
        // /**
        //  * đăng ký middleware cho toàn bộ route web
        //  * - bạn có thể thêm/bớt middleware khác nếu cần
        //  */
        //  /** @var Router $router */
        $router = $this->app['router'];

        // // 1) Khai báo alias middleware (để dùng 'log')
        $router->aliasMiddleware('log', \App\Http\Middleware\LogRequestMiddleware::class);
        // auth.jwt
        $router->aliasMiddleware('auth.jwt', \App\Http\Middleware\VerifyJwt::class);

        // // 2) Định nghĩa middleware cho toàn bộ group 'web'
        // $router->middlewareGroup('web', [
        //     // KHÔNG nên bật ValidateCsrfToken của Laravel cho WordPress form
        //     // \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,

        //     // nếu bạn cần session/cookie cho “ứng dụng” thì có thể bật:
        //     // \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     // \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     // \Illuminate\Session\Middleware\StartSession::class,
        //     // \Illuminate\View\Middleware\ShareErrorsFromSession::class,

        //     // middleware của bạn
        //     LogRequestMiddleware::class,
        // ]);


    }
}
