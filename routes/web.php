<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('/account/create', 'AccountController@register');
$router->post('/account/confirmation', 'AccountController@confirmationEmail');
$router->post('/account/confirmation/refresh', 'AccountController@refreshConfirmationEmail');

$router->post('/auth/login', 'AuthController@login');

$router->group(['middleware' => 'auth.jwt'], function () use ($router) {
    $router->post('/auth/refresh', 'AuthController@refresh');
    $router->post('/auth/logout', 'AuthController@logout');
    $router->post('/account/password/reset', 'AccountController@resetPassword');
    $router->put('/account/password', 'AccountController@updatePassword');

    // Users
    $router->get('/users', 'UserController@index');
    $router->put('/users', 'UserController@update');
    $router->get('/users', 'UserController@me');
    $router->get('/users/{id}', 'UserController@show');
    $router->delete('/users', 'UserController@meDestroy');
    $router->delete('/users/{id}', 'UserController@destroy');
});
