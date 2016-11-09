<!DOCTYPE html>
<html lang="en" ng-app="app">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <head>
        <title>Pulse Lab - Translator Gator</title>
        <base href=<?php echo env('APP_BASEURL','http://localhost:8000/');?>>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-router/0.3.2/angular-ui-router.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ui-router-extras/0.1.3/ct-ui-router-extras.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/sha1.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-touch/1.5.8/angular-touch.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-permission/3.2.0/angular-permission.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-permission/3.2.0/angular-permission-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-local-storage/0.5.0/angular-local-storage.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-notification/0.2.0/angular-ui-notification.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.1.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.1.1/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.5.5/angular-datatables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.5.5/plugins/buttons/angular-datatables.buttons.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.2.0/ui-bootstrap-tpls.min.js"></script>
        <script src="https://unpkg.com/angular-toggle-switch@1.3.0/angular-toggle-switch.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.16.6/lodash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sugar/1.4.1/sugar-full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-chart.js/1.0.3/angular-chart.min.js"></script>

        <script type="text/javascript" src="resources/js/app.js"></script>
        <script type="text/javascript" src="resources/js/config.js"></script>
        <script type="text/javascript" src="resources/js/factory.js"></script>

        <script type="text/javascript" src="resources/js/auth/authController.js"></script>
        <script type="text/javascript" src="resources/js/auth/loginController.js"></script>
        <script type="text/javascript" src="resources/js/auth/resetController.js"></script>
        <script type="text/javascript" src="resources/js/auth/logoutController.js"></script>
        <script type="text/javascript" src="resources/js/auth/forgotController.js"></script>
        <script type="text/javascript" src="resources/js/auth/confirmController.js"></script>
        <script type="text/javascript" src="resources/js/auth/registerController.js"></script>
        <script type="text/javascript" src="resources/js/auth/unconfirmedController.js"></script>

        <script type="text/javascript" src="resources/js/admin/adminController.js"></script>
        <script type="text/javascript" src="resources/js/admin/wordsController.js"></script>
        <script type="text/javascript" src="resources/js/admin/generalController.js"></script>
        <script type="text/javascript" src="resources/js/admin/categoryController.js"></script>
        <script type="text/javascript" src="resources/js/admin/languageController.js"></script>
        <script type="text/javascript" src="resources/js/admin/statisticController.js"></script>
        <script type="text/javascript" src="resources/js/admin/redeemAdminController.js"></script>

        <script type="text/javascript" src="resources/js/dashboard/dashboardController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/voteController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/hintController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/driftController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/kickedController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/redeemController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/newuserController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/profileController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/translateController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/categorizeController.js"></script>
        <script type="text/javascript" src="resources/js/dashboard/alternativeController.js"></script>

        <script src="resources/metronic/scripts/metronic.js" type="text/javascript"></script>
        <script src="resources/metronic/scripts/layout.js" type="text/javascript"></script>
        <script src="resources/metronic/scripts/quick-sidebar.js" type="text/javascript"></script>
        <!-- <script>
            jQuery(document).ready(function() {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            QuickSidebar.init(); // init quick sidebar
        });
        </script> -->

        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://unpkg.com/angular-toggle-switch@1.3.0/angular-toggle-switch.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.1/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.5.5/css/angular-datatables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-notification/0.2.0/angular-ui-notification.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap3/bootstrap-switch.min.css">

        <!-- BEGIN METRONIC STYLES -->
        <!-- <link href="resources/metronic/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="resources/metronic/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="resources/metronic/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="resources/metronic/css/themes/pulselab.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="resources/metronic/css/custom.css" rel="stylesheet" type="text/css"/>
        <link href="resources/metronic/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <link href="resources/metronic/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/> -->
        <!-- END METRONIC STYLES -->

        <link rel="stylesheet" href="resources/stylesheets/css/main.css">

    </head>
    <body ng-controller="MainController" class="page-header-fixed page-quick-sidebar-over-content page-sidebar-closed-hide-logo page-container-bg-solid" ng-style="bodyBackground">
        <!-- <div id="fb-root"></div> -->
        <div ui-view></div>

        <!-- <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId      : '1013431635381749',
                    xfbml      : true,
                    version    : 'v2.5'
                });
            };

            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <script type="text/javascript" async src="https://platform.twitter.com/widgets.js"></script> -->
    </body>
</html>
