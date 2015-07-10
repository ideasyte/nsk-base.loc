<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}

// Переменные, которые индицируют вход в аккаунт (содержат id аккаунта)
$enter_admin=false;
$enter_user=false;
// Массивы, содержащие инфо о текущем пользователе
$admin_info=array();
$user_info=array();

$login = $_POST['login'];
$pass = $_POST['pass'];

$isadmin = intval($_POST['isadmin']);
$need_check_isadmin = true;
$register = intval($_POST['register']);
$passchange = intval($_POST['passchange']); // флаг смены пароля
$notify_change = intval($_POST['notify_change']); // флаг смены настроек уведомлений
$act = $_GET['act'];

// Данные для редактирования аккаунта
$edit = intval($_POST['edit']); // флаг редактирования
$user_id = intval($_POST['user_id']);
$user_activate = intval($_POST['user_activate']);
$user_login = $_POST['user_login'];
$user_pass = $_POST['user_pass'];
$user_pass2 = $_POST['user_pass2'];
$user_email = $_POST['user_email'];
$user_name = $_POST['user_name'];
$user_surname = $_POST['user_surname'];
$user_country = $_POST['user_country'];
$user_region = $_POST['user_region'];
$user_city = $_POST['user_city'];
$user_address = $_POST['user_address'];
$user_geolocation_id = $_POST['user_geolocation_id'];
$user_company_name = $_POST['user_company_name'];
$user_phone = $_POST['user_phone'];

if ( get_magic_quotes_gpc()) {
	$login=addslashes(stripslashes(trim($login)));
	$pass=addslashes(stripslashes(trim($pass)));
	$user_login=addslashes(stripslashes(trim($user_login)));
	$user_pass=addslashes(stripslashes(trim($user_pass)));
	$user_pass2=addslashes(stripslashes(trim($user_pass2)));
	$user_email=addslashes(stripslashes(trim($user_email)));
	$user_name=addslashes(stripslashes(trim($user_name)));
	$user_surname=addslashes(stripslashes(trim($user_surname)));
	$user_country=addslashes(stripslashes(trim($user_country)));
	$user_region=addslashes(stripslashes(trim($user_region)));
	$user_city=addslashes(stripslashes(trim($user_city)));
	$user_address=addslashes(stripslashes(trim($user_address)));
	$user_geolocation_id=addslashes(stripslashes(trim($user_geolocation_id)));
	$user_company_name=addslashes(stripslashes(trim($user_company_name)));
	$user_phone=addslashes(stripslashes(trim($user_phone)));	
}
$user_phone = preg_replace('/[^\d]+/', '', $user_phone);

$log="";
$auth_error="";
$auth_success="";

$ses_log = "";
$ses_pass = "";
$ses_ip = "";

// Функция поиска аккаунта, соответствующего паре [логин-пароль] среди администраторов и пользователей
function search_account($acc_login, $acc_pass) {
	global $admin_info, $user_info, $enter_admin, $enter_user, $log, $isadmin, $need_check_isadmin, $auth_error;

	$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_login` ='$acc_login' AND `user_pass` ='$acc_pass'");
	if (isset($answ_user)) $user_info = mysql_fetch_assoc($answ_user);
	if (!empty($user_info['user_id'])) {
		if (empty($isadmin)) {
			$log .= "Найден пользователь!<br />";
			if (empty($user_info['user_activate'])) {
				$enter_user = $user_info['user_id'];
				return true;
			} else $auth_error = 'К сожалению, Ваш аккаунт еще не активирован (или Вы подали запрос на восстановление пароля)!<br />Пожалуйста, проверьте почту и активируйте Ваш аккаунт, перейдя по ссылке, которую мы прислали Вам в письме<br />';
		} else $auth_error = 'Запрещено вводить учетную запись пользователя в панели ввода учетной записи администратора!<br />';
	} else {
		$answ_admin = mysql_query("SELECT * FROM `lim_admins` WHERE `admin_login` ='$acc_login' AND `admin_pass` ='$acc_pass'");
		if (isset($answ_admin)) $admin_info = mysql_fetch_assoc($answ_admin);
		if (!empty($admin_info['admin_id'])) {	
			if (!empty($isadmin) || $need_check_isadmin==false) {
				$log .= "Найден админ!<br/>";
				$enter_admin = $admin_info['admin_id'];
				return true;
			} // else $auth_error = 'Запрещено вводить учетную запись администратора в панели ввода учетной записи пользователя!<br />';
		}
	}
	$user_info = array();
	$admin_info = array();
	return false;
}

function check_account_data () {
	global $user_login, $user_pass, $user_pass2, $user_email, $user_name, $user_surname, $user_city, $user_phone, $auth_error, $log;
	/* Проверка на заполнение всех обязательных полей: ненужное закоментировано */
	if (empty($user_name)) $auth_error.="Не указано имя!<br />";
	//if (empty($user_surname)) $auth_error.="Не указана фамилия!<br />";
	//if (empty($user_login)) $auth_error.="Не указан логин!<br />";
	//if (empty($user_city)) $auth_error.="Не указана город!<br />";
	//if (empty($user_phone)) $auth_error.="Не указан логин!<br />";
	if(!empty($user_phone) && !preg_match("|^[0-9]{10}$|i", $user_phone)) $auth_error.="Ошибка в номере (должно быть 10 цифр без пробелов)!<br />";
	if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $user_email)) $auth_error.="Не указан или неверно указан E-mail<br />";
	
	//if (empty($user_pass)) $auth_error.="Не указан пароль!<br />";	
	//else if ($user_pass != $user_pass2) $auth_error.="Пароли не совпадают!<br />";	
	$log.= "$user_pass ?= $user_pass2<br />";
}

$log.="запускаем сессию<br />";
session_start();
$ses_user_id = $_SESSION['user_id'];
$ses_admin_id = $_SESSION['admin_id'];
$ses_ip = $_SESSION['ip'];
$log.="ses_user_id = $ses_user_id , ses_admin_id  = $ses_admin_id , ses_ip = $ses_ip<br />";


if (!empty($ses_user_id) || !empty($ses_admin_id)) { // в сессии присутствует id админа или пользователя
		$log.="в сессии присутствует логин<br />";
		if (/*$ses_ip==$ip && */$act!="quit") { // ip не изменился, попытка выхода не предпринималась
			// ищем в БД соответствующий аккаунт
			if (!empty($ses_user_id)) {
				$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_id` ='$ses_user_id'");
				if (isset($answ_user)) {
					$user_info = mysql_fetch_assoc($answ_user);
					$enter_user = $ses_user_id;
				}
			} else if (!empty($ses_admin_id)) {
				$answ_admin = mysql_query("SELECT * FROM `lim_admins` WHERE `admin_id` ='$ses_admin_id'");
				if (isset($answ_admin)) {
					$admin_info = mysql_fetch_assoc($answ_admin);
					$enter_admin = $ses_admin_id;
				}			
			}
		} else {
			$log.="сессия разорвана (ip или выход)<br />";
			/*if (isset($_REQUEST[session_name()]))*/ session_destroy();
			//header("Location: login.php");
			setcookie('auth_hash', '', -1);
		}
} else if (!empty($_COOKIE['auth_hash'])) {
	$log .= "есть кукис-запись с предыдущей авторизации 'Запомнить меня' ".$_COOKIE['auth_hash']."<br />";
	$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_auth_hash` ='".$_COOKIE['auth_hash']."'");
	if (isset($answ_user)) {
		$user_info = mysql_fetch_assoc($answ_user);
		$enter_user = $user_info['user_id'];
		$_SESSION['user_id'] = $enter_user;
		$_SESSION['ip'] = $ip;
	}
}

if ($act == "register") {
	$log.= "поступили данные для регистрации нового аккаунта<br />";
	check_account_data ();
	/* Если в качестве логина выступает E-mail */
	$user_login = $user_email;
	if (empty($auth_error)) {
		// проверяем, не было ли уже зарегистрировано такого e-mail:
		$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_email`='$user_email'");
		if (isset($answ_user)) $user = mysql_fetch_assoc($answ_user);						
		if (empty($user['user_id'])) {
			// Генерация случайного хэша
			$user_activate = sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536));
			// Генерация случайного пароля
			$user_pass = sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536));
			$answ_user = mysql_query("INSERT INTO `lim_users` SET `user_login`='$user_login', `user_pass`='$user_pass', `user_email`='$user_email', `user_name`='$user_name', `user_surname`='$user_surname', `user_city`='$user_city', `user_phone`='$user_phone', `user_activate`='$user_activate'");
			$userid = mysql_insert_id();
			// Отправляем e-mail
			$title = "Регистрация $syte_name"; 
			$headers = "From: $syte_name<$syte_email@$syte_domain>\r\nContent-type: text/plain; charset=utf-8\r\n";
			$mess = "Доброго времени суток!\r\nВаш E-mail был указан при регистрации личного кабинета на сайте $syte_domain\r\nЕсли вы не указывали никаких данных на этом сайте, просто проигнорируйте это письмо\r\nДля активации личного кабинета кликните на ссылку:\r\nhttp://$syte_domain/activate.php?user_id=$userid&user_activate=$user_activate";
			$mess .= "\r\nВаш пароль доступа: $user_pass\r\n\r\nВы можете изменить пароль на любой удобный Вам в настройках аккаунта";
			mail($user_email, $title, $mess, $headers); 
			$auth_success.="<strong>Регистрация прошла успешно!</strong> На Ваш E-mail отправлено письмо cо ссылкой для активации личного кабинета";
		} else $auth_error.="Данный E-mail уже был использован для регистрации на нашем сайте!<br />Если Вы забыли пароль, пожалуйста, воспользуйтесь формой восстановления пароля";
	} 
} else if ($act == "recovery") {
	$log.= "режим восстановления пароля<br />";
	if (!empty($user_id)) {
		// Получили данные для смены пароля
		$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_id`='$user_id'");
		if (isset($answ_user)) $user = mysql_fetch_assoc($answ_user);						
		if (empty($user['user_activate']) || empty($user_activate) || $user_activate != $user['user_activate']) {
			if (!empty($user_pass)) {
				$answ_user = mysql_query("UPDATE `lim_users` SET `user_pass`='$user_pass', `user_activate`=0 WHERE `user_id`='".$user['user_id']."'");
				$auth_success.="<strong>Пароль успешно изменен!</strong> Вы можете войти на сайт с новым паролем";
			} else $auth_error="Пожалуйста, введите пароль!";
		} else $auth_error.= "Ошибка кода активации! Проверьте корректность вставки ссылки из E-mail с инструкцией по восстановлению!";
	} else if(!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $user_email)) $auth_error.="Не указан или неверно указан E-mail<br />";
	else {
		// проверяем, не было ли уже зарегистрировано такого e-mail:
		$answ_user = mysql_query("SELECT * FROM `lim_users` WHERE `user_email`='$user_email'");
		if (isset($answ_user)) $user = mysql_fetch_assoc($answ_user);						
		if (!empty($user['user_id'])) {
			// Генерация случайного хэша
			$user_activate = sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536));
			$answ_user = mysql_query("UPDATE `lim_users` SET `user_activate`='$user_activate' WHERE `user_id`='".$user['user_id']."'");
			// Отправляем e-mail
			$title = "Восстановление доступа к $syte_name"; 
			$headers = "From: $syte_name<$syte_email@$syte_domain>\r\nContent-type: text/plain; charset=utf-8\r\n";
			$mess = "Доброго времени суток!\r\nВы заполнили форму восстановления пароля на сайте $syte_domain\r\n\r\nДля установки нового пароля перейдите по этой ссылке:\r\nhttp://$syte_domain/pass_recovery.php?user_id=".$user['user_id']."&user_activate=$user_activate";
			mail($user_email, $title, $mess, $headers); 
			$auth_success.="<strong>Письмо успешно отправлено.</strong> Проверьте свой E-mail и следуйте инструкциям для восстановления пароля";
		} else $auth_error.="К сожалению, мы не можем найти ни одной учетной записи, связанной с данным E-mail!";
	}
} else if ($act == "edit") {
	$log.= "Режим редактирования данных аккаунта<br />";
	if (!empty($enter_user)) {
		if (!empty($passchange)) {
			// Для смены пароля нужно ввести старый пароль. Проверяем его правильность
			if ($pass == $user_info['user_pass']) {
				if (!empty($user_pass)) {
					$answ_user = mysql_query("UPDATE `lim_users` SET `user_pass`='$user_pass' WHERE `user_id`='$enter_user'");
					$auth_success = "Пароль успешно изменен!";
				} else $auth_error.="Не был введен новый пароль!";
			} else $auth_error.="Старый пароль введен неверно!";
		} else if (!empty($notify_change)) {
			// Изменение настроек уведомлений
			$user_notif_email = $_POST['user_notif_email'];
			if ($user_notif_email == 'on') $user_notif_email = 1; else $user_notif_email = 0;
			$user_notif_sms = $_POST['user_notif_sms'];
			if ($user_notif_sms == 'on') $user_notif_sms = 1; else $user_notif_sms = 0;
			db_request("UPDATE `lim_users` SET `user_notif_email`='$user_notif_email', `user_notif_sms`='$user_notif_sms' WHERE `user_id`='$enter_user'");
			$auth_success = "Настройки уведомлений успешно отредактированы!";
		} else {
			if (empty($user_name)) $auth_error.="Не указано Имя!<br />";
			if (empty($user_login)) $auth_error.="Не указан Логин!<br />";
			if (empty($user_email)) $auth_error.="Не указан Email!<br />";
			if (empty($user_phone)) $auth_error.="Не указан номер (должно быть 10 цифр без пробелов)!<br />";
			else if (!preg_match("|^[0-9]{10}$|i", $user_phone)) $auth_error.="Некорректный номер телефона(должно быть 10 цифр без пробелов)!<br />";
			if (empty($auth_error)) {
				db_request("UPDATE `lim_users` SET `user_name`='$user_name', `user_login`='$user_login', `user_email`='$user_email', `user_phone`='$user_phone' WHERE `user_id`='$enter_user'");
				$auth_success = "Информация успешно отредактирована!";
			}
		}
		// Перезапрашиваем заново из базы результат
		$user_info = db_row("SELECT * FROM `lim_users` WHERE `user_id` ='$enter_user'");
	} else $auth_error.="Редактирование данных аккаунта отклонено, т.к. отсутствует авторизация";
} else if (!empty($login) || !empty($pass)) {
	$log.= "Режим авторизации<br />";
	if (empty($login) && !empty($pass)) $auth_error="Пожалуйста, введите логин!";
	else if (!empty($login) && empty($pass)) $auth_error="Пожалуйста, введите пароль!";	
	if (empty($auth_error) && search_account($login, $pass)) {
		$log.="соответствие найдено<br />";
		/*session_start();*/
		if (!empty($enter_user)) {
			$_SESSION['user_id'] = $enter_user;
			//Запоминание юзера в случае установленной опции 'Запомнить меня'
			if ($_POST['remember'] == "on") {
				$auth_hash = sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536)) . sprintf( '%04x', rand(0, 65536));
				setcookie('auth_hash', $auth_hash, time()+2592000, '/', false);
				$answ_user = mysql_query("UPDATE `lim_users` SET `user_auth_hash`='$auth_hash' WHERE `user_id` ='$enter_user'");
			}			
		}
		if (!empty($enter_admin)) $_SESSION['admin_id'] = $enter_admin;
		$_SESSION['ip'] = $ip;
	} else if (empty($auth_error)) $auth_error.="К сожалению, авторизация не удалась. Проверьте правильность введенного логина и пароля!<br />";
} else $log.= "Сессия пуста, не предпринимается никаких действий<br />";


function avatar_echo($avatar) {
    // Вывод аватарки в зависимости от типа
    if (substr($avatar, 0, 4) == 'http') {
        $file_headers = @get_headers($avatar);
        if ($file_headers[0] == 'HTTP/1.0 404 Not Found') return '/pictures/avatars/avatar-empty.jpg';
        else return $avatar;
    } else {
        if (empty($avatar) || !file_exists(MC_ROOT . '/pictures/avatars/' . $avatar . '.jpg')) return '/pictures/avatars/avatar-empty.jpg';
        else return '/pictures/avatars/' . $avatar . '.jpg';
    }
}

$user_avatar = avatar_echo($user_info['user_avatar']);




?>
