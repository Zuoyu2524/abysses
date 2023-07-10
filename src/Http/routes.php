<?php

$router->group([
    'middleware' => 'auth',
    'namespace' => 'Views',
], function ($router) {
    $router->get('volumes/{id}/abysses', [
        'as' => 'volumes-abysses',
        'uses' => 'AbyssesJobController@index',
    ]);

    $router->get('abysses/{id}', [
        'as' => 'abysses',
        'uses' => 'AbyssesJobController@show',
    ]);
});

$router->group([
    'middleware' => ['api', 'auth:web,api'],
    'namespace' => 'Api',
    'prefix' => 'api/v1',
], function ($router) {
    $router->resource('volumes/{id}/abysses-jobs', 'AbyssesJobController', [
        'only' => ['store'],
        'parameters' => ['volumes' => 'id'],
    ]);

    $router->resource('abysses-jobs', 'AbyssesJobController', [
        'only' => ['destroy'],
        'parameters' => ['abysses-jobs' => 'id'],
    ]);

    $router->post('abysses-jobs/{id}/test', 'TestController@index')->name('job-test');

});
