<div id="content-newuser">
    <div ng-show="index == slides.indexOf('first')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="first-container"
         ng-include="'views/firsthint'">
    </div>
    <div ng-show="index == slides.indexOf('second')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="second-container"
         ng-include="'views/secondhint'">
    </div>
    <div ng-show="index == slides.indexOf('third')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="third-container"
         ng-include="'views/thirdhint'">
    </div>
    <div ng-show="index == slides.indexOf('fourth')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="fourth-container"
         ng-include="'views/fourthhint'">
    </div>
    <div ng-show="index == slides.indexOf('fifth')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="fifth-container"
         ng-include="'views/fifthhint'">
    </div>
    <div ng-show="index == slides.indexOf('sixth')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="sixth-container"
         ng-include="'views/sixthhint'">
    </div>
    <div ng-show="index == slides.indexOf('leaderboard')"
         ng-swipe-right="prevSlide()"
         ng-swipe-left="nextSlide()"
         id="leaderboard-container"
         ng-include="'views/leaderboard'">
    </div>

    <div id="slides-nav">
        <div class="circle-container" ng-repeat="slide in slides">
            <div ng-class="{'active' : index == $index}" class="circle" ng-click="goTo($index)"></div>
        </div>
    </div>

    <button ng-show="index == slides.indexOf('leaderboard')"
            class="btn btn-primary btn-lg startbutton"
            ng-click="startTheGame()">
            START
    </button>

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
