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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', ['as' => 'auth.register', 'uses' => 'AuthController@register']);
        $router->post('login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);
    });

    $router->group(['middleware' => 'auth', 'prefix' => 'todos'], function () use ($router) {
        $router->post('/', ['as' => 'todos.store', 'uses' => 'TodosController@store']);
        $router->get('/', ['as' => 'todos.index', 'uses' => 'TodosController@index']);
        $router->get('/{id}', ['as' => 'todos.show', 'uses' => 'TodosController@show']);
        $router->put('/{id}', ['as' => 'todos.update', 'uses' => 'TodosController@update']);
        $router->delete('/{id}', ['as' => 'todos.destroy', 'uses' => 'TodosController@destroy']);

        $router->post('/{id}/status/{status}', ['as' => 'todo.updateStatus', 'uses' => 'TodosController@updateStatus']);
    });
});
