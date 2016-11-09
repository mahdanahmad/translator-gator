app.controller('TranslateController', ['$scope', 'localStorageService', '$state', '$sce', 'fetcher', 'config', 'messageHelper', 'Notification', function ($scope, localStorageService, $state, $sce, fetcher, config, messageHelper, Notification) {
    'use strict';

    $('textarea.form_input_word').attr("tabIndex", -1).focus();

    $scope.language         = {};
    $scope.words_list       = [];

    $scope.placeholder      = "";
    $scope.disabled         = false;

    $scope.$parent.hideHeader   = false;
    $scope.$parent.hideNavbar   = false;

    String.prototype.capitalizeFirstLetter = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    var language_list       = [];

    $scope.input_translation = function () {
        if (!$scope.disabled) {
            $scope.disabled         = true;

            var translation_array   = [];

            _.each($scope.translate_list, function(val, key) {
                translation_array.push({
                    origin_id       : val._id,
                    translated_to   : val.translation || "",
                });
            });

            var data = {
                user_id         : localStorageService.get('_id'),
                language_id     : $scope.language._id,
                translations    : JSON.stringify(translation_array),
                action_type     : 'translate',
            }

            fetcher.postTranslate(data, function(response) {
                if ((response.status_code == "200")) {
                    if (response.result !== 0) {
                        // Notification.info(messageHelper.gainPointMsg(response.result, "translating some word(s)"));

                        $scope.$parent.points += response.result;
                    } else {
                        Notification.warning(messageHelper.noPointMsg("didn't give any translation"));
                    }
                    // $scope.disabled = false;
                    $scope.skip();
                }
            });
        }

    };

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

    var init    = function () {
        fetcher.getUserLanguage(localStorageService.get('_id'), function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                $scope.language = _.sample(response.result);
                $scope.head     = $sce.trustAsHtml(config.translateHead.replace("((language))", $scope.language.language_name));

                $scope.placeholder      = "Type here in Bahasa " + $scope.language.language_name.capitalizeFirstLetter();
                fetcher.getTranslate(function (response) {
                    if ((response.status_code == "200") && (response.response == "OK")) {
                        $scope.translate_list   = response.result;
                    } else {
                        Notification.error(messageHelper.massiveErrorMsg());
                    }
                });

                $scope.disabled = false;
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });
    };

    init();
}]);

app.directive('autofocus', ['$document', function($document) {
    return {
      link: function($scope, $element, attrs) {
        setTimeout(function() {
          $element[0].focus();
        }, 100);
      }
    };
  }])
;

app.directive('ngPlaceholder', function() {
    return {
        restrict: 'A',
        scope: {
            placeholder: '=ngPlaceholder'
        },
        link: function(scope, elem, attr) {
            scope.$watch('placeholder',function() {
                elem[0].placeholder = scope.placeholder;
            });
        }
    }
});
