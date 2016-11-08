app.controller('ResetController', ['$scope', '$state', '$stateParams', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, $state, $stateParams, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.show = false;
    $scope.head = config.reset_head;

    fetcher.checkForgot($stateParams.resetcode, function(response) {
        if ((response.status_code == "200") && (response.response == "OK")) {
            $scope.show = true;
        } else {
            $state.go('auth.login');
        }
    });

    $scope.cancel   = function() {
        $state.go('auth.login');
    }

    $scope.reset_password   = function() {
        if ($scope.password == $scope.confirm_password) {
            $scope.error    = false;

            var data    = {
                resetcode           : $stateParams.resetcode,
                password            : CryptoJS.SHA1($scope.password).toString(),
            }

            fetcher.postNewPassword(data, function(response) {
                if ((response.status_code == "200") && (response.response == "OK")) {
                    Notification.info(messageHelper.forgotMsg());
                    $state.go('auth.login');
                } else {
                    Notification.error(messageHelper.massiveErrorMsg());
                }
            });

        } else {
            $scope.error    = true;
            $scope.message  = "Password didn't match.";
        }
    }

}]);
