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

$router->get('/payment/tests', function () use ($router) {
    return view('Payment');
});

$router->post('/account/create', 'AccountController@register');
$router->post('/account/confirmation', 'AccountController@confirmation_email');
$router->post('/account/confirmation/refresh', 'AccountController@refreshConfirmationEmail');
$router->post('/account/password/forgot', 'AccountController@forgotPassword');
$router->put('/account/password/forgot/reset', 'AccountController@forgotPasswordConfirmation');

$router->post('/auth/login/social/{channel}', 'AuthController@socialLogin');

$router->post('/auth/login', 'AuthController@login');

$router->group(['middleware' => 'auth.jwt'], function () use ($router) {
    $router->post('/auth/refresh', 'AuthController@refresh');
    $router->post('/auth/logout', 'AuthController@logout');
    $router->post('/account/password/reset', 'AccountController@resetPassword');
    $router->put('/account/password', 'AccountController@updatePassword');

    // Users Transaction
    $router->get('/users/transactions/{id}', 'TransactionController@show');
    $router->get('/users/transactions', 'TransactionController@index');
    $router->get('/users/{userId}/transactions/{id}', 'TransactionController@showByUser');
    $router->get('/users/{userId}/transactions', 'TransactionController@listByUser');

    // Users
    $router->get('/users', 'UserController@index');
    $router->put('/users', 'UserController@meUpdate');
    $router->put('/users/{id}', 'UserController@update');
    $router->get('/users/me', 'UserController@me');
    $router->get('/users/{id}', 'UserController@show');
    $router->delete('/users', 'UserController@meDestroy');
    $router->delete('/users/{id}', 'UserController@destroy');

    // Styles
    $router->post('/cards/styles', 'StyleController@create');
    $router->get('/cards/styles', 'StyleController@index');
    $router->get('/cards/styles/{id}', 'StyleController@show');
    $router->put('/cards/styles/{id}', 'StyleController@update');
    $router->delete('/cards/styles/{id}', 'StyleController@destroy');

    // Items
    $router->post('/items', 'ItemController@create');
    $router->get('/items', 'ItemController@index');
    $router->get('/items/{id}', 'ItemController@show');
    $router->put('/items/{id}', 'ItemController@update');
    $router->delete('/items/{id}', 'ItemController@destroy');

    // Payment
    $router->post('/payments/items/{itemId}/gateways/{gateway}', 'PaymentController@payment');
});
