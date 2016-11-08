app.controller('LogoutController', ['$scope', 'localStorageService', '$state', '$location', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $location, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.twitter          = {
        text        : config.twitter_text,
        hashtags    : config.twitter_hashtag,
        url         : config.twitter_url
                        .replace('((baseURL))', $location.protocol() + "%3A%2F%2F" + $location.host())
                        .replace('((referral))', "ref%3D" + localStorageService.get('username')),
    };

    $scope.backToLogin  = function() {
        $state.go('auth.login');
    };

    $scope.share    = function(type) {
        switch (type) {
            case "facebook" :
                FB.ui({
                    method      : 'feed',
                    name        : config.fb_name,
                    caption     : config.fb_caption
                                    .replace("((point))", $scope.$parent.points)
                                    .replace('((baseURL))', $location.protocol() + "://" + $location.host())
                                    .replace('((referral))', "ref=" + localStorageService.get('username')),
                    description : config.fb_description
                                    .replace("((point))", $scope.$parent.points)
                                    .replace('((baseURL))', $location.protocol() + "://" + $location.host())
                                    .replace('((referral))', "ref=" + localStorageService.get('username')),
                    link        : config.fb_link
                                    .replace('((baseURL))', $location.protocol() + "://" + $location.host())
                                    .replace('((referral))', "ref=" + localStorageService.get('username')),
                    picture     : config.fb_picture
                                    .replace('((baseURL))', $location.protocol() + "://" + $location.host()),
                }, function(response){});
                break;
            case "twitter"  :
                $scope.twitter.text = $scope.twitter.text.replace("((point))", $scope.$parent.points);
                break;
            case "path"     :

                break;
            default         :
                break;
        }
    }

    var init    = function () {
        fetcher.getUser(localStorageService.get('_id'), function(response) {
            if ((response.status_code) == "200" && (response.response == "OK")) {
                $scope.$parent.points   = response.result.user.point;
            }
        });

        fetcher.getLeaderboard(function (response) {
            $scope.leaderboard = response.result;
        });
     };

    init();
}]);
