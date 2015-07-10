<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

if (!empty($enter_user)) {header("Location: index.php"); exit;}
?><!DOCTYPE html>
<html>
<head>
	<? require MC_ROOT.'/pages/metrics_head.php'; ?>
	<link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />
</head>
<body class="login-page">
	<? require MC_ROOT.'/pages/metrics_body.php'; ?>
	
    <div class="login-box">
      <div class="login-logo">
        <a href="index.php"><b>Nsk</b>Base v1.0</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Авторизация</p>
		
		<? if (!empty($auth_error)) { ?>
			<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Ошибка!</strong><br /> <? echo $auth_error; ?>
			</div>
		<? } ?>			
		
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="login" placeholder="Логин" value="<? echo $login; ?>">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="pass" placeholder="Пароль">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember"> &nbsp; Запомнить меня
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Войти</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="#">Забыли пароль?</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->	
	
    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>

</body>
</html>