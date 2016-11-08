app.controller('languageController', ['$scope', '$uibModal', 'Notification', 'fetcher', function ($scope, $uibModal, Notification, fetcher) {
    'use strict';
    
    $scope.language_list = [];
    
    $scope.addLanguage      = function () {
        var data = {
            language_name : $scope.new_language,
        }
        
        fetcher.postLanguage(data, function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                $scope.new_language = "";
                
                init();
            } else {
                Notification.error(response.message[0] || "Some error happened. We don't know and we'll back at you sooner.");
            }
        });
    }
    
    $scope.changeLanguage   = function (lang) {
        var before  = lang.language_name;
        var after   = lang.editedLanguage_name;
        
        var data    = {
            language_name   : lang.editedLanguage_name,
        }
        
        fetcher.putLanguage(lang._id, data, function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                Notification.info("Language " + before + " successfully changed into " + after + ".");
                
                lang.language_name  = lang.editedLanguage_name;
            }
        });
        
    }
    
    $scope.deleteLanguage   = function (lang) {
        var modalInstance = $uibModal.open({
            animation   : true,
            templateUrl : 'deleteLangModal.html',
            controller  : 'deleteLangModalCtrl',
            windowClass : 'delete-lang-modal',
            size        : 'sm',
            resolve     : {
                items   : function () {
                    return lang;
                }
            }
        });
        
        modalInstance.result.then(function (status) {
            if (status == 'OK') {
                fetcher.deleteLanguage(lang._id, function (response) {
                    if ((response.status_code == "200") && (response.response == "OK")) {
                        init();
                    }
                });
            }
        }, function () {

        });
    }
    
    var init    = function () {
        fetcher.getLanguage(function (response) {
            $scope.language_list    = response.result;
        });
    };
    
    init();
}]);

app.controller('deleteLangModalCtrl', ['$scope', '$modalInstance', 'items', function ($scope, $modalInstance, items) {
    $scope.lang = items;
    
    $scope.ok = function () {
        $modalInstance.close("OK");
    };

    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
    };
    
}]);

app.directive('ngEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if(event.which === 13) {
                scope.$apply(function(){
                    scope.$eval(attrs.ngEnter, {'event': event});
                });

                event.preventDefault();
            }
        });
    };
});