<div id="history-page" class="noselect">
    <div id="history-title"><h3><strong>History</strong></h3></div>
    <div id="history-header">
        <div class="right-border history-no">No</div>
        <div class="right-border history-id">ID</div>
        <div class="right-border history-mobile">Mobile Number</div>
        <div class="right-border history-idr">IDR</div>
        <div class="history-status">Status</div>
        <div class="clearfix"></div>
    </div>
    <div id="history-table">
        <div id="history-row" ng-repeat="value in history" ng-class="{'last' : $last}">
            <div class="right-border history-no">{{$index + 1}}</div>
            <div class="right-border history-id">{{value._id}}</div>
            <div class="right-border history-mobile">0{{value.mobile}}</div>
            <div class="right-border history-idr">{{editCredit(value.credit)}}</div>
            <div class="history-status">{{value.status}}</div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="history-detail">
        Please allow 2-3 working days before receiving phone credit. If you haven't received your phone credit send your email to <a href="mailto:plj-tg@un.or.id?Subject=Translator Gator Question" target="_top">plj-tg@un.or.id</a>
    </div>
</div>
