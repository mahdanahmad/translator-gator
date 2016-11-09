<script type="text/ng-template" id="deleteLangModal.html">
    <div class="modal-body">
        Are you sure you wanna delete language <span class="capitalize bold">{{lang.language_name}}</span>?
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary pull-left" type="button" ng-click="ok()">OK</button>
        <button class="btn btn-danger pull-right" type="button" ng-click="cancel()">Cancel</button>
    </div>
</script>
<div id="language-view">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
                <div class="add-language">
                    <div class="form-group input-group col-md-12">
                        <span class="input-group-addon input-title"><i class="icon-plus"></i> Add new language</span> 
                        <input type="text" class="form-control capitalize" ng-model="new_language" ng-enter="addLanguage()">
                        <span class="input-group-addon input-unit" ng-click="addLanguage()">
                            <i title="Add new language" class="fa fa-plus language_add"></i>
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="">
                    <div class="language-list-title"><strong>Language available :</strong></div>
                    <div class="clearfix"></div>
                    <div class="language-list-container noselect">
                        <div ng-repeat="lang in language_list" class="col-md-4">
                            <div class="form-group input-group" ng-init="lang.editedLanguage_name = lang.language_name">
                                <span class="input-group-addon input-title"><i class="icon-speech"></i></span> 
                                <input type="text" class="form-control capitalize" ng-model="lang.editedLanguage_name" ng-enter="changeLanguage(lang)">
                                <span class="input-group-addon input-unit">
                                    <i title="Edit" class="fa fa-pencil-square-o language_edit" ng-click="changeLanguage(lang)"></i>
                                    <i title="Delete" class="fa fa-times language_delete" ng-click="deleteLanguage(lang)"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>