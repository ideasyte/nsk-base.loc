<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}
?>
      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?=$user_avatar?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><? echo "{$user_info['user_name']} {$user_info['user_surname']}"; ?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> <? echo $user_info['user_role']; ?></a>
            </div>
          </div>

          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Поиск..."/>
              <span class="input-group-btn">
                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
		  
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">РАЗДЕЛЫ</li>
			
			<li<? if ($self == "index.php") echo ' class="active"'; ?>>
              <a href="index.php">
                <i class="fa fa-th"></i> <span>Главная</span>
              </a>
            </li>
			
<?	if ($user_info['user_role'] == 'superadmin' || $user_info['user_role'] == 'admin') { ?>
			<li<? if ($self == "users.php") echo ' class="active"'; ?>>
              <a href="users.php">
                <i class="fa fa-users"></i> <span>Пользователи</span>
              </a>
            </li>
<?	} ?>
			
            <li class="treeview<? 
				switch ($self) {
					case "objects.php":
					case "clients.php":
					case "objects_last.php":
					case "clients_last.php":
						echo ' active';
						break;
				} ?>">
              <a href="#">
                <i class="fa fa-credit-card"></i>
                <span>Продажа</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li<? if ($self == "objects.php") echo ' class="active"'; ?>>
					<a href="objects.php"><i class="fa fa-home"></i> Продавцы</a>
				</li>
                <li<? if ($self == "clients.php") echo ' class="active"'; ?>>
					<a href="clients.php"><i class="fa fa-shopping-cart"></i> Покупатели</a>
				</li>

                <? /*
				<li<? if ($self == "objects_last.php") echo ' class="active"'; ?>>
					<a href="objects_last.php"><i class="fa fa-shopping-cart"></i> Собственники
					<small class="label label-green pull-right"><i class="fa fa-exclamation-circle"></i> last</small></a>
				</li>
				<li<? if ($self == "clients_last.php") echo ' class="active"'; ?>>
					<a href="clients_last.php"><i class="fa fa-shopping-cart"></i> Клиенты
					<small class="label label-green pull-right"><i class="fa fa-exclamation-circle"></i> last</small></a>
				</li>
                */ ?>

				
              </ul>
            </li>

			<li class="treeview">
              <a href="#">
                <i class="fa fa-clock-o"></i>
                <span>Аренда</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
<? /*
              <ul class="treeview-menu">
                <li>
					<a href="#">
						<i class="fa fa-home"></i> Собственники
						<small class="label pull-right"><i class="fa fa-exclamation-circle"></i></small>
					</a>
				</li>
                <li>
					<a href="#">
						<i class="fa fa-shopping-cart"></i> Клиенты
						<small class="label pull-right"><i class="fa fa-exclamation-circle"></i></small>
					</a>
				</li>
              </ul> */ ?>
            </li>
			
			
			
			<li<? if ($self == "updates.php") echo ' class="active"'; ?>>
              <a href="updates.php">
                <i class="fa fa-info-circle"></i> <span>Обновления</span>
              </a>
            </li>
			
            <li>
              <a href="?act=quit">
                <i class="fa fa-sign-out"></i> <span>Выход</span>
              </a>
            </li>            

          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>