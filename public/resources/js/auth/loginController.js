app.controller('LoginController', ['$scope', '$state', 'localStorageService', '$window', 'Notification', 'fetcher', function ($scope, $state, localStorageService, $window, Notification, fetcher) {
    'use strict';

    $scope.username = localStorageService.get('username') || "";
    $scope.password = "";

    $scope.error    = false;

    $scope.redirect_register    = function() {
        $state.go('auth.register');
    }

    $scope.forgot_register      = function() {
        $state.go('auth.forgot');
    }

    $scope.openURL              = function() {
        $window.open('http://www.unglobalpulse.org/');
    }

    $scope.login = function() {
        $scope.error = false;
        if ($scope.username, $scope.password) {
            var data = {
                username        : $scope.username,
                password        : CryptoJS.SHA1($scope.password).toString(),
            };

            fetcher.postLogin(data, function (response) {
                if ((response.status_code == 200) && (response.response == "OK")) {
                    Notification.info({
                        message : "Welcome back " + $scope.username + "!",
                        delay   : 2000
                    });

                    localStorageService.set('username', $scope.username);
                    localStorageService.set('role', response.result.role);
                    localStorageService.set('_id', response.result._id);

                    if (response.result.role == 'user') {
                        if (response.result.isVirgin) {
                            $state.go('dashboard.newuser');
                        } else {
                            $state.go('dashboard.drift');
                        }
                    } else if (response.result.role == 'admin') {
                        $state.go('admin.statistic');
                    }
                } else if (response.message == "User unconfirmed, do you want to resent confirmation ?") {
                    localStorageService.set('username', $scope.username);
                    $state.go('auth.unconfirmed');
                } else {
                    $scope.error    = true;
                    $scope.message  = response.message;
                    $scope.password = "";
                }
            });
        }
    }
}]);
