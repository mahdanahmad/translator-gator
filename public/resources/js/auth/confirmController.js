app.controller('ConfirmController', ['$scope', '$state', '$stateParams', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, $state, $stateParams, fetcher, config, messageHelper, Notification) {
    'use strict';
    
    var data    = $stateParams.confirmcode;
    
    fetcher.getConfirm(data, function(response) {
        if ((response.status_code == 200) && (response.response == "OK")) {
            Notification.info(messageHelper.confirmMsg());
        }
        
        $state.go('auth.login');
    });
}]);