app.controller('RegisterController', ['$scope', 'localStorageService', '$state', '$stateParams', 'Notification', 'fetcher', function ($scope, localStorageService, $state, $stateParams, Notification, fetcher) {
    'use strict';

    $scope.email            = "";
    $scope.username         = "";
    $scope.password         = "";
    $scope.confirm_password = "";
    $scope.gender           = "";
    $scope.age_range        = "";

    $scope.language_list    = [];
    $scope.selection        = [];

    $scope.error_message    = "";

    var referral            = $stateParams.ref || null;

    var clear   = function () {
        $scope.email            = "";
        $scope.username         = "";
        $scope.password         = "";

        $scope.selection        = [];
    }

    var init    = function () {
        fetcher.getLanguage(function (response) {
            $scope.language_list    = response.result;
        });
    };

    $scope.redirect_login    = function() {
        $state.go('auth.login')
    }

    $scope.register = function() {
        $scope.error    = false;
        if ($scope.selection.length == 0) {
            $scope.error            = true;
            $scope.error_message    = "You must pick at least one language.";
        } else if ($scope.password !== $scope.confirm_password) {
            $scope.error            = true;
            $scope.error_message    = "Your password didn't match.";
        } else {
            var data = {
                username        : $scope.username,
                password        : CryptoJS.SHA1($scope.password).toString(),
                email           : $scope.email,
                spoken_language : JSON.stringify($scope.selection),
                gender          : $scope.gender,
                age_range       : $scope.age_range,
                referral        : referral
            };

            fetcher.postRegister(data, function(response) {
                if ((response.status_code == 200) && (response.response == "OK")) {
                    localStorageService.set('username', $scope.username);

                    $state.go('auth.unconfirmed');
                } else {
                    $scope.error            = true;
                    $scope.password         = "";
                    $scope.confirm_password = "";

                    $scope.error_message    = response.message;
                }
            });
        }

    };

    $scope.toggleSelection = function (language_id) {
        var idx = $scope.selection.indexOf(language_id);

        if (idx > -1) {
            $scope.selection.splice(idx, 1);
        } else {
            $scope.selection.push(language_id);
        }
    };

    init();
}]);
