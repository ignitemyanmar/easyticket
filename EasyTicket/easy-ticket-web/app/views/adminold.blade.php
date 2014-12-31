<!DOCTYPE html>
 <html lang="en"> 
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>528| Admin Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    {{HTML::style('../../../assets/bootstrap/css/bootstrap.min.css')}}
    {{HTML::style('../../../assets/bootstrap/css/bootstrap-responsive.min.css')}}
    {{HTML::style('../../../assets/font-awesome/css/font-awesome.css')}}
    {{HTML::style('../../../assets/css/style.css')}}
    {{HTML::style('../../../assets/css/style_responsive.css')}}
    <link href="../../../assets/css/style_default.css" rel="stylesheet" id="style_color" />
    {{HTML::style('../../../css/font.css')}}
    {{HTML::style('../../../assets/css/metro.css')}}
    {{HTML::style('../../../css/dashboard.css')}}
    <!-- <link rel="shortcut icon" href="favicon.ico" /> -->
    {{HTML::script('../../../assets/js/jquery-1.8.3.min.js')}}
    {{HTML::script('../../../src/jquery-ui.js')}}
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
                <img src="assets/img/logo1.png" alt="logo" />
                <!-- <img src="assets/img/logo.png" alt="logo" /> -->
                </a>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">
                <img src="assets/img/menu-toggler.png" alt="" />
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
            <ul>
                <li>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler hidden-phone"></div>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                </li>
                <li>
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                    <form class="sidebar-search">
                        <div class="input-box">
                            <a href="javascript:;" class="remove"></a>
                            <input type="text" placeholder="Search..." />               
                            <input type="button" class="submit" value=" " />
                        </div>
                    </form>
                    <!-- END RESPONSIVE QUICK SEARCH FORM -->
                </li>
                <li @if($currentroute=='das') class="start active" @else class="start" @endif>
                    <a href="/dashboard">
                    <i class="icon-home"></i> 
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                    </a>
                </li>
                
                <li @if($currentroute=='ite') class="has-sub active" @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">Item</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/item">Item List</a></li>
                        <li ><a href="/item/create">Item Create</a></li>
                    </ul>
                </li>
                @if(Auth::user()->role==8)
                    <li @if($currentroute=='men') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Menu</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/menu">Menu List</a></li>
                            <li ><a href="/menu/create">Menu Create</a></li>
                        </ul>
                    </li>
                @endif

                <li @if($currentroute=='sho') class="has-sub active" @else class="has-sub" @endif>
                    <a href="javascript:;">
                    <i class="icon-th-list"></i> 
                    <span class="title">Shops</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="/shop">Shop List</a></li>
                        <li ><a href="/shops/create">Shop Create</a></li>
                    </ul>
                </li>

                @if(Auth::user()->role==8)

                    <li @if($currentroute=='cat' || $currentroute=='sub') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Category</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/category">Category List</a></li>
                            <li ><a href="/category/create">Category Create</a></li>
                            <li ><a href="/subcategory">Subcategory List</a></li>
                            <li ><a href="/subcategory/create">Subcategory Create</a></li>
                            <li ><a href="/itemcategory">Item Category List</a></li>
                            <li ><a href="/itemcategory/create">Item Category Create</a></li>
                        </ul>
                    </li>


                    <li @if($currentroute=='bra') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Brands</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/brand">Brand List</a></li>
                            <li ><a href="/brand/create">Brand Create</a></li>
                        </ul>
                    </li>
                                    
                    <li @if($currentroute=='cit') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">City</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/city">City List</a></li>
                            <li ><a href="/city/create">City Create</a></li>
                            <li ><a href="/township">Township List</a></li>
                            <li ><a href="/township/create">Township Create</a></li>
                        </ul>
                    </li>

                    <li @if($currentroute=='col') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Color</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/color">Color List</a></li>
                            <li ><a href="/color/create">Color Create</a></li>
                        </ul>
                    </li>

                    <li @if($currentroute=='siz') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Size</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/size">Size List</a></li>
                            <li ><a href="/size/create">Size Create</a></li>
                        </ul>
                    </li>


                    
                    <li @if($currentroute=='qty') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Qty Range for Price</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/qtyrangeforprice">Qty Ranges for Price List</a></li>
                            <li ><a href="/qtyrangeforprice/create">Qty Range for Price Create</a></li>
                        </ul>
                    </li>
                @endif
                
                <li class="has-sub ">
                    <a href="javascript:;">
                    <i class="icon-comment"></i> 
                    <span class="title">Messages</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="#">Message List</a></li>
                    </ul>
                </li>
                
                <li class="has-sub ">
                    <a href="#">
                    <i class="icon-user"></i> 
                    <span class="title">Customer</span>
                    </a>
                </li>
                
                <li class="">
                    <a href="/order">
                    <i class="icon-bar-chart"></i> 
                    <span class="title">Order List</span>
                    </a>
                </li>
                @if(Auth::user()->role==8)
                    <li @if($currentroute=='adv') class="has-sub active" @else class="has-sub" @endif>
                        <a href="javascript:;">
                        <i class="icon-th-list"></i> 
                        <span class="title">Advertisement</span>
                        <span class="arrow "></span>
                        </a>
                        <ul class="sub">
                            <li ><a href="/advertisement">Advertisement List</a></li>
                            <li ><a href="/advertisement/create">Advertisement Create</a></li>
                        </ul>
                    </li>
                @endif
                <li class="has-sub ">
                    <a href="#">
                    <i class="icon-user"></i> 
                    <span class="title">Profile</span>
                    <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                        <li ><a href="#">Your Profile</a></li>
                    </ul>
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
        2014 &copy; 528.
        <div class="span pull-right">
            <span class="go-top"><i class="icon-angle-up"></i></span>
        </div>
    </div>
    
    
    {{HTML::script('../../../assets/breakpoints/breakpoints.js')}}
    {{HTML::script('../../../assets/jquery-ui/jquery-ui-1.10.1.custom.min.js')}}
    {{HTML::script('../../../assets/jquery-slimscroll/jquery.slimscroll.min.js')}}
    {{HTML::script('../../../assets/bootstrap/js/bootstrap.min.js')}}
    {{HTML::script('../../../assets/js/jquery.blockui.js')}}
    {{HTML::script('../../../assets/js/jquery.cookie.js')}}
    {{HTML::script('../../../assets/flot/jquery.flot.js')}}
    {{HTML::script('../../../assets/flot/jquery.flot.resize.js')}}
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
