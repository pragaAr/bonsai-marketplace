<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Services\CustomPathGenerator;
use App\Services\UserProfilePathGenerator;
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
        config([
            'media-library.custom_path_generators' => array_merge(
                config('media-library.custom_path_generators', []),
                [
                    User::class => UserProfilePathGenerator::class,
                    Product::class => CustomPathGenerator::class,
                ]
            ),
        ]);
    }
}
