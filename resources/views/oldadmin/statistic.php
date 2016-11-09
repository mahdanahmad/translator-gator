<div id="statistic-view">
    <!-- BEGIN DASHBOARD STATS -->
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison pulselab-translate">
                <div class="visual">
                    <i class="fa fa-pencil"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{stats.translated}}
                    </div>
                    <div class="desc">
                         Translate Action
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense pulselab-alternative">
                <div class="visual">
                    <i class="fa fa-random"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{stats.alternated}}
                    </div>
                    <div class="desc">
                         Alternative Action
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze pulselab-vote">
                <div class="visual">
                    <i class="fa fa-pencil-square-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{stats.voted}}
                    </div>
                    <div class="desc">
                         Vote Action
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum pulselab-categorize">
                <div class="visual">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{stats.categorized}}
                    </div>
                    <div class="desc">
                         Categorize Action
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue-madison pulselab-translate">
                <div class="visual">
                    <i class="fa fa-pencil"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{words.translated}}
                    </div>
                    <div class="desc">
                         Translated Word(s)
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red-intense pulselab-alternative">
                <div class="visual">
                    <i class="fa fa-random"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{words.alternated}}
                    </div>
                    <div class="desc">
                         Alternated Word(s)
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green-haze pulselab-vote">
                <div class="visual">
                    <i class="fa fa-pencil-square-o"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{words.voted}}
                    </div>
                    <div class="desc">
                         Voted Word(s)
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat purple-plum pulselab-categorize">
                <div class="visual">
                    <i class="fa fa-tags"></i>
                </div>
                <div class="details">
                    <div class="number">
                         {{words.categorized}}
                    </div>
                    <div class="desc">
                         Categorized Word(s)
                    </div>
                </div>
                <a class="more"></a>
            </div>
        </div>
    </div>
    <!-- END DASHBOARD STATS -->
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="portlet light bordered">
                <div class="portlet-body">
                    <div class="col-md-6">
                        <canvas id="line"
                                class="chart chart-line"
                                chart-data="lineData"
                                chart-labels="lineLabels"
                                chart-legend="true"
                                chart-series="lineSeries"
                                chart-colours="lineColours"
                                chart-click="lineOnClick"
                                chart-options="{datasetFill : false}">
                        </canvas>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="radar"
                                class="chart chart-radar"
                                chart-data="radarData"
                                chart-labels="radarLabels"
                                chart-legend="true"
                                chart-series="radarSeries"
                                chart-colours="radarColours"
                                chart-click="radarOnClick" >
                        </canvas>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
