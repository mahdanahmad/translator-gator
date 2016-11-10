app.controller('AlternativeController', ['$scope', 'localStorageService', '$state', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $scope.language         = {};
    $scope.alternative_list = [];

    $scope.placeholder      = "";

    $scope.$parent.hideHeader   = false;
    $scope.$parent.hideNavbar   = false;
    $scope.disabled             = false;

    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    $scope.input_alternative = function () {
        if (!$scope.disabled) {
            $scope.disabled         = true;

            var translation_array   = [];

            _.each($scope.alternative_list, function(val, key) {
                translation_array.push({
                    origin_id       : val.origin_id,
                    translated_to   : val.alternative || "",
                    translated_id   : val.translated_id || "",
                });
            });

            var data = {
                user_id         : localStorageService.get('_id'),
                language_id     : $scope.language._id,
                translations    : JSON.stringify(translation_array),
                action_type     : 'alternate',
            }

            fetcher.postAlternative(data, function(response) {
                if ((response.status_code == "200")) {
                    if (response.result !== 0) {
                        //                    Notification.info(messageHelper.gainPointMsg(response.result, "give some word(s) alternative"));
                        $scope.$parent.points += response.result;
                    } else {
                        Notification.warning(messageHelper.noPointMsg("didn't give any alternative"));
                    }
                    // $scope.disabled = false;
                    $scope.skip();
                } else {
                    Notification.error(messageHelper.massiveErrorMsg());
                }
            });
        }
    };

    $scope.skip = function () {
        $scope.$parent.writeLog('skip', null, null, 'skip on alternative', null, _.map($scope.alternative_list, 'translated_id'));
        $scope.$parent.refresh();
        fetcher.getRandomState(function(newPage) {
            if ($scope.$parent.activePage == newPage) {
                init();
            } else {
                $scope.$parent.activePage = newPage;
            }
        });
    };

    var init    = function () {
        fetcher.getAlternative(localStorageService.get('_id'), function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                if (response.result !== null) {
                    $scope.language         = {
                        _id                 : response.result.language_id,
                        language_name       : response.result.language_name,
                    };

                    $scope.head         = $sce.trustAsHtml(config.alternativeHead.replace("((language))", response.result.language_name).replace("((translated))", response.result.data[0].translated_to));
                    $scope.placeholder  = "Type here in Bahasa " + response.result.language_name.capitalizeFirstLetter();

                    $scope.disabled         = false;
                    $scope.alternative_list = response.result.data;

                    $scope.$parent.on_exit_id   = {
                        origin_id       : null,
                        translated_id   : _.map(response.result.data, 'translated_id'),
                        category_items  : null
                    };
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

app.directive('textwrapper', function() {
    return {
        link: function(scope, element, attrs) {
            var dummy       = element.children('.dummy');
            var textarea    = element.children('textarea');

            function formatDummyText( text ) {
                if ( !text ) {
                    return '&nbsp;';
                }

                return text.replace( /\n$/, '<br>&nbsp;' )
                .replace( /\n/g, '<br>' );
            }
            function heightChanger() {
                var top         = Math.max( 0, ( 140 - dummy.height() ) * 0.5 );
                textarea.css("padding-top", top + "px");
            }

            scope.$watch(
                function () {
                    return element.parent().height()
                },
                function(newValue, oldValue) {
                    if (newValue !== oldValue) {
                    }
                }
            );

            textarea.on( 'keyup change', function( event ) {
                dummy.html(formatDummyText(textarea.val()));
                heightChanger();
            });

            dummy.html(formatDummyText(textarea.val()));
            heightChanger();
        }
    }
});
