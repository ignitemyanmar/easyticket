    <!DOCTYPE html>
    <html>
    <head>
      <title>EazyTicket</title>
      <meta property="og:image" content="http://www.EazyTicket.com/images/logo01.png"/>
      {{HTML::style('../../images/favicon.ico',array('rel'=>'icon','type'=>'image/ico'))}}
      {{HTML::style('../../css/foundation.min.css')}}
      {{HTML::style('../../css/font.css')}}
      {{HTML::style('../../../src/select2.css')}}
      {{HTML::style('../../css/custom.css')}}
      {{HTML::style('../../css/custom_frontnav.css')}}
      {{HTML::style('../../../css/jquery-ui.css')}}
      {{HTML::style('../../css/slider.css')}}

      {{HTML::style('../../assets/font-awesome/css/font-awesome.css')}}

      {{HTML::script('../../js/jquery.js')}}
      {{HTML::script('../../js/jquery-ui.js')}}
    </head>
    <body>  
    <style type="text/css">
        .title_bar{background: #01315A;height: 45px;}
        .content_title{color:#fff; font-weight: bold;font-size: 24px; text-align: center;padding-top: 9px; font-family: "Zawgyi-One";}
        .user{font-size: 14px;float: right; font-weight: 200;padding-right: 10%; padding-top:9px; position: relative;}
        .user a{color:white; padding-left: 12px;}
        #drop > li > a {color: #333;}
        .f-dropdown {max-width: 110px;}
        a:hover{text-decoration: none;}
        .noti{
              position: absolute;
              left: -45px;
              margin-right: 24px;
              font-size: 14px !important;
              font-weight: 300;
              top: 2px;
              /*right: 24px;*/
              text-align: center;
              height: 24px;
              background-color: #E02222;
              vertical-align: baseline;
              line-height: 14px;
              padding: 5px 9px 4px;
              border-radius: 12px !important;
              text-shadow: none !important;}
        .red{background: #E02222;}
      </style>
      <?php $currentroute=Route::getCurrentRoute()->getPath();?>
      <div class="large-12 title_bar">
        <div class="content_title">
          @if($currentroute=="departure-times")
          ကားထြက္မည့္အခ်ိန္ေရြးရန္
          @elseif($currentroute=="/")
            ခရီးစဥ္ ေရြးရန္
          @else
          
          @endif

          @if(Auth::check())

            <div class="user">
              @if(Session::get('bookingcount') > 0) 
                <a href="/todaybookings" target="_blank"><span class="noti red">{{Session::get('bookingcount')}}</span></a>
              @endif
              <a href="#" data-dropdown="drop" class=""><i class="icon-user"></i>&nbsp; {{Auth::user()->name}}</a>
              <br> 
              <ul id="drop" data-dropdown-content class="f-dropdown"> 
                <li><a href="/userlogout"><i class="icon-key"></i>Logout</a></li> 
              </ul>
            </div>
          @endif
        </div>
      </div>

        <div class="contentframe off-canvas-wrap">

          <div class="inner-wrap">
              <section class="main-section"> 
                <!-- <div class="row"> -->
                  @yield('content')
                <!-- </div> -->
              </section> 
          </div>
        </div>
      
      {{HTML::script('../../../js/foundation.min.js')}}
      {{HTML::script('../../src/select2.min.js')}}

    <script type="text/javascript">
        $(document).foundation();   
    </script>
        <script type="text/javascript">
            $(function(){
              var offset = 0;
            <!-- //for back to top scroll links    -->
              $(window).scroll(function(){
                var e=$(window).scrollTop();
                e>40?$("body").addClass("scrolled"):$("body").removeClass("scrolled");
              });

                $(".to-top").click(function(){$("html, body").animate({scrollTop:0},"slow")
              });
              
              setTimeout( function(){
                $('#lb_backdrop').css("opacity","0");
                $('#lb_backdrop').hide();
                },1100);
              setTimeout( function(){
                $('#container').css("opacity","1");
                  $('#loading').css("opacity","0")
                  },800);
          });
        </script>
        
    </body>
    </html>