<?php

namespace Rinordreshaj\Localization;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        $this->publish_migrations();

    }

    private function publish_migrations()
    {
        if ($this->app->runningInConsole())
        {
            if (
                !class_exists('LocalizationPackageLanguages') &&
                !class_exists('LocalizationPackageTranslatedKeys') &&
                !class_exists('LocalizationPackageTranslatedValues')
            )
            {
                $this->publishes([
                    __DIR__ . '/database/migrations/localization_package_languages.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_languages.php'),
                    __DIR__ . '/database/migrations/localization_package_translated_keys.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_translated_keys.php'),
                    __DIR__ . '/database/migrations/localization_package_translated_values.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_translated_values.php'),
                ], 'migrations');
            }
        }
    }
}
