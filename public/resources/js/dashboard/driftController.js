app.controller('DriftController', ['$scope', 'localStorageService', 'fetcher', 'messageHelper', 'Notification', function ($scope, localStorageService, fetcher, messageHelper, Notification) {
    'use strict';
    
    $scope.username             = localStorageService.get("username");
    
    $scope.$parent.hideHeader   = true;
    $scope.$parent.hideNavbar   = true;
    $scope.hideShare            = true;
    
    $scope.leaderboard          = [];
    
    $scope.startTheGame         = function() {
        $scope.$parent.activePage   = fetcher.getRandomState();
    };
    
    var init = function() {
        fetcher.getLeaderboard(function (response) {
            $scope.leaderboard = response.result;
        });
    };
    
    init();
}]);