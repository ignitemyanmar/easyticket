<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>403 Error</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/font-awesome/css/font-awesome.css">

    <!-- Custom styles for this template -->

    <style>
    /* Page Layout CSS */
            body {
              padding-top: 20px;
            }
            /* Everything but the jumbotron gets side spacing for mobile-first views */
            .masthead,
            .body-content,
            .footer {
              padding-left: 15px;
              padding-right: 15px;
            }
/*
            .footer {
              border-top: 1px solid #ddd;
              margin-top: 30px;
              padding-top: 29px;
              padding-bottom: 30px;
            }
*/
            /* Main marketing message and sign up button */
            .jumbotron {
              text-align: center;
              background-color: transparent;
            }
            .jumbotron .btn {
              font-size: 21px;
              padding: 14px 24px;
            }
            /* Customize the nav-justified links to be fill the entire space of the .navbar */
            .nav-justified {
              max-height: 50px;
              background-color: #eee;
              border-radius: 5px;
              border: 1px solid #ccc;
            }
            .nav-justified > li > a {
              padding-top: 15px;
              padding-bottom: 15px;
              color: #777;
              font-weight: bold;
              text-align: center;
              border-left: 1px solid rgba(255,255,255,.75);
              border-right: 1px solid rgba(0,0,0,.1);
              background-color: #e5e5e5; /* Old browsers */
              background-repeat: repeat-x; /* Repeat the gradient */
              background-image: -moz-linear-gradient(top, #f5f5f5 0%, #e5e5e5 100%); /* FF3.6+ */
              background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f5f5f5), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */
              background-image: -webkit-linear-gradient(top, #f5f5f5 0%,#e5e5e5 100%); /* Chrome 10+,Safari 5.1+ */
              background-image: -ms-linear-gradient(top, #f5f5f5 0%,#e5e5e5 100%); /* IE10+ */
              background-image: -o-linear-gradient(top, #f5f5f5 0%,#e5e5e5 100%); /* Opera 11.10+ */
              filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f5f5f5', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
              background-image: linear-gradient(top, #f5f5f5 0%,#e5e5e5 100%); /* W3C */
            }
            .nav-justified > .active > a {
              background-color: #ddd;
              background-image: none;
              box-shadow: inset 0 3px 7px rgba(0,0,0,.15);
            }
            .nav-justified > li:first-child > a {
              border-left: 0;
              border-top-left-radius: 5px;
              border-bottom-left-radius: 5px;
            }
            .nav-justified > li:last-child > a {
              border-right: 0;
              border-top-right-radius: 5px;
              border-bottom-right-radius: 5px;
            }
            /* Responsive: Portrait tablets and up */
            @media screen and (min-width: 768px) {
              /* Remove the padding we set earlier */
              .masthead,
              .marketing,
              .footer {
                padding-left: 0;
                padding-right: 0;
              }
            }
    /* Colors */
    .green {color:#5cb85c;}
    .orange {color:#f0ad4e;}
    .red {color:#d9534f;}
    /* Icons */
    .icon-pull-center {
      margin-left: -50px;
      margin-top: 10px;
      vertical-align: text-top;}
    .big-icons {color:#A9A9A9;
    font-size: 110px;}
    /* Layout */
    .section {
      background:#e6e6e6;
      padding: 30px 0;}
    .jumbotron {
        font-size: 21px;
        font-weight: 200;
        line-height: 2.1428571435;
        color: inherit;
        padding: 10px 60px;
        }
    </style>
      <script type="text/javascript">
      function loadDomain()
      {
        var display = document.getElementById("display-domain");
        display.innerHTML = document.domain;
      }
      </script>
  </head>

  <body onload="javascript:loadDomain();">

    <div class="container">
      <!-- Jumbotron -->
      <div class="jumbotron">
        <h1><i class="icon-ban-circle red"></i> 401 Unauthorized </h1>
        <p class="lead">Sorry! Your access token is don't valid or expired on <em><span id="display-domain"></span></em>.</p>
          <p><a href="/users-logout" class="btn btn-default btn-lg green">Login as Administrator</a>
    </div>
    </div>
    <div class="container">
      <div class="body-content">

      </div><!-- /.body-content -->
    </div><!-- /.body-container -->

      <!-- Site footer -->
      <div class="footer">
        <p></p>
      </div>

    <!-- /container -->

  </body>
</html>