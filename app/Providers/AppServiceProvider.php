<?php

namespace App\Providers;

use App\Services\HolidayService;
use App\Services\NaturalLanguageService;
use App\Services\PageService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HolidayService::class);
        $this->app->singleton(NaturalLanguageService::class);

        $this->configurePageService();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @return void
     */
    private function configurePageService(): void
    {
        $this->app->singleton(PageService::class, function () {
            $appName = config('app.name');
            if ($appName === "Laravel") {
                $appName = "SindHeuteFerien.de"; // We're lazy ^^
            }

            $service = new \App\Services\PageService($appName);
            $service->setAuthor("SindHeuteFerien.de");
            $service->setKeywords('Ferien heute, Schulferien, Bundesländer, Deutschland, Ferienkalender, Ferienübersicht');
            return $service;
        });
    }
}
