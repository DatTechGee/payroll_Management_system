<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Add Blade directive for currency formatting
        \Illuminate\Support\Facades\Blade::directive('naira', function ($expression) {
            return "<?php echo '₦' . number_format($expression, 2); ?>";
        });
    }
}
