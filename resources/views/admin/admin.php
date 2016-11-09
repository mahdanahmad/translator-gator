<div id="admin-header" class="">
	<div id="logo-container" class="pull-left">
		<a ui-sref="admin.statistic"  ng-click="activePage = 'statistic'">
			<img src="resources/img/plj_logo_reversed.png" alt="logo" class="logo-default"/>
		</a>
	</div>
	<div id="toggle-mobile" class="pull-right"> <i class="icon-menu" ng-click="togglemobile = !togglemobile"></i> </div>
	<div id="user-dropdown" class="dropdown pull-right">
		<button class="dropdown-toggle" type="button" id="dropdown-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<i class="icon-user"></i>
			<span class="hide-on-mobile"> {{username}} </span>
			<i class="fa fa-angle-down"></i>
		</button>
		<ul class="dropdown-menu" aria-labelledby="dropdown-button">
			<li><a ng-click="logout()"> <i class="icon-power"></i> Log Out </a></li>
		</ul>
	</div>
</div>
<div id="admin-content" class="">
	<div id="admin-nav-wrapper" ng-class="{fold : fold, mobilecollapse : togglemobile}" >
		<ul id="admin-nav-menu">
			<li class="sidebar-toggler-wrapper">
				<i class="fa pull-right" ng-class="fold ? 'fa-angle-double-right' : 'fa-angle-double-left'" aria-hidden="true" ng-click="fold = !fold"></i>
			</li>
			<li class="start" ng-class="{'active' : activePage == 'statistic'}">
				<div ui-sref="admin.statistic" ng-click="activePage = 'statistic'; togglemobile = true; ">
					<i class="icon-graph"></i>
					<span class="title">Statistic</span>
					<span class="selected"></span>
				</div>
			</li>
			<li ng-class="{'active' : activePage == 'words'}">
				<div ui-sref="admin.words" ng-click="activePage = 'words'; togglemobile = true; ">
					<i class="icon-layers"></i>
					<span class="title">Words Management</span>
					<span class="selected"></span>
				</div>
			</li>
			<li ng-class="{'active' : activePage == 'general'}">
				<div ui-sref="admin.general" ng-click="activePage = 'general'; togglemobile = true; ">
					<i class="icon-wrench"></i>
					<span class="title">General Configuration</span>
					<span class="selected"></span>
				</div>
			</li>
			<li ng-class="{'active' : activePage == 'category'}">
				<div ui-sref="admin.category" ng-click="activePage = 'category'; togglemobile = true; ">
					<i class="icon-grid"></i>
					<span class="title">Category Configuration</span>
					<span class="selected"></span>
				</div>
			<li ng-class="{'active' : activePage == 'language'}">
				<div ui-sref="admin.language" ng-click="activePage = 'language'; togglemobile = true; ">
					<i class="icon-bubbles"></i>
					<span class="title">Language Configuration</span>
					<span class="selected"></span>
				</div>
			</li>
			<li class="last" ng-class="{'active' : activePage == 'redeem'}">
				<div ui-sref="admin.redeem" ng-click="activePage = 'redeem'; togglemobile = true; ">
					<i class="icon-wallet"></i>
					<span class="title">Redeem Configuration</span>
					<span class="selected"></span>
				</div>
			</li>
		</ul>
	</div>
	<div id="admin-page-wrapper" class="pull-right" ng-class="{fold : fold}" >
		<div id="admin-page">
			<div ui-view></div>
		</div>
	</div>
</div>
