app.controller('statisticController', ['$scope', 'Notification', 'fetcher', 'messageHelper', function ($scope, Notification, fetcher, messageHelper) {
    'use strict';

    $scope.stat         = {};

    $scope.lineLabels   = [];
    $scope.lineColours  = [];
    $scope.lineSeries   = [];
    $scope.lineData     = [];

    $scope.radarLabels  = [];
    $scope.radarColours = [];
    $scope.radarSeries  = [];
    $scope.radarData    = [];

    $scope.lineOnClick = function (points, evt) {
//        console.log(points, evt);
    };

    $scope.radarOnClick = function (points, evt) {
//        console.log(points, evt);
    };

    var init = function() {
        fetcher.getStatistic(function(response) {
            if ((response.status_code == 200) && (response.response == "OK")) {
//                console.log(response);
                $scope.lineLabels   = response.result.line_chart.linelabels;
                $scope.lineSeries   = response.result.line_chart.lineseries;
                $scope.lineData     = response.result.line_chart.linedata;

                $scope.radarLabels  = response.result.radar_chart.radarlabels;
                $scope.radarSeries  = response.result.radar_chart.radarseries;
                $scope.radarData    = response.result.radar_chart.radardata;

                $scope.stats        = response.result.stats;
                $scope.words        = response.result.words;
            } else {
                Notification.error(messageHelper.massiveErrorMsg());
            }
        });

//        $scope.lineLabels   = ["5/11", "6/11", "7/11", "8/11", "9/11", "10/11", "11/11"];
//        $scope.lineSeries   = ['Translate', 'Alternative', 'Vote', 'Categorize'];
        $scope.lineColours  = ['#7BBADD', '#F1734F', '#D2B551', '#B76995'];
//        $scope.lineData     = [
//            [65, 59, 80, 81, 56, 55, 40],
//            [28, 48, 40, 19, 86, 27, 90],
//            [50, 65, 43, 90, 64, 41, 56],
//            [24, 90, 65, 43, 21, 88, 21],
//        ];

//        $scope.radarLabels  = ["Indonesia", "Jawa", "Sunda", "Minang", "Makasar"];
//        $scope.radarSeries  = ['User', 'Translated Word'];
        $scope.radarColours = ['#2AABD2', '#989898'];
//        $scope.radarData    = [
//            [65, 23, 90, 42, 56],
//            [28, 48, 40, 19, 100]
//        ];
    }

    init();
}]);
