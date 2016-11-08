app.controller('KickedController', ['$scope', '$state', 'localStorageService', '$sce', '$timeout', 'config', 'fetcher', 'messageHelper', 'Notification', function ($scope, $state, localStorageService, $sce, $timeout, config, fetcher, messageHelper, Notification) {
    'use strict';

    $scope.hour     = 0;
    $scope.minute   = 0;
    $scope.second   = 0;

    $scope.$parent.hideHeader   = false;
    $scope.$parent.hideNavbar   = true;

    var countdown;

    var onTimeout = function(){
        $scope.second--;

        if ($scope.second == -1) {
            $scope.second   = 59;
            $scope.minute--;
            if ($scope.minute == -2) {
                $scope.minute   = 58;
                $scope.hour--;
            }
        }

        if ($scope.second == 0 && $scope.minute == 0 && $scope.hour == 0) {
            stop();
            $scope.$parent.refresh();
            $scope.activePage   = fetcher.getRandomState();
        } else {
            countdown = $timeout(onTimeout,1000);
        }
    }

    $scope.reconfigure  = function (number) {
        if (number < 10) {
            return "0" + number;
        } else {
            return "" + number;
        }
    }

    var stop = function(){
        $timeout.cancel(countdown);
    }

    var init = function() {
        fetcher.getUser(localStorageService.get('_id'), function(response) {
            if ((response.status_code) == "200" && (response.response == "OK")) {
                $scope.$parent.backToGame();
            } else {
                if ((response.status_code == '400') && (response.message == "User doesn't exist")) {
                    localStorageService.remove('role');
                    localStorageService.remove('_id');
                    localStorageService.remove('username');

                    $state.go('auth.login');
                } else {
                    countdown = $timeout(onTimeout,1000);

                    $scope.hour     = Math.floor(response.result.countdown / 3600);
                    $scope.minute   = Math.floor((response.result.countdown / 60) % 60);
                    $scope.second   = response.result.countdown % 60;

                    $scope.message  = $sce.trustAsHtml(config.kicked_msg.replace('((time))', (response.result.time / 60)));

                    $scope.$parent.points   = response.result.points;
                }
            }
        });
    }

    init();

}]);
