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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'], function () use ($app) {

    // CRUD User
    $app->get('users','UserController@index');
    $app->get('users/{id}','UserController@show');
    $app->post('users','UserController@store');
    $app->put('users/{id}','UserController@update');
    // $app->delete('users/{id}','UserController@destroy');

    $app->get('leaderboard','UserController@leaderboard');
    $app->get('users/{id}/language','UserController@getLanguage');

    // User Account management
    $app->get('users/confirm/{confirmationcode}','AuthController@confirm');
    $app->get('users/forgot/{resetcode}','AuthController@checkresetcode');
    $app->post('users/login','AuthController@show');
    $app->post('users/newpassword','AuthController@newpassword');
    $app->post('users/resetpassword','AuthController@resetpassword');
    $app->post('users/resetconfirmation','AuthController@resetconfirmation');

    // CRUD Configurations
    $app->get('config','ConfigurationController@index');
    $app->put('config','ConfigurationController@update');
    $app->get('config/action','ConfigurationController@getAction');
    $app->get('config/redeem','ConfigurationController@getRedeemTime');

    // CRUD Languages
    $app->get('languages','LanguageController@index');
    $app->get('languages/{id}','LanguageController@show');
    $app->post('languages','LanguageController@store');
    $app->put('languages/{id}','LanguageController@update');
    $app->delete('languages/{id}','LanguageController@destroy');

    // CRUD Categories
    $app->get('categories','CategoryController@index');
    $app->get('categories/{id}','CategoryController@show');
    $app->post('categories','CategoryController@store');
    $app->put('categories/{id}','CategoryController@update');
    $app->delete('categories/{id}','CategoryController@destroy');

    // CRUD Category Items
    $app->get('categories/{category_id}/items','CategoryItemController@index');
    $app->get('categories/{category_id}/items/{id}','CategoryItemController@show');
    $app->post('categories/{category_id}/items','CategoryItemController@store');
    $app->put('categories/{category_id}/items/{id}','CategoryItemController@update');
    $app->delete('categories/{category_id}/items/{id}','CategoryItemController@destroy');

    // Origin Words
    // $app->get('originwords','OriginWordController@index');
    // $app->get('originwords/random','OriginWordController@getRandom');
    $app->post('originwords','OriginWordController@store');

    // End point for game purpose
    $app->get('untranslated','GameController@getUntranslated');
    $app->get('votewords/{user_id}','GameController@getNextVote');
    $app->get('alternatewords/{user_id}','GameController@getNextAlternate');
    $app->get('categorizewords/{user_id}','GameController@getNextCategorize');
    $app->post('votewords','GameController@storeVote');
    $app->post('translatedwords','GameController@storeTranslated');
    $app->post('categorizewords','GameController@storeCategorize');

    // API terkait statistik
    $app->get('stats','TranslatedWordController@statistic');

    // API terkait Export
    $app->get('export','TranslatedWordController@export');

    // API terkait Redeem
    $app->get('redeem', 'RedeemController@index');
    $app->post('redeem', 'RedeemController@store');
    $app->get('redeem/{id}', 'RedeemController@show');
    $app->post('redeem/upload', 'RedeemController@bulkStatus');
});
