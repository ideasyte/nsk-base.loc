<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

$user_id = intval($_GET['user_id']);
$user_activate = $_GET['user_activate'];
if ( get_magic_quotes_gpc()) $user_activate = addslashes(stripslashes(trim($user_activate)));

if (!empty($user_id) && !empty($user_activate)) {
	$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_id` ='$user_id'");
	if (isset($answ_user)) $user_info = mysql_fetch_assoc($answ_user);
	if (!empty($user_info['user_id'])) {
		if ($user_activate == $user_info['user_activate'] && !empty($user_info['user_activate'])) {
			$answ_user = mysql_query("UPDATE `lim_users` SET `user_activate`=0 WHERE `user_id` ='$user_id'");
			if (isset($answ_user)) $activate_success = "Ваш личный кабинет успешно активирован!";
			else $activate_error = "Произошла ошибка активации";
		} else if (empty($user_info['user_activate'])) {
			$activate_error = "Данный аккаунт уже активирован Вами ранее";
		} else $activate_error = "Ошибка! Неверный код активации";
	} else $activate_error = "Ошибка! Не найден пользователь с таким id";
} else {header("Location: index.php"); exit;} 

?><!DOCTYPE html>
<html>
<head>
	<? require MC_ROOT.'/pages/metrics_head.php'; ?>
	<!-- CSS Page Style -->
    <link rel="stylesheet" href="assets/css/pages/log-reg-v3.css">	
</head>
<body>
<div class="wrapper">
	<? require MC_ROOT.'/pages/metrics_body.php'; ?>
	<? 	require MC_ROOT.'/pages/des_navbar.php'; ?>
	
    <!--=== Breadcrumbs v4 ===-->
    <div class="breadcrumbs-v4">
        <div class="container">
            <span class="page-name">Активация E-mail адреса пользователя</span>
            <h1>База данных <span class="shop-green">салонов красоты</span></h1>
            <ul class="breadcrumb-v4-in">
                <li><a href="index.php">Главная страница</a></li>
                <li class="active">Активация</li>
            </ul>
        </div><!--/end container-->
    </div> 
    <!--=== End Breadcrumbs v4 ===-->

    <!--=== Login ===-->
    <div class="log-reg-v3 content-md">
        <div class="container">
		
				<? if (!empty($activate_success)) { ?>
					<div class="alert alert-success">
					  <strong>Поздравляем!</strong> <? echo $activate_success; ?>
					  <span class="close"></span>
					</div>
                    <div class="row">
						<div class="col-md-10 col-md-offset-1">
							<a href="login.php" class="btn-u btn-block">Войти на сайт</a>
						</div>
					</div>
				<? } else if (!empty($activate_error)) { ?>
					<div class="alert alert-error">
					  <strong>Ошибка!</strong> <? echo $activate_error; ?>
					  <span class="close"></span>
					</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<a href="index.php" class="btn-u btn-block">На главную</a>
						</div>
					</div>
				<? } else { ?>
					<div class="alert alert-warning">
					  <strong>Неизвестная ошибка!</strong> Пожалуйста, попробуйте еще раз, или свяжитесь с нашей техподдержкой.
					  <span class="close"></span>
					</div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<a href="index.php" class="btn-u btn-block">На главную</a>
						</div>
					</div>					
				<? } ?>
		
		</div><!--/end container-->
    </div>
    <!--=== End Login ===-->
	

<? 	require MC_ROOT.'/pages/des_footer.php'; ?>
</div>
<? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

<script>
    jQuery(document).ready(function() {
        App.init();
        //OwlCarousel.initOwlCarousel();
	});
</script>

</body>
</html>