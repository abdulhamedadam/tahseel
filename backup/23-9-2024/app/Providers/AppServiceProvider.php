<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

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
        Paginator::useBootstrapFour();
        Lang::handleMissingKeysUsing(function ($key) {
            if (strpos($key, 'flasher') !== false) {
                return $key;
            }

            // Add custom logic to handle missing keys
            // For example, you can log the missing key
            Log::info("Missing translation key: $key");

            // You can also add the missing key to the language file dynamically
            $keyParts = explode('.', $key);
            if (count($keyParts) >= 2) {
                $group = $keyParts[0];
                $item = $keyParts[1];

                $langPath = base_path("lang/" . app()->getLocale() . "/$group.php");

                if (File::exists($langPath)) {
                    $translations = File::getRequire($langPath);
                    $translations[$item] = $item;
                    File::put($langPath, '<?php return ' . var_export($translations, true) . ';');
                } else {
                    File::put($langPath, '<?php return ' . var_export([$item => $item], true) . ';');
                }
            }

            // Return the key as the translation (optional)
            return $key;
        });
    }
}
