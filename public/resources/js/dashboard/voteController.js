app.controller('VoteController', ['$scope', 'localStorageService', '$state', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.language         = {};
    $scope.vote_list        = [];

    $scope.$parent.hideHeader   = false;
    $scope.$parent.hideNavbar   = false;

    $scope.head                 = $sce.trustAsHtml(config.voteHead);
    $scope.disabled             = false;

    $scope.input_vote       = function () {
        if (!$scope.disabled) {
            $scope.disabled = true;

            var vote_array  = [];
            _.each($scope.vote_list, function(val, key) {
                vote_array.push({
                    translated_id   : val.translated_id,
                    action          : ("vote" + val.stat) || "",
                });
            });

            var data = {
                user_id         : localStorageService.get('_id'),
                votes           : JSON.stringify(vote_array),
            }

            fetcher.postVote(data, function(response) {
                if ((response.status_code == "200") && (response.response == "OK")) {
                    $scope.$parent.points += response.result;
                    // Notification.info(messageHelper.gainPointMsg(response.result, "voting some word(s)"));
                    $scope.skip();
                }
            });
        }
    }

    $scope.skip = function () {
        $scope.$parent.refresh();
        fetcher.getRandomState(function(newPage) {
            if ($scope.$parent.activePage == newPage) {
                init();
            } else {
                $scope.$parent.activePage = newPage;
            }
        });
    };

    $scope.upvote   = function (value) {
        if (value.stat  == 'up') {
            value.stat  = '';
        } else {
            value.stat  = 'up';
        }

        if ($scope.vote_list.length == 1) {
            $scope.input_vote();
        }
    }

    $scope.downvote   = function (value) {
        if (value.stat  == 'down') {
            value.stat  = '';
        } else {
            value.stat  = 'down';
        }

        if ($scope.vote_list.length == 1) {
            $scope.input_vote();
        }
    }

    var init    = function () {
        fetcher.getVote(localStorageService.get('_id'), function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                if (response.result !== null) {
                    $scope.language         = {
                        _id                 : response.result.language_id,
                        language_name       : response.result.language_name,
                    };
                    $scope.disabled     = false;
                    $scope.vote_list    = response.result.data;
                } else {
                    $scope.$parent.activePage = 'translate';
                    $state.go('dashboard.translate');
                }
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });
    };

    init();
}]);
