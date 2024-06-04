<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\ClothingSize;
use App\Models\Gender;
use App\Models\Size;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
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
        $this->configurePermissions();
        Jetstream::deleteUsersUsing(DeleteUser::class);
        Fortify::registerView(function () {
            $genders = Gender::all();
            $sizes = Size::all();
            return view('auth.register',compact('genders', 'sizes'));
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
