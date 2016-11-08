<div id="general-view">
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-grid round-icon"></i> Page Configuration</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form ng-submit="cfg_save('page')">
                        <div class="form-container nopadding col-md-12">
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Items</span>
                                <input type="number" min="1" class="form-control" ng-model="config.display_items_per_page">
                                <span class="input-group-addon input-unit">per page</span>
                            </div>
                        </div>
                        <div ng-hide="true"class="form-group input-group col-md-6">
                            <span class="input-group-addon input-title">Option</span>
                            <input type="number" min="1" class="form-control" ng-model="config.display_options_per_page">
                            <span class="input-group-addon input-unit">per page</span>
                        </div>
                        <button type="submit" class="btn btn-info btn-pulselab btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-badge round-icon"></i> Point Configuration</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form ng-submit="cfg_save('point')">
                        <div class="form-container nopadding col-md-12">
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Translate</span>
                                <input type="number" min="0" class="form-control" ng-model="config.translate_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Alternate</span>
                                <input type="number" min="0" class="form-control" ng-model="config.alternate_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Vote</span>
                                <input type="number" min="0" class="form-control" ng-model="config.voter_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Categorize</span>
                                <input type="number" min="0" class="form-control" ng-model="config.categorize_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Upvoted</span>
                                <input type="number" min="0" class="form-control" ng-model="config.vote_up_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Downvoted</span>
                                <input type="number" min="0" class="form-control" ng-model="config.vote_down_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Referral</span>
                                <input type="number" min="0" class="form-control" ng-model="config.referral_point">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-pulselab btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-energy round-icon"></i> Battery Configuration</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form ng-submit="cfg_save('battery')">
                        <div class="form-container nopadding col-md-12">
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Max Health</span>
                                <input type="number" min="0" class="form-control" ng-model="config.max_health">
                                <span class="input-group-addon input-unit">point(s)</span>
                            </div>
                            <div class="form-group input-group col-md-6">
                                <span class="input-group-addon input-title">Kick Out Period</span>
                                <input type="number" min="0" class="form-control" ng-model="config.kick_time">
                                <span class="input-group-addon input-unit">minute(s)</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info btn-pulselab btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-directions round-icon"></i> Action Configuration</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form ng-submit="cfg_save('action')">
                        <div class="row">
                            <toggle-switch
                                ng-model="config.is_on_translate"
                                class="switch-pulselab switch-large"
                                knob-label="Translate">
                            </toggle-switch>
                            <toggle-switch
                                ng-model="config.is_on_alternative"
                                class="switch-pulselab switch-large"
                                knob-label="Alternative">
                            </toggle-switch>
                        </div>
                        <div class="row">
                            <toggle-switch
                                ng-model="config.is_on_vote"
                                class="switch-pulselab switch-large"
                                knob-label="Vote">
                            </toggle-switch>
                            <toggle-switch
                                ng-model="config.is_on_categorize"
                                class="switch-pulselab switch-large"
                                knob-label="Categorize">
                            </toggle-switch>
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-info btn-pulselab btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title ">
                    <div class="caption">
                        <span class="caption-subject bold uppercase"><i class="icon-cup round-icon"></i> Redeem Configuration</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <form ng-submit="cfg_save('redeem')">
                        <toggle-switch
                            ng-model="config.redeem_time"
                            class="switch-pulselab switch-large"
                            knob-label="Redeem Time">
                        </toggle-switch>
                        <div class="form-group input-group col-md-6">
                            <span class="input-group-addon input-title">Point Value</span>
                            <input type="number" min="0" class="form-control" ng-model="config.point_value">
                            <span class="input-group-addon input-unit">Rupiah(s) / point</span>
                        </div>
                        <div class="clearfix"></div>
                        <button type="submit" class="btn btn-info btn-pulselab btn-cfg-submit"><i class="icon-check"></i> <span>Save</span></button>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
