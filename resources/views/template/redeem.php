<div id="redeem-page" class="noselect">
    <div id="redeem-title"><h3><strong>Redeem</strong></h3></div>
    <form id="redeem-form" ng-submit="redeemSubmit()">
        <!-- <input ng-model="redeem.mobile" type="text" class="form-control input-reset" id="redeem-mobile" placeholder="Mobile number" autocomplete="off" autocapitalize="off" required autofocus> -->
        <div id="redeem-mobile-group" class="form-group input-group col-md-6">
            <span class="input-group-addon input-title">+62</span>
            <input type="number" min="0" class="form-control" ng-model="redeem.mobile" placeholder="Mobile number" autocomplete="off" autocapitalize="off" required autofocus>
        </div>
        <!-- <div class="form-divider-reset"></div> -->
        <div id="redeem-set" class="form-control input-reset">
            <span id="redeem-point">{{ $parent.points }} points</span>
            <span id="redeem-dash">&ndash;</span>
            <select id="redeem-dropdown" ng-model="redeem.credits" required>
                <option class="header" value="" disabled selected>--</option>
                <option value="{{credit}}"
                        class="redeem-credits"
                        ng-repeat="credit in redeemCredits"
                        ng-hide="checkCredit({{credit}})">
                            {{credit | currency:"IDR "}}
                </option>
            </select>
            <button id="redeem-submit" ng-disabled="redeemDisable">REDEEM</button>
        </div>
    </form>
</div>
