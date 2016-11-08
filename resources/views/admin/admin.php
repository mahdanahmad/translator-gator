<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<a ui-sref="admin.statistic"  ng-click="activePage = 'statistic'">
			<img src="resources/img/plj_logo_reversed.png" alt="logo" class="logo-default"/>
			</a>
			<div class="menu-toggler sidebar-toggler hide">
			</div>
		</div>
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<li class="dropdown dropdown-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="icon-user"></i>
					<span class="username username-hide-on-mobile">
					{{username}} </span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a ng-click="logout()">
							<i class="icon-power"></i> Log Out </a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- END SIDEBAR TOGGLER BUTTON -->
				</li>
				<li class="start" ng-class="{'active' : activePage == 'statistic'}">
					<a href="javascript:;"  ui-sref="admin.statistic" ng-click="activePage = 'statistic'">
					<i class="icon-graph"></i>
					<span class="title">Statistic</span>
					<span class="selected"></span>
					</a>
				</li>
				<li ng-class="{'active' : activePage == 'words'}">
					<a href="javascript:;"  ui-sref="admin.words" ng-click="activePage = 'words'">
					<i class="icon-layers"></i>
					<span class="title">Words Management</span>
					<span class="selected"></span>
					</a>
				</li>
				<li ng-class="{'active' : activePage == 'general'}">
					<a href="javascript:;"  ui-sref="admin.general" ng-click="activePage = 'general'">
					<i class="icon-wrench"></i>
					<span class="title">General Configuration</span>
					<span class="selected"></span>
					</a>
                </li>
				<li ng-class="{'active' : activePage == 'category'}">
					<a href="javascript:;"  ui-sref="admin.category" ng-click="activePage = 'category'">
					<i class="icon-grid"></i>
					<span class="title">Category Configuration</span>
					<span class="selected"></span>
					</a>
				<li ng-class="{'active' : activePage == 'language'}">
					<a href="javascript:;"  ui-sref="admin.language" ng-click="activePage = 'language'">
					<i class="icon-bubbles"></i>
					<span class="title">Language Configuration</span>
					<span class="selected"></span>
					</a>
				</li>
				<li class="last" ng-class="{'active' : activePage == 'redeem'}">
					<a href="javascript:;"  ui-sref="admin.redeem" ng-click="activePage = 'redeem'">
					<i class="icon-wallet"></i>
					<span class="title">Redeem Configuration</span>
					<span class="selected"></span>
					</a>
				</li>
            </ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<div ui-view></div>
		</div>
	</div>
	<!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
