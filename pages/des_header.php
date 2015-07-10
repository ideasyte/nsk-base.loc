<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}
?>
      <header class="main-header">
        <a href="index.php" class="logo"><b>NSK</b>base</a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
<? 	if (empty($enter_user)) { ?>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="notifications-menu">
                <a href="login.php">
                  <i class="fa fa-power-off"></i> Войти
                </a>
              </li>
<? 	} else { ?>		
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?=$user_avatar?>" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><? echo $user_info['user_name']; ?> <i class="fa fa-chevron-down"></i></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?=$user_avatar?>" class="img-circle" alt="User Image" />
                    <p>
                      <? echo "{$user_info['user_name']} {$user_info['user_surname']}"; ?>
                      <small><? echo $user_info['user_role']; ?></small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
					<div class="col-xs-4 text-center">
                      <a href="index.php">Главная</a>
                    </div>				  
                    <div class="col-xs-4 text-center">
                      <a href="objects.php">Объекты</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="clients.php">Клиенты</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="profile.php" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Профиль</a>
                    </div>
                    <div class="pull-right">
                      <a href="?act=quit" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Выход</a>
                    </div>
                  </li>
                </ul>
              </li>
<?	} ?>
            </ul>
          </div>
        </nav>
      </header>