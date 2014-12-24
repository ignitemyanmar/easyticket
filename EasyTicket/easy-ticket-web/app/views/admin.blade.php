<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Easyticket| Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    {{HTML::style('../../../assets/bootstrap/css/bootstrap.min.css')}}
    {{HTML::style('../../../assets/css/metro.css')}}
    {{HTML::style('../../../assets/bootstrap/css/bootstrap-responsive.min.css')}}
    {{HTML::style('../../../assets/font-awesome/css/font-awesome.css')}}
    {{HTML::style('../../../assets/css/style.css')}}
    {{HTML::style('../../../assets/css/style_responsive.css')}}
    <link href="../../../assets/css/style_default.css" rel="stylesheet" id="style_color" />
    {{HTML::style('../../../assets/gritter/css/jquery.gritter.css')}}
    {{HTML::style('../../../assets/uniform/css/uniform.default.css')}}
    {{HTML::style('../../../assets/bootstrap-daterangepicker/daterangepicker.css')}}
    {{HTML::style('../../../css/font.css')}}
    {{HTML::style('../../../css/dashboard.css')}}
    <!-- {{HTML::style('../../assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css')}} -->
     
    <!-- <link href="../../assets/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" /> -->
    <link rel="shortcut icon" href="favicon.ico" />

    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS -->
    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- {{HTML::script('../../js/jquery.js')}} -->
    {{HTML::script('../../../assets/js/jquery-1.8.3.min.js')}}

    <!--[if lt IE 9]>
    <script src="assets/js/excanvas.js"></script>
    <script src="assets/js/respond.js"></script>    
    <![endif]-->  

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="navbar-inner">
            <div class="container-fluid">
                <!-- BEGIN LOGO -->
                <a class="brand" href="index.html">
                <!-- <img src="assets/img/logo1.png" alt="logo" /> -->
                <!-- <img src="assets/img/logo.png" alt="logo" /> -->
                </a>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <!-- <img src="assets/img/menu-toggler.png" alt="" /> -->
                </a>          
                <!-- END RESPONSIVE MENU TOGGLER -->                
                <!-- BEGIN TOP NAVIGATION MENU -->                  
                <ul class="nav pull-right">
                    <!-- BEGIN INBOX DROPDOWN -->
                    <!-- END INBOX DROPDOWN -->
                    <!-- BEGIN USER LOGIN DROPDOWN -->
                    <li class="dropdown user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- <img alt="" src="assets/img/avatar1_small.jpg" /> -->
                        <span class="username">@if(Auth::check()) {{ Auth::user()->name }} @endif</span>
                        <i class="icon-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
                            <li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>
                            <li class="divider"></li>
                            <li><a href="/users-logout"><i class="icon-key"></i> Log Out</a></li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
                <!-- END TOP NAVIGATION MENU -->    
            </div>
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container row-fluid">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar nav-collapse collapse">
            <!-- BEGIN SIDEBAR MENU --> 
            <?php $currentroute=Route::getCurrentRoute()->getPath();
              $currentroute=substr($currentroute,0,3);
            ?>
            @if(Auth::check())
                <?php 
                    $operator_id =$agent_id =0;
                    $userid=Auth::user()->id;
                    $usertype=Auth::user()->type;
                    $operator_id=$myApp->operator_id;
                    Session::put('operator_id', $operator_id);

                ?>
            @endif
            <ul>
                <li>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler hidden-phone"></div>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                </li>
                <li>
                &nbsp;
                </li>

                @if($usertype=='agent')
                    <li @if($currentroute=='age') class="has-sub " @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">အေရာင်းကုိယ်စားလှယ် အေရာင်းစာရင်း</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/operators/agent/{{$agent_id}}">Oparator List Report</a></li>
                        </ul>
                    </li>
                @endif
                @include('include.permission_menu')

                <li @if($currentroute=='cli') class="" @endif>
                    <a href="/client/sync">
                    <i class="icon-th-list"></i> 
                    <span class="title">Sync</span>
                    </a>
                </li>

            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
        @yield('content')
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="footer">
        2014 &copy; Easyticket.
        <div class="span pull-right">
            <span class="go-top"><i class="icon-angle-up"></i></span>
        </div>
    </div>
    
    
    {{HTML::script('../../../assets/breakpoints/breakpoints.js')}}
    {{HTML::script('../../../assets/jquery-slimscroll/jquery.slimscroll.min.js')}}
    <!-- {{HTML::script('../../../assets/fullcalendar/fullcalendar/fullcalendar.min.js')}} -->
    {{HTML::script('../../../assets/bootstrap/js/bootstrap.min.js')}}
    {{HTML::script('../../../assets/js/jquery.blockui.js')}}
    {{HTML::script('../../../assets/js/jquery.cookie.js')}}
    {{HTML::script('../../../assets/flot/jquery.flot.js')}}
    {{HTML::script('../../../assets/flot/jquery.flot.resize.js')}}
    {{HTML::script('../../../assets/gritter/js/jquery.gritter.js')}}
    <!-- {{HTML::script('../../../assets/uniform/jquery.uniform.min.js')}} -->
    <!-- {{HTML::script('../../../assets/bootstrap-daterangepicker/date.js')}} -->
    <!-- {{HTML::script('../../../assets/bootstrap-daterangepicker/daterangepicker.js')}} -->
    {{HTML::script('../../../assets/js/app.js')}}   
    <script>
        jQuery(document).ready(function() { 
            App.init(); // init the rest of plugins and elements
        });
    </script>   
    
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
