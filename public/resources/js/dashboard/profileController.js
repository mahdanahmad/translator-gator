app.controller('ProfileController', ['$scope', 'localStorageService', '$state', '$window', '$location', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $window, $location, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.$parent.hideHeader   = true;
    $scope.$parent.needClose    = true;
    $scope.$parent.hideNavbar   = false;

    $scope.slides           = ['leaderboard', 'details'];
    $scope.index            = 0;

    $scope.profileDetails   = {};
    $scope.language_list    = {};
    $scope.leaderboard      = [];

    $scope.isEdited         = false;
    $scope.showRedeem       = false

    $scope.twitter          = {
        text        : config.twitter_text,
        hashtags    : config.twitter_hashtag,
        url         : config.twitter_url
                        .replace('((baseURL))', $location.protocol() + "%3A%2F%2F" + $location.host())
                        .replace('((referral))', "ref%3D" + localStorageService.get('username')),
    };

    $scope.nextSlide = function() {
        if ($scope.index < $scope.slides.length - 1) {
            $scope.index++;
        } else {
            $scope.index = 0;
        }
    };

    $scope.prevSlide = function() {
        if ($scope.index > 0) {
            $scope.index--;
        } else {
            $scope.index = $scope.slides.length - 1;
        }
    };

    $scope.goTo = function(index) {
        $scope.index    = index;
    }

    $scope.goToRedeem   = function() {
        $state.go('dashboard.redeem');
    }

    $scope.toggleSelection = function (language_id) {
        var idx = $scope.profileDetails.user.languages.indexOf(language_id);

        if (idx > -1) {
            $scope.profileDetails.user.languages.splice(idx, 1);
        } else {
            $scope.profileDetails.user.languages.push(language_id);
        }
    };

    $scope.editProfile = function () {
        var data = {
//            email       : $scope.profileDetails.email,
            languages   : JSON.stringify($scope.profileDetails.user.languages),
        };

        if ($scope.old_password) {
            if ($scope.new_password == $scope.password_again) {
                data.old_password = CryptoJS.SHA1($scope.old_password).toString();
                data.new_password = CryptoJS.SHA1($scope.new_password).toString();
            } else {
                Notification.error("Your new password didn't match");

                $scope.old_password     = "";
                $scope.new_password     = "";
                $scope.password_again   = "";

                return false;
            }
        } else {

        }

        fetcher.putUser(localStorageService.get('_id'), data, function (response) {
            $scope.old_password     = "";
            $scope.new_password     = "";
            $scope.password_again   = "";

            if ((response.status_code == "200") && (response.response == "OK")) {
                $scope.isEdited = false;
                Notification.info("Your profile successfully updated.");
//                init();
            } else {
                Notification.error(response.message);
            }
        });

    };

    $scope.edited   = function() {
//        console.log($scope.isEdited);
        $scope.isEdited = !$scope.isEdited;
    }

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

    var init = function () {
        fetcher.getLanguage(function (response) {
            _.each(response.result, function(val, key) {
                $scope.language_list[val._id] = val.language_name;
            });
        });
        fetcher.getUser(localStorageService.get('_id'), function(response) {
            if ((response.status_code) == "200" && (response.response == "OK")) {
                $scope.profileDetails       = response.result;
                $scope.$parent.points       = response.result.user.point;
                $scope.$parent.health       = response.result.user.health;
                $scope.$parent.max_health   = response.result.max_health;
                $scope.showRedeem           = response.result.redeem_time;
            }
        });

        fetcher.getLeaderboard(function (response) {
            $scope.leaderboard = response.result;
        });
    };

    init();
}]);
