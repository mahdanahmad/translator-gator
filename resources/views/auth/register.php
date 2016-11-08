<div id="register-container" class="col-md-12 col-xs-12 col-md-offset-0 col-xs-offset-0 animated fadeIn">
    <div class="header-wrapper row">
        <div class="header_logo"><img src="resources/img/transgator-landscape-logo.png"></div>
    </div>

    <div class="form-register-container">
        <form ng-submit="register()">
            <div class="form-auth">
                <input ng-model="email" type="email" name="email" class="form-control input-auth" id="email" placeholder="Email *" autocomplete="off" autocapitalize="off" required autofocus>
                <input ng-model="username" type="text" name="username" class="form-control input-auth" id="username" placeholder="Username *" autocomplete="off" autocapitalize="off" required>
                <div class="form-divider"></div>
                <input ng-model="password" type="password" name="password" class="form-control input-auth" id="password" placeholder="Password *" required>
                <input ng-model="confirm_password" type="password" name="password" class="form-control input-auth" id="password" placeholder="Confirm Password *" required>
                <div class="form-divider"></div>
                <select class="select-crdsrc-auth"
                        ng-model="gender"
                        ng-class=" gender == '' ? 'placeholder' : ''">
                    <option class="header" value="" disabled selected>Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <select class="select-crdsrc-auth"
                        ng-model="age_range"
                        ng-class=" age_range == '' ? 'placeholder' : ''">
                    <option class="header" value="" disabled selected>Age Range</option>
                    <option value="< 15">&#60; 15</option>
                    <option value="15 - 19">15 - 19</option>
                    <option value="20 - 24">20 - 24</option>
                    <option value="25 - 29">25 - 29</option>
                    <option value="30 - 34">30 - 34</option>
                    <option value="35 - 39">35 - 39</option>
                    <option value="> 40">&#62; 40</option>
                </select>
                <div class="clearfix"></div>
            </div>
            <div id="form-detail">
                <i>* = required information</i>
            </div>
            <div class="languange-chooser">
                <strong>I can speak Bahasa :</strong>
                <div id="language_picker">
                    <label class="col-md-4 col-xs-6 nopadding" ng-repeat="lang in language_list">
                        <input
                            type="checkbox"
                            value="{{lang._id}}"
                            ng-checked="selection.indexOf(lang._id) > -1"
                            ng-click="toggleSelection(lang._id)">
                        <span>{{lang.language_name}}</span>
                    </label>
                </div>
            </div>

            <div class="clearfix"></div>
            <div ng-show="error" class="alert auth-error animated fadeIn" role="alert"> <p>{{error_message}}</p> </div>
            <div class="form-submit">
                <button ng-class="{withError : error}" class="btn btn-primary btn-lg btn-crdsrc-auth" type="submit">REGISTER</button>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
