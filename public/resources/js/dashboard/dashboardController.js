app.controller('DashboardController', ['$scope', '$location', 'localStorageService', '$state', 'fetcher', 'config', 'messageHelper', function ($scope, $location, localStorageService, $state, fetcher, config, messageHelper) {
    'use strict';

    $scope.activePage   = $location.path().replace('/dashboard/', '');
    $scope.username     = localStorageService.get('username');
    $scope.available    = [];
    $scope.needClose    = false;
    $scope.points       = 0;
    $scope.health       = 0;
    $scope.max_health   = 1;
    $scope.on_exit_id   = {
        origin_id       : null,
        translated_id   : null,
        category_items  : null,
    };

    $scope.batteryBar   = {
        red     : config.batteryRed,
        yellow  : config.batteryYellow,
        green   : config.batteryGreen,
    }

    $scope.imageSrc     = {
        hint    : {
            select      : "resources/img/icons/hint_select.png",
            unselect    : "resources/img/icons/hint.png",
        },
        profile : {
            select      : "resources/img/icons/profile_select.png",
            unselect    : "resources/img/icons/profile.png",
        },
        logout  : {
            select      : "resources/img/icons/logout_select.png",
            unselect    : "resources/img/icons/logout.png",
        },
        exit    : {
            select      : "resources/img/icons/exit_select.png",
            unselect    : "resources/img/icons/exit.png",
        },
    }

    $scope.batteryColor = function(value) {
        if (value < $scope.batteryBar.red) {

        } else if (value < $scope.batteryBar.yellow) {
            return "red-battery";
        } else if (value < $scope.batteryBar.green) {
            return "yellow-battery";
        } else if (value >= $scope.batteryBar.green) {
            return "green-battery";
        }
    }

    $scope.openHint     = function() {
        $scope.writeLog('exit', null, null, 'exit to hint', $scope.on_exit_id.origin_id, $scope.on_exit_id.translated_id, $scope.on_exit_id.category_items);
        $scope.needClose    = true;
        $state.go('dashboard.hint');
    }

    $scope.openProfile  = function() {
        $scope.writeLog('exit', null, null, 'exit to profile', $scope.on_exit_id.origin_id, $scope.on_exit_id.translated_id, $scope.on_exit_id.category_items);
        $scope.needClose    = true;
        $state.go('dashboard.profile');
    }

    $scope.logout       = function() {
        $scope.writeLog('exit', null, null, 'exit to logout', $scope.on_exit_id.origin_id, $scope.on_exit_id.translated_id, $scope.on_exit_id.category_items);
        localStorageService.remove('role');
        $state.go('auth.logout');
    }

    $scope.backToGame   = function() {
        fetcher.getRandomState(function(newPage) {
            $scope.refresh();
            $scope.activePage   = newPage;
            $state.go('dashboard.' + newPage);
            $scope.needClose    = false;
        });
    }

    $scope.writeLog     = function(action_type, result, affected_user, raw_result, origin_id, translated_id, category_items) {
        if (!_.isNil(localStorageService.get('_id')) && !_.isNil(action_type)) {
            var data    = {
                result          : result,
                user_id         : localStorageService.get('_id'),
                origin_id       : origin_id,
                raw_result      : raw_result,
                action_type     : action_type,
                affected_user   : affected_user,
                translated_id   : translated_id,
                category_items  : category_items,
            }

            fetcher.postLog(_.omitBy(data, _.isNil), function(response) {});
        }
    }

    $scope.$watch('activePage', function(newValue, oldValue) {
        if (newValue !== oldValue) { $state.go('dashboard.' + newValue); }
    });

    $scope.refresh      = function() {
        fetcher.getUser(localStorageService.get('_id'), function(response) {
            if ((response.status_code) == "200" && (response.response == "OK")) {
                $scope.points       = response.result.user.point;
                if ($scope.activePage !== 'kicked') {
                    $scope.health       = response.result.user.health;
                    $scope.max_health   = response.result.max_health;
                }
            } else {
                $state.go('dashboard.kicked');
            }
        });
    }

    var init            = function () {
        fetcher.getAction(function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                $scope.available    = response.result;

                var exclude = ['profile', 'hint', 'newuser', 'drift', 'kicked', 'redeem'];

                if ((exclude.indexOf($scope.activePage) == -1) &&
                    (response.result.indexOf($scope.activePage) == -1)) {
                    var nextPage        = _.sample(response.result);
                    $scope.activePage   = nextPage;
                    $state.go('dashboard.' + nextPage);
                }
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });

        $scope.refresh();
    };

    init();

}]);

app.directive('imgSrc', function($window) {
    return {
        link: function(scope, element, attrs) {
            var type        = 'unselect';
            var windowWidth = $window.innerWidth;

            if ((typeof attrs.ngHide == 'undefined') && (windowWidth > 992)) {
               type = 'select';
            }

            attrs.$set('src', scope.imageSrc[attrs.imgSrc][type]);
        }
    }
});

app.directive('sourceHeight', function () {
    return {
        link: function( scope, elem, attrs ) {
            scope.__height      = 0;

            scope.$watch( function() {
                scope.__height  = $(elem)[0].getBoundingClientRect().height;
            });
        }
    }
});

app.directive('targetHeight', function () {
    return {
        link: function( scope, elem, attrs ) {
            scope.$watch( '__height', function( newHeight, oldHeight ) {
                if (newHeight > 61) {
                    $(elem).css('height', newHeight);
                } else {
                    $(elem).css('height', 61);
                }
            });
        }
    }
});
