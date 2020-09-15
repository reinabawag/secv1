 <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo site_url() ?>">AMWIRE SECURITY</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="<?php echo uri_string('index') == 'index' ? 'class' : ''; echo uri_string('index') ?>"><a href="<?php echo site_url() ?>"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
            <!-- <li class="<?php echo isset($active) ? $active : '' ?>"><a href="<?php echo site_url('user') ?>"><span class="glyphicon glyphicon-user"></span>&nbsp;Accounts</a></li> -->
            <li ><a href="<?php echo site_url('system') ?>"><span class="glyphicon glyphicon-tasks"></span>&nbsp;System</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Options <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li> -->
                <li><a href="<?php echo site_url('pages/logout') ?>">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>