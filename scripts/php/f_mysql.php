<?/* Защита от "прямого" вызова скрипта */ if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}

/* ФУНКЦИИ ЗАПРОСОВ К БД */

function db_row ($query, $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}	
	if (isset($ret) && !empty($ret)) return mysql_fetch_assoc($ret);
	else return false;
}

function db_great_row ($query, $query2 = "", $name_id = "", $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}
	if (isset($ret) && !empty($ret)) while ($row = mysql_fetch_assoc($ret)) {
		if (rand(0,1)==1) {
			$query2 .= $row[$name_id];
			if (!empty($query2) && !empty($name_id)) $ret = mysql_query($query2);
			$err = mysql_error();
			if (!empty($err) || $need_log) {
				$query2=addslashes(stripslashes(trim($query2)));
				$err=addslashes(stripslashes(trim($err)));
				$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query2', `log_error`='$err'");
			}
			return $row;
		}
		$last_row = $row;
	}
	return $last_row;
}

function db_array ($query, $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	$res = Array();
	if (isset($ret) && !empty($ret)) while ($row = mysql_fetch_assoc($ret)) {
		$res[] = $row;
	}
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}
	return $res;
}

function db_result ($query, $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}
	if (isset($ret) && !empty($ret)) {
		$ret2 = @mysql_result($ret, 0);
		return $ret2;
	}
	else return false;
}

function db_request ($query, $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}
	if (isset($ret) && !empty($ret)) return true;
	else return false;
}

function db_insert ($query, $need_log = false) {
	$ret = mysql_query($query);
	$err = mysql_error();
	$id = mysql_insert_id();
	if (!empty($err) || $need_log) {
		$query=addslashes(stripslashes(trim($query)));
		$err=addslashes(stripslashes(trim($err)));
		$ret2 = mysql_query("INSERT INTO `lim_log_error` SET `log_type`='mysql', `log_data`='$query', `log_error`='$err'");
	}
	if (isset($ret) && !empty($ret)) return $id;
	else return false;
}

/* ПРИМЕРЫ ИСПОЛЬЗОВАНИЯ

// Извлечение массива строк:

	$arr = db_array ("SELECT * FROM `vk_projects` WHERE `pj_id`>2");
	foreach ($arr as $row) {
		...
	}

// Принудительный LOG запроса (true вторым параметром):

	$arr = db_result ("SELECT COUNT(*) FROM `vk_projects` WHERE `pj_id`>0", true);
	
// Выбор одной случайной строки, приоритет первым строкам
	второй параметр - это запрос, который выполняется в случае успешно выбранной строки
	третий параметр - имя id, нужный для подстановки в запрос 2

	$data = db_great_row("SELECT * FROM `vk_options` WHERE `op_type`=1 AND `op_task`=1 ORDER BY `op_service_cnt` ASC",
							"UPDATE `vk_options` SET `op_service_cnt`=`op_service_cnt`+1 WHERE `op_id`=", "op_id")
*/

?>