app.controller('CategorizeController', ['$scope', 'localStorageService', '$state', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.user_language        = [];
    $scope.category_list        = [];
    $scope.uncategorize_word    = [];
    $scope.remainder            = [];

    $scope.selected_category    = [];

    $scope.$parent.hideHeader   = false;
    $scope.$parent.hideNavbar   = false;

    $scope.head                 = $sce.trustAsHtml(config.categorizeHead);
    $scope.disabled             = false;

    $scope.categorize = function (id) {
        if (_.contains($scope.selected_category, id)) {
            $scope.selected_category   = _.reject($scope.selected_category, function (val) {
                return val  == id;
            })
        } else {
            $scope.selected_category.push(id);
        }
    };

    $scope.input_categorize = function () {
        if (!$scope.disabled) {
            $scope.disabled         = true;
            var data    = {
                categories      : JSON.stringify($scope.selected_category),
                translated_id   : $scope.uncategorize_word.translated_id,
                user_id         : localStorageService.get('_id'),
            }

            fetcher.postCategorize(data, function (response) {
                if ((response.status_code == "200") && (response.response == "OK")) {
                    //                Notification.info(messageHelper.gainPointMsg(response.result, "categorizing a word."));
                    $scope.$parent.points += response.result;
                } else {
                    Notification.error(messageHelper.massiveErrorMsg());
                }
                // $scope.disabled = false;
                $scope.skip();
            });

        }
    }

    $scope.skip = function () {
        $scope.$parent.refresh();
        $scope.selected_category    = [];
        var newPage = fetcher.getRandomState();
        if ($scope.$parent.activePage == newPage) {
            init();
        } else {
            $scope.$parent.activePage = newPage;
        }
    };

    $scope.random = function() {
        return 0.5 - Math.random();
    };

    var init    = function () {
        fetcher.getCategorize(localStorageService.get('_id'), function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                if (response.result !== null) {
                    $scope.category_list        = response.result.category;
                    $scope.uncategorize_word    = response.result.uncategorized;

                    $scope.category_list.category_items = ($scope.category_list.category_items);

                    var minus = (3 - _.size($scope.category_list.category_items) % 3);

                    if (minus !== 3) {
                        $scope.remainder    = [];
                        for (var i = 0; i < minus; i++) {
                            $scope.remainder.push(i + 1);
                        }
                    }

                    $scope.disabled = false;
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

app.directive('forceHeight', function ($window, $timeout) {
    var sameHeight = {
        restrict: 'A',
        groups: {},
        link: function (scope, element, attrs) {
            $timeout(getHighest);
            angular.element($window).bind('resize', getHighest);

            function getHighest() {
                if (!sameHeight.groups[attrs.sameHeight]) {
                    sameHeight.groups[attrs.sameHeight] = {
                        height: 0,
                        elems:[]
                    };
                }

                sameHeight.groups[attrs.sameHeight].elems.push(element);
                element.css('height', '');

                if (sameHeight.groups[attrs.sameHeight].height < element.outerHeight()) {
                    sameHeight.groups[attrs.sameHeight].height = element.outerHeight();
                }

                if (scope.$last) {
                    angular.forEach(sameHeight.groups[attrs.sameHeight].elems, function(elem){
                        var newHeight   = sameHeight.groups[attrs.sameHeight].height;

                        if (newHeight < 90) {
                            newHeight = 90;
                        }

                        elem.css('height', newHeight);
                    });

                    sameHeight.groups[attrs.sameHeight].height = 0;
                }
            }
        }
    };

    return sameHeight;
});

app.directive('errSrc', function() {
    return {
        link: function(scope, element, attrs) {
            element.bind('error', function() {
                if (attrs.src != attrs.errSrc) {
                    attrs.$set('src', attrs.errSrc);
                }
            });
        }
    }
});
