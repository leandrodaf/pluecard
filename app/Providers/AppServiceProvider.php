<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->databaseDebug();
    }

    /**
     * Debug database connection.
     *
     * @return void
     */
    private function databaseDebug(): void
    {
        DB::listen(function ($query) use (&$time) {
            $time += $query->time;
            Log::debug('DATABASE_QUERY', ['sql' => $query->sql, 'values' => $query->bindings, 'time' => $query->time . 'ms']);
        });
    }
}
