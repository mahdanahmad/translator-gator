<div class="col-md-8 col-xs-10 col-md-offset-2 col-xs-offset-1 forgot-container">
    <div class="forgot_head">{{head}}</div>
    
    <form ng-submit="send_email()" ng-hide="success">
        <input ng-model="email" type="email" name="email" class="form-control" id="email" placeholder="Email" autocomplete="off" autocapitalize="off" required autofocus>
        
        <div ng-show="error" class="alert auth-error animated fadeIn" role="alert">
            <p>{{errmessage}}</p>
        </div>
        
        <div class="form-button">
            <button class="btn btn-forgot-cancel" type="button" ng-click="cancel()"><span>CANCEL</span></button>
            <button class="btn btn-forgot-resend" type="submit"><span>CONTINUE</span></button>
        </div>
    </form>
    
    <div ng-show="success">
        <div class="success_message" ng-bind-html="message"></div>
        <button class="btn btn-forgot-back" type="button" ng-click="cancel()"><span>GO BACK</span></button>
    </div>
    
</div>