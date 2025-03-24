<?php

namespace App\Providers;

use App\Exceptions\handler;
use App\Models\File;
use App\Repositories\AuthRepository;
use App\Repositories\BaseRepository;
use App\Repositories\CourseCategoryRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CourseTypeRepository;
use App\Repositories\GenderCategoryRepository;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\CourseCategoryRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\CourseTypeRepositoryInterface;
use App\Repositories\Interfaces\GenderCategoryRepositoryInterface;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->singleton(ExceptionHandlerContract::class, Handler::class);
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseTypeRepositoryInterface::class, CourseTypeRepository::class);
        $this->app->bind(CourseCategoryRepositoryInterface::class, CourseCategoryRepository::class);
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

        // macro for upload file

        Storage::macro('uploadFile', function ($files, $model,) {
            $files = is_array($files) ? $files : [$files];


            $urls = [];
            foreach ($files as $file) {
                if (is_object($file)) {
                    $size = $file->getSize();
                    $extension = $file->getClientOriginalExtension();
                    $type = $file->getClientMimeType();
                    $name = Str::random(20) . time() . Str::random(20) . "." . $extension;
                    $folder_path = Carbon::now()->format('Y-m-d');
                    $url = $file->store($folder_path, 'upload_driver');

                    $model->files()->create([
                        'name' => $name,
                        'path' => $url,
                        'size' => $size,
                        'type' => $type,
                        'extension' => $extension,

                    ]);

                }
            }
        });

    }



}
