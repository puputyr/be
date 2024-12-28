<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The namespace for the controller.
     *
     * @var string
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route services.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes(); // Menambahkan pemetaan rute API
        $this->mapWebRoutes(); // Menambahkan pemetaan rute web
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // Menambahkan prefix 'api'
            ->middleware('api') // Menggunakan middleware api
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php')); // Menentukan file rute api.php
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') // Menambahkan middleware web
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php')); // Menentukan file rute web.php
    }
}
