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
$router->post('/account/confirmation', 'AccountController@confirmation_email');
$router->post('/account/confirmation/refresh', 'AccountController@refreshConfirmationEmail');
$router->post('/account/password/forgot', 'AccountController@forgotPassword');
$router->put('/account/password/forgot/reset', 'AccountController@forgotPasswordConfirmation');

$router->post('/auth/login', 'AuthController@login');

$router->group(['middleware' => 'auth.jwt'], function () use ($router) {
    $router->post('/auth/refresh', 'AuthController@refresh');
    $router->post('/auth/logout', 'AuthController@logout');
    $router->post('/account/password/reset', 'AccountController@resetPassword');
    $router->put('/account/password', 'AccountController@updatePassword');

    // Users
    $router->get('/users', 'UserController@index');
    $router->put('/users', 'UserController@meUpdate');
    $router->put('/users/{id}', 'UserController@update');
    $router->get('/users/me', 'UserController@me');
    $router->get('/users/{id}', 'UserController@show');
    $router->delete('/users', 'UserController@meDestroy');
    $router->delete('/users/{id}', 'UserController@destroy');

    // Styles
    $router->post('/models/styles', 'ModelStyleController@create');
    $router->put('/models/styles', 'ModelStyleController@update');
    $router->get('/models/styles/{id}', 'ModelStyleController@show');
    $router->get('/models/styles', 'ModelStyleController@index');
    $router->delete('/models/styles/{id}', 'ModelStyleController@destroy');
});
