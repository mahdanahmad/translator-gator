app.controller('UnconfirmedController', ['$scope', '$state', '$sce', 'Notification', 'config', function ($scope, $state, $sce, Notification, config) {
    'use strict';

    $scope.head     = config.unconfirm_head;
    $scope.message  = $sce.trustAsHtml(config.unconfirm_msg);

    $scope.cancel   = function() {
        $state.go('auth.login');
    }
}]);
