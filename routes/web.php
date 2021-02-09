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

    // Users
    $router->get('/users', 'UserController@index');
    $router->put('/users', 'UserController@meUpdate');
    $router->put('/users/{id}', 'UserController@update');
    $router->get('/users/me', 'UserController@me');
    $router->get('/users/{id}', 'UserController@show');
    $router->delete('/users', 'UserController@meDestroy');
    $router->delete('/users/{id}', 'UserController@destroy');

    // Users Transactions

    // Styles
    $router->post('/models/styles', 'ModelStyleController@create');
    $router->get('/models/styles', 'ModelStyleController@index');
    $router->get('/models/styles/{id}', 'ModelStyleController@show');
    $router->put('/models/styles/{id}', 'ModelStyleController@update');
    $router->delete('/models/styles/{id}', 'ModelStyleController@destroy');

    // Payment Items
    $router->post('/payments/items', 'PaymentItemController@create');
    $router->get('/payments/items', 'PaymentItemController@index');
    $router->get('/payments/items/{id}', 'PaymentItemController@show');
    $router->put('/payments/items/{id}', 'PaymentItemController@update');
    $router->delete('/payments/items/{id}', 'PaymentItemController@destroy');

    // Payment
    $router->post('/payments/items/{itemId}/gateways/{gateway}', 'PaymentController@payment');
});
