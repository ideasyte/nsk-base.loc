<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}

$answ=mysql_query("SELECT * FROM `lim_options` WHERE `id`=1");
if (isset($answ)) $options = mysql_fetch_assoc($answ);
$answ_page = mysql_query("SELECT * FROM `lim_pages` WHERE `page_href`='$self'");
if (isset($answ_page)) $page = mysql_fetch_assoc($answ_page);

// Хелперы

// Favicon's
function fa ($class, $toString = false) {
	$output = '<i class="fa fa-' . $class . '"></i>';
	if ($toString) return $output;
	else echo $output;
}

function f_input($params_str, $toString = false, $sql_query = '') {
	global $el_data, $user_info;
	$params = explode(' ', $params_str);
	if (count($params) == 0) return;
	$output = ''; // переменная, в которую выводим данные
	
	// Defaults
	$type = 'text';
	$class = '';
	$access = Array();
	$hidden = Array();
	
	foreach ($params as $p) {
		$t = substr($p, 0, 1);
		switch ($t) {
			default: 	$name = $p;							break;	// user_name (выражение без префикса) будет считаться параметром name
			case '~':	$type = substr($p, 1); 				break;	// ~hidden тип инпута. По-умолчанию, text
			case '.':	if ($p == '.') $class .= ' form-control';	// . подставляет 'form-control' (наиболее часто употребляемый класс)
						else $class .= ' ' . substr($p, 1); break;	// .my_class добавляет класс (можно добавлять сколько угодно классов)
			case '#':	$id = substr($p, 1); 				break;	// #my_id добавляет id. Указание пустого # добавит id = name
			case '?':	$placeholder = substr($p, 1); 		break;	// ?Имя_пользователя добавляет подсказку (символы _ заменятся на пробел)
			case '=':	$value = substr($p, 1); 			break;	// =5 значение инпута. Если не указано - берется по дефолту из $el_data[$name]
			
			/* Варианты для поля выбора select */
			
			case '{':	$variants = explode('|', substr(substr($p, 1), 0, strlen($p)-2)); 	// {вариант_1|вариант_2} перечисление вариантов
						// в случае, когда варианты поступают из mysql запроса $sql_query, {user_id|user_fio} данный параметр регулирует,
						// что берется за value, а что за содержимое тегов <option> внутри select-а
						break;
			
			/* 	Права доступа к полю
				@superadmin - роль юзера, которому разрешено редактирование поля
				/user - роль юзера, которому поле вообще не выводится. Перечисление ролей может быть любой длины: 
				
				@superadmin @admin /user - 	суперадмин и админ видят и редактируют, 
											manager (не указан) видит, но редактировать не может,
											юзеру же поле вообще скрыто												
			*/
			case '@':	$access[] = substr($p, 1);			break;
			case '/':	$hidden[] = substr($p, 1);			break;
			
			/* 
				Addons. Если используются favicon's, синтаксис: >fa-user или <fa-clock
				В случае вставок произвольного текста в аддон, к примеру, +7 для ввода номера телефона, синтаксис <+7 
			*/
			case '<':	if (substr($p, 1, 3) == 'fa-') 
						$addon_left = fa ( substr($p, 4), true );
						else $addon_left = substr($p, 1);	break;
			case '>':	if (substr($p, 1, 3) == 'fa-')
						$addon_right = fa ( substr($p, 4), true );
						else $addon_right = substr($p, 1);	break;
		}
	}
	if (array_search($user_info['user_role'], $hidden) !== false) return; // Поле скрыто для текущей роли пользователя
	if (count($access) > 0 && array_search($user_info['user_role'], $access) === false) $disabled = ' disabled';
	else $disabled = '';
	
	if (!empty($class)) $class = substr($class, 1); // убираем пробел в начале строки с классами
	if (empty($id) && $type != 'radio') $id = $name;
	if (empty($value) && !empty($name) && $type != 'radio') $value = $el_data[$name];
	$placeholder = str_replace('_', ' ', $placeholder);
	if (!empty($variants) && empty($sql_query) && count($variants)) foreach ($variants as $k => $v) $variants[$k] = str_replace('_', ' ', $variants[$k]);
	
	switch ($type) {
		case 'text': 
			if (!empty($addon_left) || !empty($addon_right)) $output .= '<div class="input-group">';
				if (!empty($addon_left)) $output .= '<span class="input-group-addon">' . $addon_left . '</span>';
				$output .= "<input type=\"text\" name=\"$name\" id=\"$id\" class=\"$class\" placeholder=\"$placeholder\" value=\"$value\"$disabled>";
				if (!empty($addon_right)) $output .= '<span class="input-group-addon">' . $addon_right . '</span>';
			if (!empty($addon_left) || !empty($addon_right)) $output .= '</div>';
			break;
        case 'checkbox':
            $checked = '';
            if ($value == true || $el_data[$name] == true) $checked = ' checked';
            $value = 1;
            $output .= "<div class=\"checkbox\"><label><input type=\"$type\" name=\"$name\" id=\"$id\" class=\"$class\" value=\"$value\"$checked$disabled> $placeholder</label></div>";
            break;
		case 'radio':
			$checked = '';
    		if (!empty($name) && $value == $el_data[$name]) $checked = ' checked';
            if (!empty($value) && !empty($el_data[$value])) $checked = ' checked';
			$output .= "<div class=\"radio\"><label><input type=\"$type\" name=\"$name\" id=\"$id\" class=\"$class\" value=\"$value\"$checked$disabled> $placeholder</label></div>";
			break;
		case 'select':
			if (!empty($sql_query) && count($variants) == 2) {
				$key = $variants[0];
				$val = $variants[1];
				$variants = db_array($sql_query);
				$mysql = true;
			}
			$output .= "<select name=\"$name\" id=\"$id\" class=\"$class\"$disabled>";
				$row = 0;
				foreach ($variants as $variant) {
					$cur_value = ($mysql) ? $variant[$key] : $row;
					$cur_title = ($mysql) ? $variant[$val] : $variant;
					$selected = ($cur_value == $value) ? ' selected' : '';
					$output .= "<option value=\"$cur_value\"$selected>$cur_title</option>";
					$row ++;
				}
			$output .= "</select>";
			break;
		case 'textarea': 
			$output .= "<textarea name=\"$name\" id=\"$id\" class=\"$class\" placeholder=\"$placeholder\"$disabled>$value</textarea>";
			break;			
		
	}

	if ($toString) return $output;
	else echo $output;
}

?>
