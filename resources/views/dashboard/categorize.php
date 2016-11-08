<div id="categorize-view">
    <div class="clearfix"></div>
    <div class="categorize-container col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
        <div class="categorize-languange bold" ng-bind-html="head"></div>
        <form class="words-container" ng-submit="input_categorize()">
            <div id="untranslated-word" class="capitalize bold">
                {{ uncategorize_word.translated_to }}
            </div>

            <div id="categories-container">
                <div class="category-container noselect" ng-repeat="category in category_list.category_items">
                    <div class="category-tag capitalize"
                         ng-class="{selected : selected_category.indexOf(category._id) > -1}"
                         ng-click="categorize(category._id)"
                         ng-style="category.category_name == 'other' && {'background-color': 'gainsboro'}">
                        <span>{{category.category_name}}</span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="form-button">
                <button class="btn btn-submit-randomize" type="button" ng-click="skip()"><span>SKIP</span></button>
                <button class="btn btn-submit-translate" type="submit" ng-disabled="disabled"><span>SUBMIT</span></button>
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
