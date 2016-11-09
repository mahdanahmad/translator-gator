var angular = angular;
var app     = angular.module('app', ['ui.router', ('ct.ui.router.extras.core'), 'permission', 'permission.ui', 'LocalStorageModule', 'chart.js', 'ui.bootstrap', 'ui-notification', 'toggle-switch', 'ngTouch', 'datatables', 'datatables.buttons']);

app.config(function ($stateProvider, $locationProvider, $urlRouterProvider) {
    'use strict';

    // use the HTML5 History API
    $locationProvider.html5Mode(true);

    $urlRouterProvider.otherwise( function($injector, $location) {
        var $state = $injector.get("$state");
        $state.go("auth.login");
    });

    $stateProvider
        .state('auth', {
            url: '/auth',
            templateUrl: 'views/auth',
            controller: 'AuthController',
            abstract:true,
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        })
        .state('auth.login', {
            url: '/login',
            templateUrl: 'views/login',
            controller: 'LoginController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.register', {
            url: '/register?ref',
            templateUrl: 'views/register',
            controller: 'RegisterController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.logout', {
            url: '/logout',
            templateUrl: 'views/logout',
            controller: 'LogoutController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.forgot', {
            url: '/forgot',
            templateUrl: 'views/forgot',
            controller: 'ForgotController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.reset', {
            url: '/reset/:resetcode',
            templateUrl: 'views/reset',
            controller: 'ResetController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.confirm', {
            url: '/confirm/:confirmcode',
            templateUrl: 'views/confirm',
            controller: 'ConfirmController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('auth.unconfirmed', {
            url: '/unconfirmed',
            templateUrl: 'views/unconfirmed',
            controller: 'UnconfirmedController',
            data: { permissions: { except: ['user', 'admin'], redirectTo: 'dashboard.drift' }}
        }).
        state('dashboard', {
            url: '/dashboard',
            templateUrl: 'views/dashboard',
            controller: 'DashboardController',
            abstract:true,
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.drift', {
            url: '/drift',
            templateUrl: 'views/drift',
            controller: 'DriftController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.newuser', {
            url: '/newuser',
            templateUrl: 'views/newuser',
            controller: 'NewuserController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.profile', {
            url: '/profile',
            templateUrl: 'views/profile',
            controller: 'ProfileController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.translate', {
            url: '/translate',
            templateUrl: 'views/translate',
            controller: 'TranslateController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.alternative', {
            url: '/alternative',
            templateUrl: 'views/alternative',
            controller: 'AlternativeController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.vote', {
            url: '/vote',
            templateUrl: 'views/vote',
            controller: 'VoteController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.categorize', {
            url: '/categorize',
            templateUrl: 'views/categorize',
            controller: 'CategorizeController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.hint', {
            url: '/hint',
            templateUrl: 'views/hint',
            controller: 'HintController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.kicked', {
            url: '/kicked',
            templateUrl: 'views/kicked',
            controller: 'KickedController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('dashboard.redeem', {
            url: '/redeem',
            templateUrl: 'views/redeem',
            controller: 'RedeemController',
            data: { permissions: { only: ['user'], redirectTo: 'admin.statistic' }}
        }).
        state('admin', {
            url: '/admin',
            templateUrl: 'views/admin',
            controller: 'adminController',
            abstract:true,
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.statistic', {
            url: '/statistic',
            templateUrl: 'views/statistic',
            controller: 'statisticController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.words', {
            url: '/words',
            templateUrl: 'views/words',
            controller: 'wordsController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.general', {
            url: '/general',
            templateUrl: 'views/general',
            controller: 'generalController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.category', {
            url: '/category',
            templateUrl: 'views/category',
            controller: 'categoryController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.language', {
            url: '/language',
            templateUrl: 'views/language',
            controller: 'languageController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('admin.redeem', {
            url: '/redeem',
            templateUrl: 'views/redeemAdmin',
            controller: 'RedeemAdminController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        });

    $stateProvider.
        state('oldadmin', {
            url: '/oldadmin',
            templateUrl: 'views/oldadmin',
            controller: 'adminController',
            abstract:true,
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.statistic', {
            url: '/statistic',
            templateUrl: 'views/oldstatistic',
            controller: 'statisticController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.words', {
            url: '/words',
            templateUrl: 'views/oldwords',
            controller: 'wordsController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.general', {
            url: '/general',
            templateUrl: 'views/oldgeneral',
            controller: 'generalController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.category', {
            url: '/category',
            templateUrl: 'views/oldcategory',
            controller: 'categoryController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.language', {
            url: '/language',
            templateUrl: 'views/oldlanguage',
            controller: 'languageController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        }).
        state('oldadmin.redeem', {
            url: '/redeem',
            templateUrl: 'views/oldredeemAdmin',
            controller: 'RedeemAdminController',
            data: { permissions: { only: ['admin'], redirectTo: 'auth.login' }}
        });
});

app.controller('MainController', ['$scope', '$rootScope', function ($scope, $rootScope) {
    'use strict';

}]);

app.config(function (localStorageServiceProvider) {
  localStorageServiceProvider
    .setPrefix('translator-gator');
});

app.config(function(NotificationProvider) {
    NotificationProvider.setOptions({
        delay: 6000,
        startTop: 35,
        startRight: 35,
        verticalSpacing: 20,
        horizontalSpacing: 20,
        positionX: 'right',
        positionY: 'top',
    });
});

app.run(function(PermissionStore, localStorageService) {
    PermissionStore.definePermission('user', function() { return localStorageService.get('role') == 'user'; });
    PermissionStore.definePermission('admin', function() { return localStorageService.get('role') == 'admin'; });
});
