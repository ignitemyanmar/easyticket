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
    <style type="text/css">
        .title_bar{background: #0800ad;height: 45px;}
        .content_title{color:#fff; font-weight: bold;font-size: 24px; text-align: center;padding-top: 9px; font-family: "Zawgyi-One";}
      </style>
      <div class="large-12 title_bar">
        <div class="content_title">ခရီးစဥ္ ေရြးရန္</div>
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
      <!-- footer  -->
      <!-- <div class="large-12 columns footer"> -->
            <!-- <div class="large-12 columns footer">
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

            </div> -->
      <!-- </div> -->
      {{HTML::script('../../../js/foundation.min.js')}}
      {{HTML::script('../../src/select2.min.js')}}

    <script type="text/javascript">
        $(document).foundation();   
    </script>
    </body>
    </html>