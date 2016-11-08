<div id="alternative-view">
    <div class="clearfix"></div>
    <div class="alternative-container col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
        <div class="alternative-languange bold" ng-bind-html="head"></strong></div>
        <form class="words-container" ng-submit="input_alternative()">
            <div class="word-to-be-translated" ng-repeat="word in alternative_list">
                <div>
                    <div class="alternate-origin">{{word.origin_word}}</div>
                    <div class="alternate-arrow"><i class="icon-arrow-right"></i></div>
                    <div class="alternate-word">{{word.translated_to}}</div>
                    <div class="clearfix"></div>
                </div>
                <div class="textarea-wrapper" textwrapper>
                    <div class="dummy"></div>
                    <textarea class="form_input_word" ng-model="word.alternative" ng-enter="input_alternative()" placeholder="{{placeholder}}" autofocus></textarea>
                    <div class="dummy"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-button">
                <button class="btn btn-submit-randomize" type="button" ng-click="skip()"><span>SKIP</span></button>
                <button class="btn btn-submit-translate" type="submit" ng-disabled="disabled"><span>SUBMIT</span></button>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
