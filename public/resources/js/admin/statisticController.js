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

    var init = function() {
        fetcher.getStatistic(function(response) {
            if ((response.status_code == 200) && (response.response == "OK")) {
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

        $scope.lineColours  = ['#7BBADD', '#F1734F', '#D2B551', '#B76995'];
        $scope.radarColours = ['#2AABD2', '#989898'];
    }

    init();
}]);
