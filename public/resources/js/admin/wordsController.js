app.controller('wordsController', ['$scope', '$window', 'Notification', 'fetcher', 'messageHelper', function ($scope, $window, Notification, fetcher, messageHelper) {
    'use strict';
    
    $scope.language_list        = {};
    $scope.downloaded_language  = [];
    
    $scope.category_list        = {};
    $scope.selected_group       = "";
    $scope.downloaded_category  = [];
    
    $scope.upload_disabled      = false;
    $scope.download_disabled    = false;
    
    $scope.selectall            = {
        language    : false,
        category    : false,
    };
    
    $scope.upload   = function() {
        if (typeof ($scope.uploaded_file) !== 'undefined') {
            Notification.info("Uploading your file, just step back and smell the coffee.");
            $scope.upload_disabled  = true;
            
            var data    = new FormData();
            data.append('origin_files', $scope.uploaded_file);
            
            fetcher.postCSV(data, function(response) {
                delete $scope.uploaded_file;
                if ((response.status_code == 200) && (response.response == "OK")) {
                    Notification.info("Upload success, adding words to translate list.");
                } else {
                    Notification.error("Upload failed. Please comeback later while we figure this out.");
                }
                $scope.upload_disabled  = false;
            });
        } else {
            Notification.error("Please input a file before you upload.");
        }
    };
    
    $scope.download = function() {
        var filename = 'Crowdsource ' + Date.create().format('{yyyy}-{MM}-{dd} {hh}{mm}{ss}') + '.csv';

        var data = {
            languages   : JSON.stringify($scope.downloaded_language),
            categories  : JSON.stringify($scope.downloaded_category),
        };
        
        $window.open('/api/export?' + $.param(data), '_blank');
    };
    
    $scope.toggleSelection = function (type, id) {
        var idx = 0;

        if (type == 'language') {
            idx = $scope.downloaded_language.indexOf(id);
        } else if (type == 'category') {
            idx = $scope.downloaded_category.indexOf(id);
        }
        
        if (idx > -1) {
            if (type == 'language') {
                $scope.downloaded_language.splice(idx, 1);
            } else if (type == 'category') {
                $scope.downloaded_category.splice(idx, 1);
            }
        } else {
            if (type == 'language') {
                $scope.downloaded_language.push(id);
            } else if (type == 'category') {
                $scope.downloaded_category.push(id);
            }
        }
        
        checkAll(type);
    };
    
    $scope.tonggleAll   = function (type) {
        if ($scope.selectall[type]) {
            if (type == 'language') {
                $scope.downloaded_language  = [];
                _.each($scope.language_list, function(val, key) {
                    $scope.downloaded_language.push(val._id);
                });
            } else if (type == 'category') {
                $scope.downloaded_category  = [];
                _.each($scope.category_list, function(val, key) {
                    _.each(val.data, function(valval, keykey) {
                        $scope.downloaded_category.push(valval._id);
                    });
                });
            }
        } else {
            if (type == 'language') {
                $scope.downloaded_language  = [];
            }  else if (type == 'category') {
                $scope.downloaded_category  = [];
            }
        }
    }
    
    var checkAll    = function(type) {
        var result  = true;
        
        if (type == 'language') {
            result  = _.every($scope.language_list, function(some) {
                return $scope.downloaded_language.indexOf(some._id) > -1;
            });
        }  else if (type == 'category') {
//            if (result) {
            result  = _.every($scope.category_list, function(some) {
                return _.every(some.data, function(somesome) {
                    return $scope.downloaded_category.indexOf(somesome._id) > -1;
                });
            });
//            }
        }

        $scope.selectall[type] = result;
    };
    
    var init = function() {
        fetcher.getLanguage(function (response) {    
            $scope.language_list        = response.result;
//            $scope.downloaded_language  = response.result[0].language_name;
        });
        
        fetcher.getCategories(function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
//                console.log(response);
                $scope.category_list    = response.result;
                $scope.selected_group   = Object.keys(response.result)[0];
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            } 
        });
    }
    
    init();
}]);

app.directive('buttonUploader', function (Notification) {
    return {
        restrict: "A",
        link: function (scope, element, attrs) {
            $(element).on('change', function(event) {
                scope.$apply(function(scope) {
                    var checker = ~event.target.files[0].type.indexOf('csv');
                    
//                    console.log(checker);
//                    console.log(event.target.files[0]);
                    
                    if (checker !== -1) {
                        scope.uploaded_file = event.target.files[0];
                        
                    } else {
                        Notification.error("You only allowed upload an csv file!");
                    }
                });
            });
        }
   }
});