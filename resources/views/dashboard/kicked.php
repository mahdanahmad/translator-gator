<div id="content-kicked">
    <div id="floor">
        <img id="stickman-img" src="resources/img/stickman.png">
        <div id="kicked-message" ng-bind-html="message"></div>
    </div>
    <div id="ceil">
        <div id="timer">Timer</div>
        <div id="countdown"> {{reconfigure(hour)}}:{{reconfigure(minute + 1)}} </div>
        <div id="detail">
            <span class="col-md-6 col-xs-6 col-sm-6">HOURS</span>
            <span class="col-md-6 col-xs-6 col-sm-6">MINUTE</span>
        </div>
    </div>
</div>
