<?php
namespace Lixus\ValueFirst;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Str;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__. "/../config/valuefirst.php","valuefirst");

        $this->app->bind("lixus.client", function () {
            return new Client($this->app["config"]);
        });
    }

    /**
     * Check if package is running under Lumen app
     *
     * @return bool
     */
    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen') === true;
    }

    public function boot()
    {
        if (!$this->isLumen()) {
            $configPath = __DIR__ . "/../config/valuefirst.php";
            $this->publishes([$configPath => config_path("valuefirst.php")], "valuefirst");
        }
    }
}