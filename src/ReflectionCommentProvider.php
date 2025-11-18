<?php

namespace Tengliyun\Helper;

use Illuminate\Support\ServiceProvider;
use Tengliyun\Helper\Contracts\ReflectionCommentWhenResolved;

class ReflectionCommentProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->afterResolving(ReflectionCommentWhenResolved::class, function (ReflectionCommentWhenResolved $resolved) {
            $resolved->ReflectionCommentResolved();
        });
    }
}
