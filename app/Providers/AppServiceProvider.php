<?php

namespace App\Providers;

use DateTimeInterface;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\User\Entities\User;
use Modules\Visitor\Entities\Invite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });

        Paginator::useBootstrapFive();

        if (!($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            Route::get('local/temp/{path}', function (string $path) {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk(env('TERMINAL_BASE64_STORAGE_DISK'));

                return $disk->download($path) ?? null;
            })->where('path', '.*')
                ->name('local.temp')
                ->middleware(['web', 'signed']);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(env('TERMINAL_BASE64_STORAGE_DISK'));
        $disk->buildTemporaryUrlsUsing(
            function (string $path, DateTimeInterface $expiration, array $options = []) {
                return URL::temporarySignedRoute(
                    'local.temp',
                    $expiration,
                    array_merge($options, ['path' => $path])
                );
            }
        );

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

    }

}
