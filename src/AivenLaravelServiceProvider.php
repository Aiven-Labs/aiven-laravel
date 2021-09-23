<?php

namespace LornaJane\AivenLaravel;

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

      $this->publishes([
        __DIR__.'/../config/aiven.php' => config_path('aiven.php'),
      ], 'config');
    }
  }
}

