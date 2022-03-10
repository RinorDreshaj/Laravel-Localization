<?php

namespace Rinordreshaj\Localization;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
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
        
        Builder::macro('whereLikeRelation', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }

    private function publish_migrations()
    {
        if ($this->app->runningInConsole()) {
            if (
                !class_exists('LocalizationPackageLanguages') &&
                !class_exists('LocalizationPackageTranslatedKeys') &&
                !class_exists('LocalizationPackageTranslatedValues')
            ) {
                $this->publishes([
                    __DIR__ . '/database/migrations/localization_package_languages.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_languages.php'),
                    __DIR__ . '/database/migrations/localization_package_translated_keys.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_translated_keys.php'),
                    __DIR__ . '/database/migrations/localization_package_translated_values.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_localization_package_translated_values.php'),
                ], 'migrations');
            }
        }
    }
}
