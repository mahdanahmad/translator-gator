app.controller('adminController', ['$scope', '$location', 'localStorageService', '$state', function ($scope, $location, localStorageService, $state) {
    'use strict';

    $scope.activePage   = $location.path().replace('/admin/', '');
    $scope.username     = localStorageService.get('username');
    $scope.fold         = false;
    $scope.togglemobile = false;

    $scope.logout = function() {
        localStorageService.remove('role');

        $state.go('auth.login');
    }
}]);
