<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('register', 'AuthController@register');
    $router->post('login', 'AuthController@login');
});

$router->group(['middleware' => 'auth', 'prefix' => 'api'], function ($router) {
    $router->get('profile', 'AuthController@profile');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');

    $router->post('products-save', 'ProductController@store');
    $router->get('products-list', 'ProductController@index');
    $router->get('products-show/{id}', 'ProductController@show');
    $router->get('products-edit/{id}', 'ProductController@edit');
    $router->put('products-update/{id}', 'ProductController@update');
    $router->delete('products-delete/{id}', 'ProductController@delete');
});

$router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function () use ($router) {
    $router->get('logs', 'LogViewerController@index');
});
