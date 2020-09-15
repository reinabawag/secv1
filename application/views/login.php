
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MIS User Manager System By: Rein</title>

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/icon/apple-icon-57x57.png') ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('assets/icon/apple-icon-60x60.png') ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/icon/apple-icon-72x72.png') ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/icon/apple-icon-76x76.png') ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/icon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('assets/icon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/icon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('assets/icon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/icon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url('assets/icon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('assets/icon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('assets/icon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/icon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?php echo base_url('assets/icon/manifest.json') ?>">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo base_url('assets/icon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#ffffff">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/toastr.min.css'); ?>">

    <style>
      .pace {
        -webkit-pointer-events: none;
        pointer-events: none;

        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      .pace-inactive {
        display: none;
      }

      .pace .pace-progress {
        background: #80ff80;
        position: fixed;
        z-index: 2000;
        top: 0;
        right: 100%;
        width: 100%;
        height: 2px;
      }

      body {
        padding-bottom: 40px;
      }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="<?php echo base_url('assets/js/jquery-1.12.4.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo base_url('assets/js/pace.min.js') ?>"></script>

  </head>

  <body>

    <div class="container">
    <p align="center" style="margin-top: 10px;">
        <a href="<?php echo site_url(); ?>"><img src="<?php echo base_url('assets/img/amwire_logo.jpg') ?>" width="150"></a>
    </p>
    <div class="row">
          <div class="col-md-4 col-md-offset-4" style="margin-top: 5%">
              <div class="panel panel-primary">
                  <div class="panel-heading"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Secv1</h4></div>
                  <div class="panel-body">
                      <form id="form" class="form-signin" action="<?php echo site_url('login/get_user') ?>" method="POST">
                          <div class="form-group">
                              <label for="username">Username</label>
                              <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus>
                          </div>
                          <div class="form-group">
                              <label for="password">Password</label>
                              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
                          </div>
                          <div class="loader" style="margin-top: 10px; display: none;">
                              <img src="<?php echo base_url('assets/img/hourglass.gif') ?>" width="20px">
                              loading please wait
                          </div>
                          <p>
                            <span id="auth-msg"></span>
                          </p>
                          <button class="btn btn-primary btn-block">Login</button>
                          <p id="msg" align="center" style="margin-top: 10px"></p>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    </div> <!-- /container -->

    <script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/toastr.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.form.min.js') ?>"></script>
    <script>
      $(document).ready(function() {
        $('.form-signin').ajaxForm({
          dataType: 'json',
          beforeSubmit: function() {
            $('#auth-msg').empty();
            $('.loader').show();
          },
          success: function(data) {
            $('.loader').hide();
            console.log(data);
            if (data.status == true) {
              window.location=data.url;
            } else if (data.status == false) {
              $('span#auth-msg').text(data.msg);
            } else {
              $('span#auth-msg').html(data.error);
            }
          },
        })
      })
    </script>
  </body>
</html>
