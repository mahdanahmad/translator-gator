<script type="text/ng-template" id="deleteCateModal.html">
    <div class="modal-body">
        Are you sure you wanna delete category <span ng-show="type == 'group'">group </span> <span class="capitalize bold">{{name}}</span>?
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary pull-left" type="button" ng-click="ok()">OK</button>
        <button class="btn btn-danger pull-right" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script>
<script type="text/ng-template" id="addCateModal.html">
    <form ng-submit="ok()">
        <div class="modal-header">
            Add new category
            <span ng-show="type == 'group'">group</span>
            <span ng-show="type == 'category'">on <span class="capitalize">{{name}}</span></span>
        </div>
        <div class="modal-body">
            <div class="form-group input-group">
                <span class="input-group-addon input-title">Name</span>
                <input type="text" class="form-control capitalize" ng-model="new_category">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-pulselab pull-right" type="submit">Submit</button>
        </div>
    </form>
</script>
<script type="text/ng-template" id="editCateModal.html">
    <form ng-submit="ok()">
        <div class="modal-header">
            Edit category <span ng-show="type == 'group'">group</span> <span class="capitalize">{{name}}<span>
        </div>
        <div class="modal-body">
            <div class="form-group input-group">
                <span class="input-group-addon input-title">New Name</span>
                <input type="text" class="form-control capitalize" ng-model="new_name">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-pulselab pull-right" type="submit">Submit</button>
        </div>
    </form>
</script>
<div id="category-view">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="category-container">
                    <div class="group-selector col-md-12">
                        <div class="form-group input-group">
                            <span class="input-group-addon input-title noselect"> Category groups </span>
                            <select class="group-picker" ng-change="category_group_changed()" ng-model="picked_category_group">
                                <option ng-repeat="(key, value) in categories" value="{{key}}">{{value.category_group}}</option>
                            </select>
                            <span title="Edit group category name" class="input-group-addon input-unit noselect" ng-click="editCategory('group')"> <i class="fa fa-pencil-square-o"></i></span>
                            <span title="Add new group category" class="input-group-addon input-unit noselect" ng-click="addCategory('group')"> <i class="fa fa-plus"></i> </span>
                            <span title="Delete" class="input-group-addon input-unit noselect" ng-click="deleteCategory('group')"> <i class="fa fa-times"></i> </span>
                        </div>
                    </div>
                    <div class="category-selector col-md-12">
                        <div class="form-group input-group">
                            <span class="input-group-addon input-title noselect"> Categories </span>
                            <select class="categories-picker" ng-change="category_changed()" ng-model="picked_category">
                                <option ng-repeat="value in categories[picked_category_group].category_items" value="{{value._id}}" ng-hide="value.category_name == 'other'">{{value.category_name == 'other' ? '' : value.category_name}}</option>
                            </select>
                            <span title="Edit category name" class="input-group-addon input-unit noselect" ng-click="editCategory('category')"> <i class="fa fa-pencil-square-o"></i> </span>
                            <span title="Add new category" class="input-group-addon input-unit noselect" ng-click="addCategory('category')"> <i class="fa fa-plus"></i> </span>
                            <span title="Delete" class="input-group-addon input-unit noselect" ng-click="deleteCategory('category')"> <i class="fa fa-times"></i> </span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix">
</div>
