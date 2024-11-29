<?php

namespace App\Providers;

use App\Exceptions\handler;
use App\Repositories\AuthRepository;
use App\Repositories\AuthRepositoryInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->singleton(ExceptionHandlerContract::class, Handler::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('jsonResponse', function ($status, $message, $data, $status_code = 200) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'status_code' => $status_code,
            ], $status_code);
        });
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        App::setLocale(request()->header('Accept-Language', 'en'));

    }
}
