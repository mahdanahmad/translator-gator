<div class="col-md-10 col-xs-10 col-md-offset-1 col-xs-offset-1 unconfirmed-container">
    
    <div class="unconfirmed_head">{{head}}</div>
    
    <ul class="unconfirmed_message" ng-bind-html="message"></ul>
    
    <div ng-hide="true" class="form-button">
        <button class="btn btn-unconfirmed-cancel" type="button" ng-click="cancel()"><span>Go Back</span></button>
        <button class="btn btn-unconfirmed-resend" type="button" ng-click="resend()"><span>Resend Confirmation</span></button>
    </div>
    
    <button class="btn btn-unconfirmed-back" type="button" ng-click="cancel()"><span>GO BACK</span></button>
</div>