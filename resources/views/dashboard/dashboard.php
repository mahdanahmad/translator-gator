<div id="page-dash">
    <div ng-hide="hideHeader" id="dash-header">
        <div id="battery-header">
            <div id="image-container" source-height>
                <img src="resources/img/icons/battery.png">
            </div>
            <div id="color-container" ng-class="batteryColor(health / max_health * 100)" target-height>
                <div ng-show="(health / max_health) * 100 >= batteryBar.red"
                     class="battery-color"></div>
                <div ng-show="(health / max_health) * 100 >= batteryBar.yellow"
                     class="battery-color"></div>
                <div ng-show="(health / max_health) * 100 >= batteryBar.green"
                     class="battery-color"></div>
            </div>
        </div>

        <div id="points-header" class="noselect">
            <span id="ceil">{{points}}</span>
            <span id="floor">points</span>
        </div>

        <div class="clearfix"></div>
    </div>

    <div ui-view></div>

    <div class="clearfix"></div>

    <div ng-hide="hideNavbar" id="dash-navigation">
        <div ng-hide="needClose" id="default-nav">
            <div ng-mouseenter="hint_select = true"
                 ng-mouseleave="hint_select = false"
                 ng-click="openHint()"
                 id="hint-nav"
                 class="pulse-nav col-md-4 col-xs-4"
                 title="hint">
                <img ng-hide="hint_select" src="" img-src="hint">
                <img ng-show="hint_select" src="" img-src="hint">
            </div>
            <div ng-mouseenter="profile_select = true"
                 ng-mouseleave="profile_select = false"
                 ng-click="openProfile()"
                 class="pulse-nav col-md-4 col-xs-4"
                 id="profile-nav"
                 title="profile">
                <img ng-hide="profile_select" src="" img-src="profile">
                <img ng-show="profile_select" src="" img-src="profile">
            </div>
            <div ng-mouseenter="logout_select = true"
                 ng-mouseleave="logout_select = false"
                 ng-click="logout()"
                 class="pulse-nav col-md-4 col-xs-4"
                 id="logout-nav"
                 title="logout">
                <img ng-hide="logout_select" src="" img-src="logout">
                <img ng-show="logout_select" src="" img-src="logout">
            </div>
            <div class="clearfix"></div>
        </div>

        <div ng-show="needClose" id="exit-nav">
            <div ng-mouseenter="exit_select = true"
                 ng-mouseleave="exit_select = false"
                 ng-click="backToGame()"
                 id="exit-navv"
                 class="pulse-nav col-md-4 col-xs-4 col-md-offset-4 col-xs-offset-4"
                 title="exit">
                <img ng-hide="exit_select" src="" img-src="exit">
                <img ng-show="exit_select" src="" img-src="exit">
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
