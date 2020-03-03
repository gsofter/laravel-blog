<?php

namespace App\Providers;

use App\Article;
use App\Discussion;
use App\Tools\FileManager\BaseManager;
use App\Tools\FileManager\UpyunManager;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use App\TestClass;

class AppServiceProvider extends ServiceProvider
{

    

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $lang = config('app.locale') != 'zh_cn' ? config('app.locale') : 'zh';
        \Carbon\Carbon::setLocale($lang);

        Relation::morphMap([
            'discussions' => Discussion::class,
            'articles'    => Article::class,
        ]);

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('bind_hello', function() {
            return Str::random(8);
        });

        $this->app->singleton('singleton_hello', function(){
            return Str::random(40);
        });

        $this->app->when('App\Http\Controllers\UserController')
                    ->needs('$param')
                    ->give("123123123");

        $this->app->singleton('uploader', function ($app) {
            $config = config('filesystems.default', 'public');

            if ($config == 'upyun') {
                return new UpyunManager();
            }

            return new BaseManager();
        });
    }
}
