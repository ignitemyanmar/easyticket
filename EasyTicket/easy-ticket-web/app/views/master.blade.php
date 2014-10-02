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

      {{HTML::script('../../js/jquery.js')}}
      {{HTML::script('../../js/jquery-ui.js')}}
    </head>
    <body>
        <nav class="top-bar show-for-large-up top-bars" data-topbar>
                <ul class="title-area">
                  <li class="name">
                    <h1><a href="/bus">Easy Ticket</a></h1>
                  </li>
                  <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
                </ul>

                <section class="top-bar-section">
                  <!-- Right Nav Section -->
                  <ul class="right">
                   <!--  <li class="active has-dropdown"><a href="/bus">Bus</a>
                      <ul class="dropdown">
                        <li><a href="/bus">YGN _ MDL</a></li>
                      </ul>
                    </li>
                    <li class="has-dropdown">
                      <a href="movie.html">Movie</a>
                      <ul class="dropdown">
                        <li><a href="#">First Movie</a></li>
                      </ul>
                    </li>
                    <li class="has-dropdown">
                      <a href="show.html">Show</a>
                      <ul class="dropdown">
                        <li><a href="showlist.html">Rock Show</a></li>
                        <li><a href="showlist.html">Hip Hop Show</a></li>
                      </ul>
                    </li> -->
                  </ul>

                  <!-- Left Nav Section -->
                  <ul class="left">
                    <!-- <li><a href="#">Left Nav Button</a></li> -->
                  </ul>
                </section>
        </nav>
        <div class="contentframe off-canvas-wrap">
          <div class="inner-wrap">
              
                <!-- <nav class="top-bar show-for-large-up top-bars" data-topbar> 
                    <ul class="title-area"> 
                      <li class="name">
                        <a href="/"></a>
                      </li>
                      <br>
                    </ul> 
                    <section class="top-bar-section"> 
                      <div style="clear:both;min-height:5.82rem;z-index:-99;">&nbsp;</div>
                      <ul class="left"> 
                      <?php $currentroute=Route::getCurrentRoute()->getPath();
                        $currentroute=substr($currentroute,0,3);
                        $currentroute=substr($currentroute,0,3);
                        ?>
                        <li @if($currentroute =='/') class="active" @else  @endif> 
                          <a href="/demo">Home</a> 
                        </li>
                        <li @if($currentroute =='buy') class="has-dropdown active" @else class="has-dropdown"  @endif> 
                          <a href="/buy-car">Bus</a> 
                          <ul class="dropdown"> 
                            <li>
                              <a href="/buy-car/coupe">
                              <span class="icon-arrow-right"></span>
                              coupe</a>
                            </li> 
                            <li>
                              <a href="/buy-car/hatchback-and-stationwagon">
                                <span class="icon-arrow-right"></span>
                                Hatchback and Stationwagon</a>
                            </li> 
                            <li>
                              <a href="/buy-car/seden">
                              <span class="icon-arrow-right"></span>
                              Seden</a>
                            </li> 
                            <li>
                              <a href="/buy-car/sports">
                              <span class="icon-arrow-right"></span>
                              sports</a>
                            </li> 
                            <li>
                              <a href="/buy-car/suv">
                              <span class="icon-arrow-right"></span>
                              suv</a>
                            </li> 
                            <li>
                              <a href="/buy-car/van">
                              <span class="icon-arrow-right"></span>
                              Van</a>
                            </li>
                            <li>
                              <a href="/buy-car/wagon">
                              <span class="icon-arrow-right"></span>
                              wagon</a>
                            </li>  

                          </ul> 
                        </li> 
                        
                        <li @if($currentroute=='con') class="active" @endif> 
                          <a href="/contact-us">ဆက္သြယ္ရန္</a>
                        </li>
                      </ul> 
                    </section> 
                </nav> -->
                <!-- shown on only on a small screen. -->
                <nav class="tab-bar hide-for-large-up">
                  <a class="left-off-canvas-toggle menu-icon">
                    <span>EazyTicket</span>
                  </a>
                </nav>
                <aside class="contentframe-left-off-canvas-menu">
                  <ul class="off-canvas-list">
                    <li><label class="first">EazyTicket</label></li>
                    <li><a href="http://www.EazyTicket.com.mm/demo">Home</a></li>
                  </ul>
                  <hr>
                  <ul class="off-canvas-list">
                    <li>
                      <label>
                        <a href="/buy-car">Bus</a>
                      </label>
                    </li>
                    <hr>
                    <div class="site-links">
                      <ul class="top">
                        <!-- <li class="logo"><a href="http://example.com/"></a></li> -->
                        <li><a href="/buy-car/coupe">Bus</a></li>
                        <li><a href="/buy-car/hatchback-and-stationwagon">Movie</a></li>
                      </ul>
                    </div>

                    <hr>
                    <div class="site-links">
                      <ul class="top">
                        <!-- <li class="logo"><a href="http://example.com/"></a></li> -->
                        <li><a href="/sell-car/add-new-vehicle">Bus</a></li>
                        <li><a href="/sell-car/my-vehicles">Movie</a></li>
                        <li><a href="/sell-car/my-profile">Show</a></li>
                      </ul>
                    </div>
                    <hr>
                  </ul>
                </aside>
                <a class="exit-off-canvas" href="#"></a>
              <section class="main-section"> 
                <!-- <div class="row"> -->
                  @yield('content')
                <!-- </div> -->
              </section> 
          </div>
        </div>
      <!-- footer  -->
      <!-- <div class="large-12 columns footer"> -->
            <div class="large-12 columns footer">
                  <div class="large-3 small-6 left columns">
                    <h3 class="fttitle">Ticketing Site</h3>
                    <p><a href="#">Link 1</a></p>
                    <p><a href="#">Link 2</a></p>
                    <p><a href="#">Link 3</a></p>
                  </div>

                  <div class="large-3 small-6 left columns">
                    <h3 class="fttitle">Follow Ticketing</h3>
                    <p><a href="">Facebook</a></p>
                  </div>
                  <div class="large-3 small-6 left columns">
                    <h3 class="fttitle">Other Links</h3>
                  </div>

                  <div class="large-3 small-6 left columns">
                    <h3 class="fttitle">About Ticketing</h3>
                    <p><a href="#">Link 3</a></p>
                    <p><a href="#">Link 4</a></p>
                    <p><a href="#">Link 5</a></p>
                  </div>

            </div>
      <!-- </div> -->
      {{HTML::script('../../../js/foundation.min.js')}}
      {{HTML::script('../../src/select2.min.js')}}
      {{HTML::script('../../js/responsiveslides.min.js')}}

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

              $("#slider0").responsiveSlides({
                  auto: true,
                  pager: false,
                  nav: true,
                  timeout: 5000,  
                  speed: 800,
                  namespace: "callbacks",
                  before: function () {
                  },
                  after: function () {
                  }
              });
          });
        </script>
        
    </body>
    </html>