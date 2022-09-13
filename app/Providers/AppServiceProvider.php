<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
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
    // Paginator::useBootstrapFive();

    // !! SHARING the setting to all view
    // !! WITHOUT checking if table exist will destroy 
    // !! the migration process

    if (Schema::hasTable('settings')) {
      View::share('setting', \App\Models\settings::All());
    }

    if (Schema::hasTable('request_certificates')) {
      View::share('req', \App\Models\RequestCertificate::all());
    }

    // View::share('blotter', \App\Models\Blotter::all());
    // View::share('req', \App\Models\RequestCertificate::all());
  }
}
