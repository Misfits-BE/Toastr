<?php 

namespace ActivismeBe\Toastr;

use Illuminate\Support\ServiceProvider; 
use ActivismeBE\Toastr\Toastr;

/**
 * Class ToastrServiceProvider 
 * ----
 * The class for registring the package in a laravel application. 
 * 
 * @author      Kamal Nasser <kamal@kamalnasser.net>
 * @copyright   2013 Kamal Nasser <kamal@kamalnasser.net>
 * @package     ActivismeBe\Toastr
 */
class ToastrServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred. 
     * 
     * @var bool $defer
     */
    protected $defer = false;

    /**
     * Bootstrap the application events 
     * 
     * @return void 
     */
    public function boot(): void 
    {
        $this->publishes([__DIR__ . '/config.php' => config_path('toastr.php')], 'config');
    }

    /**
     * Register the service provider 
     * 
     * @return void 
     */
    public function register(): void 
    {
        /** 
         * [CLOSURE]: Register the Toastr singleton 
         * 
         * @param  mixed $app The application services variable.
         * @return Toastr
         */
        $this->app->singleton('toastr', function ($app): Toastr {
            return new Toastr($app['session'], $app['config']);
        });
    }

    /**
     * Get the services provided by the provider.
     * 
     * @return array
     */
    public function provides(): array 
    {
        return ['toastr'];
    }
}
