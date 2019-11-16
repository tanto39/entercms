<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Group routes for admin panel
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController@dashboard')->name('admin.index');

    // Resource
    Route::resource('/category', 'CategoryController', ['as'=>'admin']);
    Route::resource('/item', 'ItemController', ['as'=>'admin']);
    Route::resource('/property', 'PropertyController', ['as'=>'admin']);
    Route::resource('/propgroup', 'PropGroupController', ['as'=>'admin']);
    Route::resource('/review', 'ReviewController', ['as'=>'admin']);
    Route::resource('/user', 'UserController', ['as'=>'admin']);
    Route::resource('/order', 'OrderController', ['as'=>'admin']);
    Route::resource('/menu', 'MenuController', ['as'=>'admin']);
    Route::resource('/menuitem', 'MenuItemController', ['as'=>'admin']);
    Route::resource('/delivery', 'DeliveryController', ['as'=>'admin']);
    Route::resource('/region', 'RegionController', ['as'=>'admin']);

    // Sorting and filters
    Route::post('/category/filter','CategoryController@filter')->name('admin.category.filter');
    Route::post('/item/filter','ItemController@filter')->name('admin.item.filter');
    Route::post('/propgroup/filter','PropGroupController@filter')->name('admin.propgroup.filter');
    Route::post('/property/filter','PropertyController@filter')->name('admin.property.filter');
    Route::post('/review/filter','ReviewController@filter')->name('admin.review.filter');
    Route::post('/user/filter','UserController@filter')->name('admin.user.filter');
    Route::post('/order/filter','OrderController@filter')->name('admin.order.filter');
    Route::post('/menu/filter','MenuController@filter')->name('admin.menu.filter');
    Route::post('/menuitem/filter','MenuItemController@filter')->name('admin.menuitem.filter');
    Route::post('/delivery/filter','DeliveryController@filter')->name('admin.delivery.filter');
    Route::post('/region/filter','RegionController@filter')->name('admin.region.filter');

    // Sitemap
    Route::get('/sitemap','SitemapController@index')->name('admin.sitemap.index');
    Route::post('/sitemap/generate','SitemapController@generate')->name('admin.sitemap.generate');

    // Yandex market
    Route::get('/yandexmarket','YandexMarketController@index')->name('admin.yandexmarket.index');
    Route::post('/yandexmarket/generate','YandexMarketController@generate')->name('admin.yandexmarket.generate');

    // Turbopages
    Route::get('/turbopages','TurbopagesController@index')->name('admin.turbopages.index');
    Route::post('/turbopages/generate','TurbopagesController@generate')->name('admin.turbopages.generate');
});

Route::get('/', 'HomeController@index')->name('home.index');

Auth::routes();

/**
 * Group routes for public part
 */
Route::group(['namespace' => 'Site'], function() {
    // Search
    Route::get('/search','SearchController@index')->name('item.search');

    // Basket
    Route::get('/basket','OrderController@showBasket')->name('item.basket');
    Route::post('/addtobasket','OrderController@addToBasket')->name('item.addtobasket');
    Route::post('/deletebasketitem','OrderController@deleteBasketItem')->name('item.deletebasketitem');
    Route::post('/setregion','SetRegionController@setRegion')->name('item.setregion');

    // Blog controllers
    Route::get('/' . BLOG_SLUG, 'CategoryController@showBlogCategories')->name('item.showBlogCategories');
    Route::get('/' . BLOG_SLUG . '/{category_slug}', 'CategoryController@showBlogCategory')->name('item.showBlogCategory');
    Route::get('/' . BLOG_SLUG . '/{category_slug}/{item_slug}', 'ItemController@showBlogItem')->name('item.showBlogItem');

    // Catalog controllers
    Route::get('/' . CATALOG_SLUG, 'CategoryController@showCatalogCategories')->name('item.showCatalogCategories');
    Route::get('/' . CATALOG_SLUG . '/{category_slug}', 'CategoryController@showCatalogCategory')->name('item.showCatalogCategory');
    Route::get('/' . CATALOG_SLUG . '/{category_slug}/{item_slug}', 'ItemController@showProduct')->name('item.showProduct');

    // Reviews controllers
    Route::post('/review','ReviewController@store')->name('item.review.store');

    // Orders and mail controllers
    Route::post('/sendorder','OrderController@store')->name('item.order.store');

    // Uncategorised items
    Route::get('/{slug}', 'ItemController@showUncategorisedItem')->name('item.showUncaterorised');
});

