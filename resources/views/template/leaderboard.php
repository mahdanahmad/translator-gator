<div class="split-container leader">
    <div class="left-container">
        <div class="ceil">
            Your Points
        </div>
        <div class="floor noselect">
            <span id="ceil">{{$parent.points}}</span>
            <span id="floor">points</span>
            <div id="socmed-header" ng-hide="hideShare">
                Share and invite your friend for extra point!
            </div>
            <div id="socmed-container" ng-hide="hideShare">
                <div facebook-feed-share
                     ng-mouseenter="fb_select = true"
                     ng-mouseleave="fb_select = false"
                     ng-click="share('facebook')"
                     id="fb-share"
                     class="pulse-share fb-share-button"
                     title="Share on Facebook">
                    <img ng-hide="fb_select" src="resources/img/icons/facebook.png">
                    <img ng-show="fb_select" src="resources/img/icons/facebook_select.png">
                </div>
                <a ng-href="https://twitter.com/intent/tweet?text={{twitter.text}}&hashtags={{twitter.hashtags}}&url={{twitter.url}}">
                    <div ng-mouseenter="tw_select = true"
                         ng-mouseleave="tw_select = false"
                         ng-click="share('twitter')"
                         id="tw-share"
                         class="pulse-share"
                         title="Share on Twitter">
                        <img ng-hide="tw_select" src="resources/img/icons/twitter.png">
                        <img ng-show="tw_select" src="resources/img/icons/twitter_select.png">
                    </div>
                </a>
                <div ng-hide="true"
                     ng-mouseenter="path_select = true"
                     ng-mouseleave="path_select = false"
                     ng-click="share('path')"
                     id="path-share"
                     class="pulse-share"
                     title="Share on Path">
                    <img ng-hide="path_select" src="resources/img/icons/path.png">
                    <img ng-show="path_select" src="resources/img/icons/path_select.png">
                </div>
                <div class="clearfix"></div>
            </div>
            <div id="socmed-email" ng-hide="hideShare">
                For questions, please email: <a href="mailto:plj-tg@un.or.id?Subject=Translator Gator Question" target="_top">plj-tg@un.or.id</a>
            </div>
        </div>
    </div>
    <div class="right-container">
        <div class="ceil">
            Points
        </div>
        <div class="floor">
            <div ng-repeat="leader in leaderboard" class="row">
                <label class="col-xs-1 col-md-1">{{$index + 1}}.</label>
                <label class="col-xs-5 col-md-7">{{leader.username}}</label>
                <label class="col-xs-3 col-md-3 bold">{{leader.point}}</label>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="redeem-button" ng-show="showRedeem" ng-click="goToRedeem()">
            REDEEM
        </div>
    </div>
    <div class="clearfix"></div>
</div>
