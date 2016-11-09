<div id="words-view">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-cloud-upload round-icon"></i> Upload</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div>Upload list of words to be translated</div>
                    <div class="file-uploader input-group">
                        <span class="input-group-btn">
                            <span class="btn btn-info btn-pulselab btn-file" button-uploader>
                                Browse <input type="file" accept=".csv" ng-disabled="upload_disabled">
                            </span>
                        </span>
                        <input type="text" class="form-control" readonly="" ng-model="uploaded_file.name" onclick="console.log('triggered')">
                    </div>
                    <button class="btn btn-info btn-pulselab btn-submit" ng-click="upload()" ng-disabled="upload_disabled"><i class="icon-check"></i> <span>Upload</span></button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-cloud-download round-icon"></i> Download</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="col-md-12" id="download_language">
                        <div>Select languange to include in download result</div>
                        <div id="language-container">
                            <label class="col-md-2" ng-repeat="lang in language_list">
                                <input
                                    type="checkbox"
                                    value="{{lang._id}}"
                                    ng-checked="downloaded_language.indexOf(lang._id) > -1"
                                    ng-click="toggleSelection('language', lang._id)">
                                <span class="capitalize">{{lang.language_name}}</span>
                            </label>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-12" id="download_category">
                        <div>Select category to include in download result</div>
                        <div class="file-downloader input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-pulselab btn-fake-lang">
                                    Category Group
                                </span>
                            </span>
                            <select class="selectpicker" ng-model="selected_group">
                                <option ng-repeat="(key, value) in category_list" value="{{key}}">{{value.category_group}}</option>
                            </select>
                        </div>

                        <div class="col-md-12" id="download_categories">
                            <label class="col-md-4" ng-repeat="cata in category_list[selected_group].data">
                            <input
                                type="checkbox"
                                value="{{cata._id}}"
                                ng-checked="downloaded_category.indexOf(cata._id) > -1"
                                ng-click="toggleSelection('category', cata._id)">
                            <span class="capitalize">{{cata.category_name}}</span>
                        </label>
                        </div>
                    </div>
                    <div id="selectall-container" class="col-md-4 col-xs-12 noselect">
                        <label class="col-md-6 col-xs-12">
                            <input type="checkbox"
                                   ng-model="selectall.language"
                                   ng-click="tonggleAll('language')">
                            All language
                        </label>
                        <label class="col-md-6 col-xs-12">
                            <input type="checkbox"
                                   ng-model="selectall.category"
                                   ng-click="tonggleAll('category')">
                            All category
                        </label>
                    </div>
                    <button class="btn btn-info btn-pulselab btn-submit" ng-click="download()"><i class="icon-check"></i> <span>Download</span></button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
