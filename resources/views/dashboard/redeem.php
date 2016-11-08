<div id="content-redeem">
    <div ng-show="index == slides.indexOf('hint')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="fifth-container"
         ng-include="'views/fifthhint'">
    </div>
    <div ng-show="index == slides.indexOf('redeem')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="redeemslide-container"
         ng-include="'views/redeemslide'">
    </div>
    <div ng-show="index == slides.indexOf('history')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="historyslide-container"
         ng-include="'views/historyslide'">
    </div>

    <div id="slides-nav">
        <div class="circle-container" ng-repeat="slide in slides">
            <div ng-class="{'active' : index == $index}" class="circle" ng-click="goTo($index)"></div>
        </div>
    </div>

    <div id="arrow-nav" class="noselect">
        <img id="arrow-left"
             ng-hide="index == 0"
             ng-click="prevSlide()"
             src="resources/img/icons/arrow-left.png">
        <img id="arrow-right"
             ng-hide="index == (slides.length - 1)"
             ng-click="nextSlide()"
             src="resources/img/icons/arrow-right.png">
    </div>
</div>
