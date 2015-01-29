<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title>Eazyticket Login Page</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}
  {{HTML::style('../../../assets/css/metro.css')}}
  {{HTML::style('assets/font-awesome/css/font-awesome.css')}}
  {{HTML::style('assets/css/style.css')}}
  <!-- {{HTML::style('css/font.css')}} -->
  {{HTML::style('css/main.css')}}
  <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login">
  <!-- BEGIN LOGO -->
  <div class="logo">
    <img src="../img/elite.png" alt="" /> 
  </div>
    @yield('content')
  <div class="copyright">
  </div>
  {{HTML::script('assets/js/jquery-1.8.3.min.js')}}
  {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}
  <!-- {{HTML::script('assets/uniform/jquery.uniform.min.js')}} -->
  {{HTML::script('assets/js/jquery.blockui.js')}}
  {{HTML::script('assets/jquery-validation/dist/jquery.validate.min.js')}}
  {{HTML::script('assets/js/app.js')}}
  <script>
    jQuery(document).ready(function() {     
      App.initLogin();
    });
  </script>
</body>
</html>