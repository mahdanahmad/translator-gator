<div id="vote-view">
    <div class="clearfix"></div>
    <div class="vote-container col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
        <div class="vote-languange bold" ng-bind-html="head"></div>
        <form class="words-container" ng-submit="input_vote()">
            <div class="vote-word" ng-repeat="value in vote_list">
                <div class="vote-origin capitalize bold">{{ value.origin_word }}</div>
                <div class="vote-translated capitalize">{{ value.translated_to }}</div>
                <div class="clearfix"></div>
                <div class="vote-buttons">
                    <div class="noselect up-button" ng-click="upvote(value)" ng-class="{'upvote-button' : value.stat == 'up'}">
                        <div class="arrow agree">
                            <img src="resources/img/icons/arrow_up.png">
                        </div>
                        <div class="title">AGREE</div>
                    </div>
                    <div class="noselect down-button" ng-click="downvote(value)" ng-class="{'downvote-button' : value.stat == 'down'}">
                        <div class="arrow disagree">
                            <img src="resources/img/icons/arrow_down.png">
                        </div>
                        <div class="title">DISAGREE</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="form-button" ng-hide="vote_list.length == 1">
                <button class="btn btn-submit-randomize" type="button" ng-click="skip()"><span>SKIP</span></button>
                <button class="btn btn-submit-translate" type="submit" ng-disabled="disabled"><span>SUBMIT</span></button>
            </div>
            <div class="form-button one-vote" ng-show="vote_list.length == 1">
                <button class="btn btn-submit-randomize" type="button" ng-click="skip()"><span>SKIP</span></button>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
