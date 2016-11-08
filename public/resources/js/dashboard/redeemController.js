app.controller('RedeemController', ['$scope', 'localStorageService', '$state', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.$parent.hideHeader   = true;
    $scope.$parent.needClose    = true;
    $scope.$parent.hideNavbar   = false;

    $scope.redeem_value     = 0;
    $scope.slides           = ['hint', 'redeem', 'history'];
    $scope.index            = 0;
    $scope.redeem           = {
        'mobile'    : "",
        'credits'   : "",
    };
    $scope.history          = [];

    $scope.hint    = {
        fifth   : $sce.trustAsHtml(config.fifthHint),
    };

    $scope.redeemCredits    = config.redeem;

    $scope.redeemDisable    = false;

    $scope.nextSlide = function() {
        if ($scope.index < $scope.slides.length - 1) {
            $scope.index++;
        } else {
            $scope.index = $scope.slides.length - 1;
        }
    };

    $scope.prevSlide = function() {
        if ($scope.index > 0) {
            $scope.index--;
        } else {
            $scope.index = 0;
        }
    };

    $scope.checkCredit      = function(data) {
        return ($scope.$parent.points * $scope.redeem_value) < data;
    }

    $scope.redeemSubmit     = function() {
        // $scope.redeemDisable    = true;

        if ($scope.redeem.mobile.toString().substring(0, 2) == '62') {
            var data    = {
                'user_id'   : localStorageService.get('_id'),
                'mobile'    : parseInt($scope.redeem.mobile.toString().substr(2)),
                'credit'    : $scope.redeem.credits,
            };
        } else {
            var data    = {
                'user_id'   : localStorageService.get('_id'),
                'mobile'    : $scope.redeem.mobile,
                'credit'    : $scope.redeem.credits,
            };
        }

        // console.log(data);

        fetcher.postRedeem(data, function(response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                Notification.info("Your redeem request is already submitted.");

                $scope.$parent.points   = response.result;

                fetcher.getUserRedeem(localStorageService.get('_id'), function(response){
                    if ((response.status_code == "200") && (response.response == "OK")) {
                        $scope.history  = response.result;
                        $scope.nextSlide();
                    } else {
                        // $scope.$parent.backToGame();
                    }
                });
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
            $scope.redeemDisable    = false;
            $scope.redeem           = {
                'mobile'    : '',
                'credits'   : '',
            }
        });
    };

    $scope.editCredit       = function(data) {
        return (data / 1000) + 'K';
    }

    var init = function () {
        fetcher.getRedeemTime(function(response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                if (!response.result.redeem_time) {
                    $scope.$parent.backToGame();
                } else {
                    $scope.redeem_value     = response.result.redeem_value
                    fetcher.getUserRedeem(localStorageService.get('_id'), function(resp){
                        if ((resp.status_code == "200") && (resp.response == "OK")) {
                            $scope.history  = resp.result;
                        } else {
                            $scope.$parent.backToGame();
                        }
                    });
                }
            } else {
                $scope.$parent.backToGame();
            }
        });

    };

    init();
}]);
