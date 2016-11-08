<div id="content-profile">
    <div ng-show="index == slides.indexOf('leaderboard')"
         ng-swipe-right="prevSlide()" 
         ng-swipe-left="nextSlide()"
         id="leaderboard-container" 
         ng-include="'views/leaderboard'">
    </div>
    <div ng-show="index == slides.indexOf('details')"
         ng-swipe-right="prevSlide()" 
         ng-swipe-left="nextSlide()"
         id="details-container" 
         ng-include="'views/details'">
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