<div class="col-md-8 col-xs-10 col-md-offset-2 col-xs-offset-1 reset-container">
    
    <div class="reset_head">{{head}}</div>
    
    <form ng-submit="reset_password()" ng-show="show">
        <div class="form-auth">
            <input ng-model="password" type="password" name="password" class="form-control input-reset" id="email" placeholder="New Password" autocomplete="off" autocapitalize="off" required autofocus>
            <div class="form-divider-reset"></div>
            <input ng-model="confirm_password" type="password" name="password" class="form-control input-reset" id="email" placeholder="Confirm Password" autocomplete="off" autocapitalize="off" required>
        </div>
        
        <div ng-show="error" class="alert auth-error animated fadeIn" role="alert">
            <p>{{message}}</p>
        </div>
        
        <div class="form-button">
            <button class="btn btn-reset-change" type="submit"><span>CHANGE</span></button>
            
            <div class="clearfix"></div>
        </div>
    </form>
</div>