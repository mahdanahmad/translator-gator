<div id="redeem-view">
    <div id="redeem-filter">
        <div class="col-md-3 col-xs-6">
            <button class="btn" id="redeem-filter-all"
                    ng-click="changeFilter('all')"
                    ng-class="{'redeem-filter-active' : redeem.filter == 'all'}">
                All
            </button>
        </div>
        <div class="col-md-3 col-xs-6">
            <button class="btn" id="redeem-filter-onprogress"
                    ng-click="changeFilter('on progress')"
                    ng-class="{'redeem-filter-active' : redeem.filter == 'on progress'}">
                On Progress
            </button>
        </div>
        <div class="col-md-3 col-xs-6">
            <button class="btn" id="redeem-filter-success"
                    ng-click="changeFilter('success')"
                    ng-class="{'redeem-filter-active' : redeem.filter == 'success'}">
                Success
            </button>
        </div>
        <div class="col-md-3 col-xs-6">
            <button class="btn" id="redeem-filter-failed"
                    ng-click="changeFilter('failed')"
                    ng-class="{'redeem-filter-active' : redeem.filter == 'failed'}">
                Failed
            </button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="redeem-table">
        <div id="redeem-title">
            <span>{{redeem.filter}} Redeem Data</span>
            <span id="redeem-upload" class="btn btn-file" redeem-uploader>
                <i class="icon-cloud-upload"></i> Upload Report <input type="file" accept=".csv" ng-disabled="upload_disabled">
            </span>
        </div>
        <table datatable="ng" dt-options="table.options" dt-column-defs="table.colums" dt-disable-deep-watchers="true" class="cell-border compact hover order-column">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Previous Points</th>
                    <th>Point Redeemed</th>
                    <th>Mobile Number</th>
                    <th>Denomination</th>
                    <th>Request On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="value in redeem.show">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ value._id }}</td>
                    <td>{{ value.user.username }}</td>
                    <td>{{ value.user.email }}</td>
                    <td>{{ value.prev }}</td>
                    <td>{{ value.points }}</td>
                    <td>62{{ value.mobile }}</td>
                    <td>{{ value.credit }}</td>
                    <td>{{ configureDate(value.created_at) }}</td>
                    <td ng-class="statusColor(value.status)">
                        {{ value.status }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
