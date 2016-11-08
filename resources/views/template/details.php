<div class="split-container ceil">
    <div class="left-container">
        <div>username</div>
    </div>
    <div class="right-container">
        <div>{{ profileDetails.user.username }}</div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="split-container ceil">
    <div class="left-container">
        <div>email</div>
    </div>
    <div class="right-container">
        <div>{{ profileDetails.user.email }}</div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="split-container language">
    <div class="left-container">
        <div>I can speak : </div>
    </div>
    <div class="right-container">
        <ul class="capitalize" id="details_language" style="list-style-type:disc" ng-hide="isEdited">
            <li ng-repeat="key in profileDetails.user.languages" class="col-xs-12 col-md-12">
                {{language_list[key]}}
            </li>
        </ul>
        <div id="language_picker" class="capitalize" ng-show="isEdited">
            <label class="col-xs-12 col-md-12" ng-repeat="(key, value) in language_list">
                <input
                    type="checkbox"
                    value="{{key}}"
                    ng-checked="profileDetails.user.languages.indexOf(key) > -1"
                    ng-click="toggleSelection(key)">
                {{value}}
            </label>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<button ng-hide="isEdited"
        type="button"
        class="btn btn-primary btn-lg editbutton"
        ng-click="edited()">
    EDIT
</button>
<button ng-show="isEdited"
        type="button"
        class="btn btn-primary btn-lg editbutton"
        ng-click="editProfile()">
    SAVE
</button>
