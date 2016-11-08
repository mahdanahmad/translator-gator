app.controller('generalController', ['$scope', 'Notification', 'fetcher', 'messageHelper', function ($scope, Notification, fetcher, messageHelper) {
    'use strict';
    
    $scope.config       = {};
    
    $scope.cfg_save = function(state) {
        var data    = {};
        if (state == 'page') {
            data    = {
                display_items_per_page      : $scope.config.display_items_per_page,
                display_options_per_page    : $scope.config.display_options_per_page,
            }
        } else if (state == 'point') {
            data    = {
                translate_point             : $scope.config.translate_point,
                alternate_point             : $scope.config.alternate_point,
                categorize_point            : $scope.config.categorize_point,
                voter_point                 : $scope.config.voter_point,
                vote_down_point             : $scope.config.vote_down_point,
                vote_up_point               : $scope.config.vote_up_point,
                referral_point              : $scope.config.referral_point,
            }
        } else if (state == 'battery') {
            data    = {
                max_health                  : $scope.config.max_health,
                kick_time                   : $scope.config.kick_time,
            }
        } else if (state == 'action') {
            data    = {
                is_on_translate             : $scope.config.is_on_translate + 0,
                is_on_alternative           : $scope.config.is_on_alternative + 0,
                is_on_vote                  : $scope.config.is_on_vote + 0,
                is_on_categorize            : $scope.config.is_on_categorize + 0,
            }
        } else if (state == 'redeem') {
            data    = {
                redeem_time                 : $scope.config.redeem_time,
                point_value                 : $scope.config.point_value + 0,
            }
        }
        
        if (!(_.isEmpty(data))) {
            fetcher.putConfig(data, function (response) {
                if ((response.status_code == 200) && (response.response == "OK")) {
                    Notification.info("Configuration successfully saved!");
                } else {
                    Notification.error(messageHelper.massiveErrorMsg());
                }
            });            
        } else {
            Notification.error(messageHelper.massiveErrorMsg());
        }
    };

        
    var init = function() {
        fetcher.getConfig(function (response) {
            if ((response.status_code == 200) && (response.response == "OK")) {
                $scope.config   = response.result;
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });
      
    }
    
    init();
}]);