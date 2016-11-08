<div class="col-md-8 col-xs-10 col-md-offset-2 col-xs-offset-1 login-container">
    <div id="login-box login-panel panel panel-default" class="animated fadeIn">

        <div class="header-wrapper">
            <div class="header_logo"><img src="resources/img/transgator-logo.png"></div>
        </div>
        <div>
            <form ng-submit="login()">
                <div class="form-auth">
                    <input ng-model="username" type="text" name="username" class="form-control input-reset" id="username" placeholder="Username" autocomplete="off" autocapitalize="off" required autofocus>
                    <div class="form-divider-reset"></div>
                    <input ng-model="password" type="password" name="password" class="form-control input-reset" id="password" placeholder="Password" required>
                </div>

                <div ng-show="error" class="alert auth-error animated fadeIn" role="alert">
                    <p>{{message}}</p>
                </div>

                <div class="form-submit">
                    <button ng-class="{withError : error}" class="btn btn-primary btn-lg btn-crdsrc-auth" type="submit">LOGIN</button>
                </div>
            </form>
        </div>
        <div id="register-redirect">
            <span ng-click="redirect_register()">REGISTER</span>
        </div>
        <div id="forgot-redirect">
            <span ng-click="forgot_register()">FORGOT PASSWORD</span>
        </div>

    </div><!-- #login-box -->

</div><!-- .container -->
<div class="clearfix"></div>
<img src="resources/img/plj_logo_reversed.png" id="plj_logo" ng-click="openURL()">
