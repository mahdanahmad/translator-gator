app.controller('RedeemAdminController', ['$scope', '$uibModal', 'Notification', 'fetcher', 'messageHelper', 'DTOptionsBuilder', 'DTColumnDefBuilder', function ($scope, $uibModal, Notification, fetcher, messageHelper, DTOptionsBuilder, DTColumnDefBuilder) {
    'use strict';

    $scope.redeem   = {
        filter  : 'all',
        data    : [],
        show    : [],
    };
    $scope.table    = {
        options : DTOptionsBuilder.newOptions().withPaginationType('full_numbers').withButtons(['csv']).withDisplayLength(10),
        colums  : [

        ],
    };
    $scope.upload_disabled  = false;

    $scope.changeFilter     = function(data) {
        $scope.redeem.filter    = data;

        if (data == 'all') {
            $scope.redeem.show  = $scope.redeem.data;
        } else {
            $scope.redeem.show  = _.filter($scope.redeem.data, function(val){ return val.status.toLowerCase() == data.toLowerCase();});
        }
    };

    $scope.statusColor = function(value) {
        if (value.toLowerCase() == 'success') {
            return "status-success";
        } else if (value.toLowerCase() == 'failed') {
            return "status-failed";
        } else if (value.toLowerCase() == 'on progress') {
            return "status-onprogress";
        }
    }

    $scope.configureDate    = function(data) {
        return Date.create(data).format('{dd}-{mon}-{yyyy}');
    };

    $scope.init    = function() {
        fetcher.getRedeem(function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                $scope.redeem.data  = response.result;
                $scope.redeem.show  = response.result
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });
    };

    $scope.init();
}]);

app.directive('redeemUploader', function (Notification, fetcher) {
    return {
        restrict: "A",
        scope : false,
        link: function (scope, element, attrs) {
            $(element).on('change', function(event) {
                scope.$apply(function(scope) {
                    var checker = ~event.target.files[0].type.indexOf('csv');
                    if (checker !== -1) {
                        if (!scope.upload_disabled) {
                            scope.upload_disabled   = true;

                            var data    = new FormData();
                            data.append('data', event.target.files[0]);

                            fetcher.uploadRedeem(data, function(response) {
                                angular.element("input[type='file']").val(null);
                                if ((response.status_code == 200) && (response.response == "OK")) {
                                    scope.upload_disabled   = false;
                                    Notification.info("Upload success, redeem list updated.");
                                    scope.init();
                                } else {
                                    Notification.error("Upload failed. Please comeback later while we figure this out.");
                                }
                            });
                        } else {
                            Notification.info("Your file is already uploading, sit back and enjoy your coffee.");
                        }
                    } else {
                        Notification.error("You only allowed upload an csv file!");
                    }
                });
            });
        }
   }
});
