<?php

$router->group([
    'middleware' => 'auth',
    'namespace' => 'Views',
], function ($router) {
    $router->get('volumes/{id}/abysses', [
        'as' => 'volumes-abysses',
        'uses' => 'AbyssesJobController@index',
    ]);

    $router->get('abysses/{id}/trainAnnotation', [
        'as' => 'abysses-train',
        'uses' => 'AbyssesJobController@train',
    ]);
    
    $router->get('abysses/{id}/showResult', [
        'as' => 'abysses-show',
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

    $router->get('abysses-jobs/{id}/training-proposals', 'TrainingProposalController@index');
    $router->post('abysses-jobs/{id}/training-proposals', 'TrainingProposalController@submit');
    $router->put('abysses/training-proposals/{id}', 'TrainingProposalController@update');

    $router->get('abysses-jobs/{id}/test', 'TestController@index');
    $router->post('abysses-jobs/{id}/test', 'TestController@submit');
    $router->put('abysses/test/{id}', 'TestController@update');

    $router->get('abysses-jobs/{id}/images/{id2}/retraining-proposals', 'MaiaJobImagesController@indexTrainingProposals');

});
