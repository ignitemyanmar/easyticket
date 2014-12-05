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
                    $operator_id=OperatorGroup::whereuser_id($userid)->pluck('operator_id');

                    /*if($usertype=='operator')
                    $operator_id=OperatorGroup::whereuser_id($userid)->pluck('operator_id');
                    else
                    $agent_id=Agent::whereuser_id($userid)->pluck('id');*/

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
<!--                     <form class="sidebar-search">
                        <div class="input-box">
                            <a href="javascript:;" class="remove"></a>
                            <input type="text" placeholder="Search..." />               
                            <input type="button" class="submit" value=" " />
                        </div>
                    </form> -->
                </li>
                <li @if($currentroute=='das') class="start " @else class="start" @endif>
                    <a href="/report/dailycarandadvancesale?operator_id={{$operator_id}}">
                    <i class="icon-home"></i> 
                    <span class="title">ပင်မစာမျက်နှာ</span>
                    <span class="selected"></span>
                    </a>
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
                        <!-- <li ><a href="#">Trip list by date range report</a></li>
                        <li ><a href="#">Trip list by daily report</a></li> -->
                    </ul>
                </li>
                @endif
                <li @if($currentroute=='daiadv') class="" @endif>
                    <a href="/report/dailycarandadvancesale?operator_id={{$operator_id}}">
                    <i class="icon-th-list"></i> 
                    <span class="title">ေန့စဥ်အေရာင်းစာရင်းများ</span>
                    </a>
                </li>
                <li @if($currentroute=='dai') class="" @endif>
                    <a href="/report/dailybydeparturedate">
                    <i class="icon-th-list"></i> 
                    <span class="title">ကားချုပ် စာရင်းများ</span>
                    </a>
                </li>
                @if($usertype=='operator')
                <li @if($currentroute=='rep') class="" @endif>
                    <a href="/report/operator/trip/dateranges?operator_id={{$operator_id}}&trips=1">
                    <i class="icon-th-list"></i> 
                    <span class="title">ခရီးစဥ်အလုိက်အေရာင်း စာရင်းများ</span>
                    </a>
                </li>

                <li @if($currentroute=='rep') class="" @endif>
                    <a href="/report/operator/trip/dateranges?operator_id={{$operator_id}}">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းကုိယ်စားလှယ်နှင့် အေရာင်းစာရင်းများ</span>
                    </a>
                </li>

                <li @if($currentroute=='rep') class="" @endif>
                    <a href="/report/booking">
                    <i class="icon-th-list"></i> 
                    <span class="title">ြကိုတင်မှာယူေသာ စာရင်းများ</span>
                    </a>
                </li>

                
                

                <li @if($currentroute=='') class="" @endif>
                    <a href="/report/bestseller/trip">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းရဆုံး ခရီးစဥ် စာရင်းများ</span>
                    </a>
                </li>


                <li @if($currentroute=='') class="" @endif>
                    <a href="/report/bestseller/agents">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းရဆုံး အေရာင်းကုိယ်စားလှယ် စာရင်းများ</span>
                    </a>
                </li>

                <!-- <li @if($currentroute=='') class="" @endif>
                    <a href="/report/bestseller/time">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းရဆုံး အချိန် စာရင်းများ</span>
                    </a>
                </li> -->

                <li @if($currentroute=='') class="" @endif>
                    <a href="/report/agentscredit">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းကုိယ်စားလှယ် နှင့် အေြကွးစာရင်းများ</span>
                    </a>
                </li>

                @endif
                <!-- 
                    <li @if($currentroute=='dai') class="has-sub " @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">ကားချုပ် အေရာင်းစာရင်း</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/report/dailybydeparturedate">ကားထွက်မည့်ေန့ အလုိက် အေရာင်းစာရင်း</a></li>
                        </ul>
                    </li> 

                    <li @if($currentroute=='dai') class="has-sub " @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">ေန့စဥ် အေရာင်းစာရင်း</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/report/dailycarandadvancesale?operator_id={{$operator_id}}">ေန့စဥ် နှင့် ြကိုေရာင်းစားရင်း</a></li>
                        </ul>
                    </li>
                    <li @if($currentroute=='sea') class="has-sub " @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-bar-chart"></i> 
                        <span class="title">Seat Occupied By Bus</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/report/seatoccupiedbybus">Seat Occupied By Bus Report</a></li>
                        </ul>
                    </li> 
                -->
                
                <!-- <li @if($currentroute=='ope') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ကားဂိတ်များ</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/operators/create">ကားဂိတ်အသစ်ထည့်သွင်းြခင်း</a></li>
                        <li ><a href="/operatorlist">ကားဂိတ်များ</a></li>
                    </ul>
                </li> -->
                <li @if($currentroute=='age') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းကုိယ်စားလှယ်များ</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/agents/create">အေရာင်းကုိယ်စားလှယ် အသစ်ထည့်သွင်းြခင်း</a></li>
                        <li ><a href="/agentlist">အေရာင်းကုိယ်စားလှယ်များ</a></li>
                    </ul>
                </li>
                <li @if($currentroute=='ord') class="" @endif>
                    <a href="/orderlist">
                    <i class="icon-th-list"></i> 
                    <span class="title">ေရာင်းြပီးလက်မှတ်များ ဖျက်ရန်</span>
                    </a>
                </li>

                <li @if($currentroute=='cit') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ြမို့များ</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/city/create">ြမို့ အသစ်ထည့်သွင်းြခင်း</a></li>
                        <li ><a href="/citylist">ြမို့များ</a></li>
                    </ul>
                </li>
                <li @if($currentroute=='bus') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ကားအမျိုးအစား</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/busclass/create">ကားအမျိုးအစား အသစ်ထည့်သွင်းြခင်း</a></li>
                        <li ><a href="/busclasslist">ကားအမျိုးအစားများ</a></li>
                    </ul>
                </li>
                <li @if($currentroute=='sea') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ခုံအေနအထား အစီအစဥ်</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/seatlayout/create">ခုံအေနအထား အသစ်ထည့်သွင်းြခင်း</a></li>
                        <li ><a href="/seatlayoutlist">ခုံအေနအထားများ</a></li>
                    </ul>
                </li>

                

                <li @if($currentroute=='tri') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ခရီးစဥ် အသစ်ထည့်မည်။</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/trip/create">ခရီးစဥ် အသစ်ထည့်မည်။</a></li>
                        <li ><a href="/trip-list">ခရီးစဥ်များ</a></li>
                    </ul>
                </li>

                <li @if($currentroute=='sea') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">ခုံနံပါတ် အစီအစဥ်</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/seatplans/create">ခုံနံပါတ် သတ်မှတ်ြခင်း</a></li>
                        <li ><a href="/seatplanlist">ခုံနံပါတ်များ</a></li>
                    </ul>
                </li> 
                <li @if($currentroute=='cli') class="" @endif>
                    <a href="/client/sync">
                    <i class="icon-th-list"></i> 
                    <span class="title">Sync</span>
                    </a>
                </li>
                <!-- <li @if($currentroute=='sea') class=""@endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">အေရာင်းကုိယ်စားလှယ် အေြကွးစာရင်း</span>
                    </a>
                </li>  -->
                <!-- 
                <li @if($currentroute=='age') class="has-sub " @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-user"></i> 
                    <span class="title">Agent Group</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/agentgroup/create">Agent Group Create</a></li>
                        <li ><a href="/agentgrouplist">Agent Group List</a></li>
                    </ul>
                </li>
                 -->
                
                <!-- 
                @if($usertype=='admin')
                    <li @if($currentroute=='tic') class="has-sub " @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-user"></i> 
                        <span class="title">Ticket Type</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/tickettype/create">Ticket Type Create</a></li>
                            <li ><a href="/tickettypelist">Ticket Type List</a></li>
                        </ul>
                    </li>
                @endif
                 -->

                <!--
                <li class="has-sub ">
                    <a href="javascript:;">
                    <i class="icon-user"></i> 
                    <span class="title">Profile</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="#">Your Profile</a></li>
                    </ul>
                </li>
                -->
                
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
