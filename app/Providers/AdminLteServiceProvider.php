<?php

namespace App\Providers;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Route;
use JeroenNoten\LaravelAdminLte\AdminLteServiceProvider as BaseServiceProvider;
use JeroenNoten\LaravelAdminLte\Console\AdminLteInstallCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLtePluginCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteStatusCommand;
use JeroenNoten\LaravelAdminLte\Console\AdminLteUpdateCommand;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;
use JeroenNoten\LaravelAdminLte\View\Components\Form;
use JeroenNoten\LaravelAdminLte\View\Components\Layout;
use JeroenNoten\LaravelAdminLte\View\Components\Tool;
use JeroenNoten\LaravelAdminLte\View\Components\Widget;

class AdminLteServiceProvider extends BaseServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        $this->pkgPrefix = 'admin';
    }

    /**
     * Register the package services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        // Bind a singleton instance of the AdminLte class into the service
        // container.
    }

    /**
     * Bootstrap the package's services.
     *
     * @return void
     */
    public function boot(Factory $view, Dispatcher $events, Repository $config): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadConfig();
//        $this->registerCommands();
        $this->registerViewComposers($view);
//        $this->registerMenu($events, $config);
        $this->loadComponents();
        $this->loadRoutes();
    }

    /**
     * Load the package views.
     *
     * @return void
     */
    private function loadViews(): void
    {
        $viewsPath = $this->packagePath('../resources/views');
        $this->loadViewsFrom($viewsPath, $this->pkgPrefix);
    }

    /**
     * Load the package translations.
     *
     * @return void
     */
    private function loadTranslations(): void
    {
        $translationsPath = $this->packagePath('../resources/lang');
        $this->loadTranslationsFrom($translationsPath, $this->pkgPrefix);
    }

    /**
     * Load the package config.
     *
     * @return void
     */
    private function loadConfig(): void
    {
        $configPath = $this->packagePath('../config/adminlte.php');
        $this->mergeConfigFrom($configPath, $this->pkgPrefix);
    }

    /**
     * Get the absolute path to some package resource.
     *
     * @param  string  $path  The relative path to the resource
     * @return string
     */
    private function packagePath(string $path): string
    {
        return __DIR__."/../$path";
    }

    /**
     * Register the package's artisan commands.
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands([
            AdminLteInstallCommand::class,
            AdminLteStatusCommand::class,
            AdminLteUpdateCommand::class,
            AdminLtePluginCommand::class,
        ]);
    }

    /**
     * Register the package's view composers.
     *
     * @param Factory $view
     * @return void
     */
    private function registerViewComposers(Factory $view): void
    {
        $view->composer('layouts.admin.page', AdminLteComposer::class);
    }

    /**
     * Register the menu events handlers.
     *
     * @param Dispatcher $events
     * @param Repository $config
     * @return void
     */
    private static function registerMenu(Dispatcher $events, Repository $config): void
    {
        // Register a handler for the BuildingMenu event, this handler will add
        // the menu defined on the config file to the menu builder instance.

        $events->listen(
            BuildingMenu::class,
            function (BuildingMenu $event) use ($config) {
                $menu = $config->get('adminlte.menu', []);
                $menu = is_array($menu) ? $menu : [];
                $event->menu->add(...$menu);
            }
        );
    }

    /**
     * Load the blade view components.
     *
     * @return void
     */
    private function loadComponents(): void
    {
        // Support of x-components is only available for Laravel >= 7.x
        // versions. So, we check if we can load components.

        $canLoadComponents = method_exists(
            'Illuminate\Support\ServiceProvider',
            'loadViewComponentsAs'
        );

        if (! $canLoadComponents) {
            return;
        }

        // Load all the blade-x components.

        $components = array_merge(
            $this->layoutComponents,
            $this->formComponents,
            $this->toolComponents,
            $this->widgetComponents
        );

        $this->loadViewComponentsAs($this->pkgPrefix, $components);
    }

    /**
     * Load the package web routes.
     *
     * @return void
     */
    private function loadRoutes(): void
    {
        $routesCfg = [
            'as' => "{$this->pkgPrefix}.",
            'prefix' => $this->pkgPrefix,
            'middleware' => ['web'],
        ];

        Route::group($routesCfg, function () {
            $routesPath = $this->packagePath('../routes/web.php');
            $this->loadRoutesFrom($routesPath);
        });
    }
}
