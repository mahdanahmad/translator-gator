<div id="admin-wrapper">
    <div class="page-header navbar">
       <div class="page-header-inner container">
           <div class="page-logo">
               <img src="resources/img/plj_logo_reversed.png" id="plj-logo">
               <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="dropdown dropdown-user">
                            <a ng-click="logout()" class="dropdown-toggle">
                            <i class="icon-power"></i>
                            <span class="username username-hide-on-mobile">
                            Log Out </span>
                            </a>
                        </li>
                    </ul>
                </div>
           </div>
       </div>
    </div>
    <div class="container">
        <div class="page-container">
            <div class="page-content-wrapper">
<!--                <div class="page-content ">-->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><i class="icon-chart round-icon"></i> Statistic</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="col-md-6">
                                        <canvas id="line"
                                                class="chart chart-line"
                                                chart-data="lineData"
                                                chart-labels="lineLabels"
                                                chart-legend="true"
                                                chart-series="lineSeries"
                                                chart-colours="lineColours"
                                                chart-click="lineOnClick" >
                                        </canvas>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="radar"
                                                class="chart chart-radar"
                                                chart-data="radarData"
                                                chart-labels="radarLabels"
                                                chart-legend="true"
                                                chart-series="radarSeries"
                                                chart-colours="radarColours"
                                                chart-click="radarOnClick" >
                                        </canvas>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><i class="icon-cloud-upload round-icon"></i> Upload</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div>Upload list of words to be translated</div>
                                    <div class="file-uploader input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-info btn-file" button-uploader>
                                                Browse <input type="file">
                                            </span>
                                        </span>
                                        <input type="text" class="form-control" readonly="" ng-model="uploaded_file.name">
                                    </div>
                                    <button class="btn btn-info btn-submit"><i class="icon-check"></i> <span>Upload</span></button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><i class="icon-cloud-download round-icon"></i> Download</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div>Download translated word from</div>
                                    <div class="file-uploader input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-info disabled btn-fake-lang">
                                                Language
                                            </span>
                                        </span>
                                        <select class="selectpicker" ng-model="downloaded_language">
                                            <option ng-repeat="lang in language_list">{{lang.language_name}}</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-info btn-submit" ng-click="download()"><i class="icon-check"></i> <span>Download</span></button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><i class="icon-badge round-icon"></i> Point Configuration</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <form ng-submit="cfg_save()">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Translate</span>
                                            <input type="number" min="0" class="form-control" ng-model="config.translate_point">
                                            <span class="input-group-addon input-unit">point(s)</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Alternative</span>
                                            <input type="number" min="0" class="form-control" ng-model="config.alternate_point">
                                            <span class="input-group-addon input-unit">point(s)</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Vote</span>
                                            <input type="number" min="0" class="form-control" ng-model="config.vote_up_point">
                                            <span class="input-group-addon input-unit">point(s)</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Down voted</span>
                                            <input type="number" min="0" class="form-control" ng-model="config.vote_down_point">
                                            <span class="input-group-addon input-unit">point(s)</span>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <span class="caption-subject bold uppercase"><i class="icon-grid round-icon"></i> Page Configuration</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <form ng-submit="cfg_save()">
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Items</span>
                                            <input type="number" min="1" class="form-control" ng-model="config.display_items_per_page">
                                            <span class="input-group-addon input-unit">per page</span>
                                        </div>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon input-title">Option</span>
                                            <input type="number" min="1" class="form-control" ng-model="config.display_options_per_page">
                                            <span class="input-group-addon input-unit">per page</span>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                </div>-->
            </div>
        </div>
    </div>
</div>
