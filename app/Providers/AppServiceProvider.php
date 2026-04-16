<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Paginator::useBootstrap();
        View::composer(['manager.layout'], function ($view) {

        $notifications = Notification::where('is_cleared', false)
            ->latest()
            ->get();

        $unreadCount = Notification::where('is_read', false)
            ->where('is_cleared', false)
            ->count();

        $view->with([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    });
    }

  
}
