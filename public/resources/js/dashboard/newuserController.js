app.controller('NewuserController', ['$scope', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.$parent.hideHeader   = true;
    $scope.$parent.hideNavbar   = true;
    $scope.hideShare            = true;

    $scope.slides   = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'leaderboard'];
    $scope.index    = 0;

    $scope.hint     = {
        first   : $sce.trustAsHtml(config.firstHint),
        second  : $sce.trustAsHtml(config.secondHint),
        third   : {
            ceil    : $sce.trustAsHtml(config.thirdHint1),
            floor   : $sce.trustAsHtml(config.thirdHint2)
        },
        fourth  : $sce.trustAsHtml(config.fourthHint),
        fifth   : $sce.trustAsHtml(config.fifthHint),
        sixth   : $sce.trustAsHtml(config.sixthHint),
    };

    $scope.nextSlide = function() {
        if ($scope.index < $scope.slides.length - 1) {
            $scope.index++;
        } else {
//            $scope.index = 0;
            $scope.index = $scope.slides.length - 1;
        }
        changeHeader();
    };

    $scope.prevSlide = function() {
        if ($scope.index > 0) {
            $scope.index--;
        } else {
//            $scope.index = $scope.slides.length - 1;
            $scope.index = 0;
        }
        changeHeader();
    };

    $scope.goTo = function(index) {
        $scope.index    = index;
        changeHeader();
    }

    $scope.startTheGame = function() {
        fetcher.getRandomState(function(newPage) {
            $scope.$parent.activePage   = newPage;
        });
    };

    var changeHeader = function() {
        if (($scope.slides.indexOf('third') == $scope.index) || ($scope.slides.indexOf('fourth') == $scope.index)) {
            $scope.$parent.hideHeader   = false;
            angular.element('#content-newuser').css('padding-top', 0);
        } else {
            $scope.$parent.hideHeader   = true;
            angular.element('#content-newuser').css('padding-top', 40);
        }
    };

    var init = function() {
        fetcher.getLeaderboard(function (response) {
            $scope.leaderboard = response.result;
        });
    };

    init();

}]);
