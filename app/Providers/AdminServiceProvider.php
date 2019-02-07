<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/constants.php';
        require_once app_path() . '/traits/ImgController.php';
        require_once app_path() . '/traits/FileController.php';
        require_once app_path() . '/traits/PropEnumController.php';
        require_once app_path() . '/traits/HandlePropertyController.php';
        require_once app_path() . '/traits/AdminPanel.php';
        require_once app_path() . '/traits/FilterController.php';
        require_once app_path() . '/traits/SearchController.php';
        require_once app_path() . '/traits/SortTrait.php';
        require_once app_path() . '/traits/CategoryTrait.php';
        require_once app_path() . '/traits/UserTrait.php';
        require_once app_path() . '/traits/MenuTrait.php';
        require_once app_path() . '/traits/ReviewTrait.php';
        require_once app_path() . '/traits/OrderTrait.php';
    }
}
