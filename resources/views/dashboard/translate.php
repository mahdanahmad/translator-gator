<div id="translate-view">
    <div class="clearfix"></div>
    <div class="translation-container col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1">
        <div class="translate-languange bold" ng-bind-html="head"></div>
        <form class="words-container" ng-submit="input_translation()">
            <div class="word-to-be-translated" ng-repeat="value in translate_list">
                <label class="col-md-12 col-xs-12 capitalize bold">{{value.origin_word}}</label>
                <div class="textarea-wrapper" textwrapper>
                    <div class="dummy"></div>
                    <textarea class="col-xs-12 col-md-12 form_input_word" ng-model="value.translation" ng-enter="input_translation()" placeholder="{{placeholder}}" autofocus></textarea>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-button">
                <button class="btn btn-submit-randomize" type="button" ng-click="skip()"><span>SKIP</span></button>
                <button class="btn btn-submit-translate" type="submit" ng-disabled="disabled"><span>SUBMIT</span></button>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
