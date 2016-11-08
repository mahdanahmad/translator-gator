app.controller('ForgotController', ['$scope', '$state', '$sce', 'fetcher', 'config', 'messageHelper', function ($scope, $state, $sce, fetcher, config, messageHelper) {
    'use strict';   
    
    $scope.error    = false;
    $scope.success  = false;
    
    $scope.head     = config.forgot_head;
    
    $scope.cancel   = function() {
        $state.go('auth.login');
    }
    
    $scope.send_email   = function() {
        var data    = {
            email   : $scope.email,
        };
        
        fetcher.postForgot(data, function(response) {
            $scope.error    = false;
            if ((response.status_code == 200) && (response.response == "OK")) {
                $scope.success  = true;
                $scope.message  = $sce.trustAsHtml(config.forgot_message.replace('((email))', $scope.email));
                
            } else if (response.message[0] == "The selected email is invalid.") {
                $scope.error        = true;
                $scope.errmessage   = config.forgot_404;
            }
        });
    }
}]);