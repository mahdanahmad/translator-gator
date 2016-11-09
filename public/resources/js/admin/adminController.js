app.controller('adminController', ['$scope', '$location', 'localStorageService', '$state', function ($scope, $location, localStorageService, $state) {
    'use strict';

    $scope.activePage   = $location.path().replace('/admin/', '');
    $scope.username     = localStorageService.get('username');
    $scope.fold         = false;
    $scope.togglemobile = true;

    $scope.$watch('fold', function(newValue, oldValue) {
        if (newValue !== oldValue) {
            console.log(oldValue +  ' => ' +  newValue);
        }
    });

    $scope.logout = function() {
        localStorageService.remove('role');
        $state.go('auth.login');
    }
}]);
