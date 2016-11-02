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

    // Configurations, supposed to be admin only...
    $app->get('config','ConfigurationController@getConfig');
    $app->get('config/action','ConfigurationController@getAction');
    $app->get('config/redeem','ConfigurationController@getRedeemTime');
    $app->put('config','ConfigurationController@update');

    // Languages, supposed to be admin only too..
    $app->get('languages','LanguageController@index');
    $app->get('languages/{id}','LanguageController@get');
    $app->post('languages','LanguageController@create');
    $app->put('languages/{id}','LanguageController@edit');
    $app->delete('languages/{id}','LanguageController@delete');

    // CategoryGroup and CategoryItem..
    $app->get('categories','CategoryController@getAllCategory');
    $app->get('categories/{id}','CategoryController@getCategoryItem');
    $app->post('categories','CategoryController@createCategoryGroup');
    $app->post('category_items','CategoryController@createCategoryItem');
    $app->put('categories/{id}','CategoryController@updateCategoryGroup');
    $app->post('category_items/{id}','CategoryController@updateCategoryItem');
    $app->delete('categories/{id}','CategoryController@deleteCategoryGroup');
    $app->delete('category_items/{id}','CategoryController@deleteCategoryItem');

    // Origin Word Controller
    $app->get('originwords','OriginWordController@getAll');
    $app->get('originwords/random','OriginWordController@getRandom');
    $app->post('originwords','OriginWordController@create');

    // API Terkait fungsi Translate
    $app->get('translatedwords','TranslatedWordController@getAll');
    $app->post('translatedwords','TranslatedWordController@create');
    $app->get('untranslated','TranslatedWordController@getNextUntranslatedWord');

    // API terkait Alternate
    $app->get('alternatewords/{user_id}','TranslatedWordController@getNextAlternateWord'); // Catatan : untuk menambahkan alternate words, gunakan endpoint api yang sama dengan create translatated words (method post pada /translate_Words)

    // API terkait dengan Vote
    $app->get('votewords/{user_id}','TranslatedWordController@getNextVoteWord');
    $app->post('votewords','TranslatedWordController@create_vote_word');

    // API terkait dengan category
    $app->get('categorizewords/{user_id}','TranslatedWordController@getNextUncategorized');
    $app->post('categorizewords/','TranslatedWordController@create_categorized_word');

    // API terkait statistik
    $app->get('stats','TranslatedWordController@statistic');

    // API terkait Export
    $app->get('export','TranslatedWordController@export');

    $app->get('debug', 'debugController@debug');
    $app->get('down', 'debugController@downdown');
    $app->post('debug', 'debugController@uploadfile');
    $app->get('debug/mail/{username}','debugController@tesmail');
    $app->get('debug/stats','debugController@statistic');
    $app->get('debug/export','debugController@export');

    // API terkait Redeem
    $app->get('redeem', 'RedeemController@index');
    $app->post('redeem', 'RedeemController@store');
    $app->get('redeem/{id}', 'RedeemController@show');
    $app->post('redeem/upload', 'RedeemController@bulkStatus');
});
