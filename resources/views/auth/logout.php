<div id="logout-container" class="noselect">
    <div id="leaderboard-container"
         ng-include="'views/leaderboard'">
    </div>

    <div id="auth-navigation">
        <div id="exit-nav">
            <div ng-mouseenter="exit_select = true"
                 ng-mouseleave="exit_select = false"
                 ng-click="backToLogin()"
                 id="exit-navv"
                 class="pulse-nav col-md-4 col-xs-4 col-md-offset-4 col-xs-offset-4"
                 title="exit">
                <img ng-hide="exit_select" src="resources/img/icons/exit.png">
                <img ng-show="exit_select" src="resources/img/icons/exit_select.png">
            </div>
        </div>
    </div>
</div>
