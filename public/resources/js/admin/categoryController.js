app.controller('categoryController', ['$scope', '$uibModal', 'Notification', 'fetcher', 'messageHelper', function ($scope, $uibModal, Notification, fetcher, messageHelper) {
    'use strict';

    $scope.categories               = [];

    $scope.picked_category          = "";
    $scope.picked_category_group    = "";

    $scope.category_group_changed   = function () {
        if ($scope.categories[$scope.picked_category_group].category_items.length > 0) {
            var temp_category           = $scope.categories[$scope.picked_category_group].category_items[0];

            $scope.picked_category      = temp_category._id;
        } else {
            $scope.picked_category      = "";
        }
    }

    $scope.category_changed   = function () {
        var temp_category   = _.find($scope.categories[$scope.picked_category_group].category_items, function (val) {return val._id == $scope.picked_category;});
    }

    $scope.deleteCategory   = function (type) {
        var modalInstance   = $uibModal.open({
            animation   : true,
            templateUrl : 'deleteCateModal.html',
            controller  : 'deleteCateModalCtrl',
            windowClass : 'delete-cate-modal',
            size        : 'sm',
            resolve     : {
                somedata  : function () {
                    var data = {type : type};
                    if (type == 'group') {
                        data.name   = $scope.categories[$scope.picked_category_group].category_group;
                    } else if (type == 'category') {
                        data.name   = _.find($scope.categories[$scope.picked_category_group].category_items, function (val){
                            return val._id == $scope.picked_category;
                        }).category_name;
                    } else {

                    }

                    return data;
                }
            }
        });

        modalInstance.result.then(function (status) {
            if (status == 'OK') {
                if (type == 'group') {
                    fetcher.deleteCategoryGroup($scope.picked_category_group, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            var name   = $scope.categories[$scope.picked_category_group].category_group;

                            Notification.info("Successfully delete category group " + name + ".");
                            init();
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                } else if (type == 'category'){
                    fetcher.deleteCategory($scope.picked_category_group, $scope.picked_category, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            var name   = _.find($scope.categories[$scope.picked_category_group].category_items, function(val) {return val._id == $scope.picked_category;}).category_name;

                            Notification.info("Successfully delete category " + name + " from category group " + $scope.categories[$scope.picked_category_group].category_group + ".");
                            init($scope.picked_category_group, $scope.categories[$scope.picked_category_group].category_items[0]._id);
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                }
            }
        }, function () {

        });
    }

    $scope.addCategory   = function (type) {
        var modalInstance   = $uibModal.open({
            animation   : true,
            templateUrl : 'addCateModal.html',
            controller  : 'addCateModalCtrl',
            windowClass : 'add-cate-modal',
            size        : 'sm',
            resolve     : {
                somedata  : function () {
                    var data = {type : type};
                    if (type == 'group') {
                        data.name   = "";
                    } else if (type == 'category') {
                        data.name   = $scope.categories[$scope.picked_category_group].category_group;
                    } else {

                    }

                    return data;
                }
            }
        });

        modalInstance.result.then(function (some) {
            if (some.status == 'OK') {
                if (type == 'group') {
                    var data = {
                        category_group : some.new_name
                    };

                    fetcher.postCategoryGroup(data, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            Notification.info("Create category groups " + some.new_name + " success.");

                            init(response.result._id, "");
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                } else if (type == 'category'){
                    var data = {
                        category_name   : some.new_name
                    };

                    fetcher.postCategory($scope.picked_category_group, data, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            Notification.info("Create category " + some.new_name + " for category group " + $scope.categories[$scope.picked_category_group].category_group + " success.");
                            init("", response.result._id);
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                }
            }
        }, function () {

        });
    }

    $scope.editCategory   = function (type) {
        var modalInstance   = $uibModal.open({
            animation   : true,
            templateUrl : 'editCateModal.html',
            controller  : 'editCateModalCtrl',
            windowClass : 'edit-cate-modal',
            size        : 'sm',
            resolve     : {
                somedata  : function () {
                    var data = {type : type};
                    if (type == 'group') {
                        data.name   = $scope.categories[$scope.picked_category_group].category_group;
                    } else if (type == 'category') {
                        data.name   = _.find($scope.categories[$scope.picked_category_group].category_items, function(val) {return val._id == $scope.picked_category;}).category_name;
                    }

                    return data;
                }
            }
        });

        modalInstance.result.then(function (some) {
            if (some.status == 'OK') {
                if (type == 'group') {
                    var name    = $scope.categories[$scope.picked_category_group].category_group;

                    var data    = {
                        category_group : some.new_name
                    };

                    fetcher.putCategoryGroup($scope.picked_category_group, data, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            Notification.info("Category group " + name + " successfully changed to " + some.new_name + ".");
                            init("", "");
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                } else if (type == 'category'){
                    var name   = _.find($scope.categories[$scope.picked_category_group].category_items, function(val) {return val._id == $scope.picked_category;}).category_name;

                    var data    = {
                        category_name   : some.new_name
                    };

                    fetcher.putCategory($scope.picked_category_group, $scope.picked_category, data, function (response) {
                        if ((response.status_code == "200") && (response.response == "OK")) {
                            Notification.info("Category " + name + " successfully changed to " + some.new_name + ".");
                            init("", "");
                        } else {
                            Notification.error(messageHelper.massiveErrorMsg());
                        }
                    });
                }
            }
        }, function () {

        });
    }

    var init    = function (category_group_id, category_id) {
        fetcher.getCategories(function (response) {
            if ((response.status_code == "200") && (response.response == "OK")) {
                var grouped_result  = response.result;
                _.each(grouped_result, function(val, key) { val.category_items = _.sortBy(val.category_items, 'category_name'); });
                $scope.categories   = grouped_result;

                if (typeof (category_group_id) == 'undefined' && typeof (category_id) == 'undefined') {
                    var temp_category               = response.result[Object.keys(response.result)[0]].category_items[0];
                    $scope.picked_category_group    = Object.keys(response.result)[0];
                    $scope.picked_category          = temp_category._id;
                } else {
                    if (category_id) { $scope.picked_category = category_id; }
                    if (category_group_id) { $scope.picked_category_group = category_group_id; }
                }
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });
    };

    init();
}]);

app.controller('deleteCateModalCtrl', ['$scope', '$uibModalInstance', 'somedata', function ($scope, $uibModalInstance, somedata) {
    $scope.type = somedata.type;
    $scope.name = somedata.name;

    $scope.ok = function () { $uibModalInstance.close("OK"); };
    $scope.cancel = function () { $uibModalInstance.dismiss('cancel'); };
}]);

app.controller('addCateModalCtrl', ['$scope', '$uibModalInstance', 'somedata', 'Notification', function ($scope, $uibModalInstance, somedata, Notification) {
    $scope.type = somedata.type;
    $scope.name = somedata.name;

    $scope.ok = function () {
        if ($scope.new_category) {
            var data = {
                status      : "OK",
                new_name    : $scope.new_category,
            }
            $uibModalInstance.close(data);
        } else {
            Notification.error("Please input a name first.");
        }
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}]);

app.controller('editCateModalCtrl', ['$scope', '$uibModalInstance', 'somedata', 'Notification', function ($scope, $uibModalInstance, somedata, Notification) {
    $scope.type     = somedata.type;
    $scope.name     = somedata.name;
    $scope.new_name = somedata.name;

    $scope.ok = function () {
        if ($scope.name !== $scope.new_name) {
            var data = {
                status      : "OK",
                new_name    : $scope.new_name,
            }
            $uibModalInstance.close(data);
        } else {
            Notification.error("New Name must be different from the Old Name.");
        }

    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

}]);
