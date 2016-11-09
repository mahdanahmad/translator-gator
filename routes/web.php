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

$app->get('/', function () {
    return view('index');
});

$app->group(['prefix' => 'views'], function () use ($app) {
    $app->get('auth', function () {return view('auth.auth');});
    $app->get('login', function () {return view('auth.login');});
    $app->get('logout', function () {return view('auth.logout');});
    $app->get('register', function () {return view('auth.register');});
    $app->get('forgot', function () {return view('auth.forgot');});
    $app->get('reset', function () {return view('auth.reset');});
    $app->get('confirm', function () {return view('auth.confirm');});
    $app->get('unconfirmed', function () {return view('auth.unconfirmed');});

    $app->get('dashboard', function () {return view('dashboard.dashboard');});
    $app->get('vote', function () {return view('dashboard.vote');});
    $app->get('hint', function () {return view('dashboard.hint');});
    $app->get('drift', function () {return view('dashboard.drift');});
    $app->get('kicked', function () {return view('dashboard.kicked');});
    $app->get('redeem', function () {return view('dashboard.redeem');});
    $app->get('newuser', function () {return view('dashboard.newuser');});
    $app->get('profile', function () {return view('dashboard.profile');});
    $app->get('translate', function () {return view('dashboard.translate');});
    $app->get('categorize', function () {return view('dashboard.categorize');});
    $app->get('alternative', function () {return view('dashboard.alternative');});

    $app->get('admin', function () {return view('admin.admin');});
    $app->get('words', function () {return view('admin.words');});
    $app->get('general', function () {return view('admin.general');});
    $app->get('category', function () {return view('admin.category');});
    $app->get('language', function () {return view('admin.language');});
    $app->get('statistic', function () {return view('admin.statistic');});
    $app->get('redeemAdmin', function () {return view('admin.redeem');});

    $app->get('oldadmin', function () {return view('oldadmin.admin');});
    $app->get('oldwords', function () {return view('oldadmin.words');});
    $app->get('oldgeneral', function () {return view('oldadmin.general');});
    $app->get('oldcategory', function () {return view('oldadmin.category');});
    $app->get('oldlanguage', function () {return view('oldadmin.language');});
    $app->get('oldstatistic', function () {return view('oldadmin.statistic');});
    $app->get('oldredeemAdmin', function () {return view('oldadmin.redeem');});

    $app->get('details', function () {return view('template.details');});
    $app->get('firsthint', function () {return view('template.1stHint');});
    $app->get('secondhint', function () {return view('template.2ndHint');});
    $app->get('thirdhint', function () {return view('template.3rdHint');});
    $app->get('fourthhint', function () {return view('template.4thHint');});
    $app->get('fifthhint', function () {return view('template.5thHint');});
    $app->get('sixthhint', function () {return view('template.6thHint');});
    $app->get('redeemslide', function () {return view('template.redeem');});
    $app->get('historyslide', function () {return view('template.history');});
    $app->get('leaderboard', function () {return view('template.leaderboard');});

    $app->get('notification', function () {return view('notification');});
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

    $app->get('stats','TranslatedWordController@statistic');
    $app->get('export','TranslatedWordController@export');

    // CRUD Redeem
    $app->get('redeem', 'RedeemController@index');
    $app->post('redeem', 'RedeemController@store');
    $app->get('redeem/{id}', 'RedeemController@show');
    $app->post('redeem/upload', 'RedeemController@bulkStatus');
});
