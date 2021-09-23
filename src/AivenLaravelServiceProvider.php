<?php

namespace LornaJane\AivenLaravel;

use LornaJane\AivenLaravel\Console\AivenGet;
use LornaJane\AivenLaravel\Console\AivenList;
use LornaJane\AivenLaravel\Console\AivenPowerdown;
use LornaJane\AivenLaravel\Console\AivenPowerup;
use LornaJane\AivenLaravel\Console\AivenState;

use Illuminate\Support\ServiceProvider;

class AivenLaravelServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/../config/aiven.php', 'aiven');
  }

  public function boot()
  {
    // Register the command if we are using the application via the CLI
    if ($this->app->runningInConsole()) {
      $this->commands([
        AivenGet::class,
        AivenList::class,
        AivenPowerdown::class,
        AivenPowerup::class,
        AivenState::class,
      ]);
    }
    
    $this->publishes([
      __DIR__.'/../config/aiven.php' => config_path('aiven.php'),
    ]);
  }
}

