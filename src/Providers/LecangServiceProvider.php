<?php

namespace ThankSong\Lecang\Providers;

use Illuminate\Support\ServiceProvider;
use ThankSong\Lecang\Lecang;

class LecangServiceProvider extends ServiceProvider{
    public function boot(){
        $this->publishes([
            __DIR__.'/../../config/lecang.php' => config_path('lecang.php'),
        ], 'lecang');
        
    }

    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lecang.php',
            'lecang'
        );
        $this->app->singleton('lecang', fn() => new Lecang);
    }
}