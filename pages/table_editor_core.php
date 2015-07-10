<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}

// Массив для входных данных из GET/POST после обработки
$data_in = Array();
// Обработка всех параметров
$in = array_merge($_GET, $_POST);
foreach ($in as $k => $v) {
	if (is_array($v)) {
		$data_in[$k] = Array();
		// В массиве разрешены только цифровые идентификаторы
		foreach ($v as $v_el) $data_in[$k][] = intval ($v_el);
	} else {
		$data_in[$k] = trim($v);
		if ( get_magic_quotes_gpc()) $data_in[$k] = addslashes(stripslashes($data_in[$k]));
	}
}

// Флаги необходимости подключения сторонних скриптов
$yandex_map_need = false;
$dadata_need = false;
$error_msg = '';
function add_error ($field_name, $error_type) {
	global $error_msg, $fields;
	switch ($error_type) {
		case 'empty': $err = "Не заполнено обязательное поле: " . $fields[$field_name]['desc']; break;
		case 'error': $err = "Проверьте правильность заполнения поля: " . $fields[$field_name]['desc']; break;
		case 'minlength': $err = "Слишком короткое значение поля '" . $fields[$field_name]['desc'] . "'! Вы должны ввести не менее " . $fields[$field_name]['minlength'] . " симв."; break;
		default: $err = $error_type; break;
	}
	$error_msg .= "$err<br />";
	$fields[$field_name]['error_msg'] .= "$err<br />";
	db_request("INSERT INTO `lim_log_error` SET `log_type`='EDITOR', `log_data`='$err', `log_error`=''");
}
// Генерация SQL-таблицы в режиме отладки
if ($data_in['act'] == 'sql') {
	$fields_query_arr = Array();
	$fields_query_arr[] = "`$row_id` int(11) NOT NULL AUTO_INCREMENT";
	$timestamp_exists = false;
	foreach ($fields as $f_name => $f_options) {
		$f_tag = '';
		if (!empty($f_options['maxlength']) && $f_options['maxlength'] < 256) $maxlength = $f_options['maxlength'];
		else $maxlength = 255;
		switch ($f_options['type']) {
			case 'text':
			case 'hidden':
				if ($f_options['tag'] == 'textarea') $f_tag = 'text';
				else $f_tag = "varchar($maxlength)";
				break;
			case 'digit': 
				if (!empty($f_options['variants']) && count($f_options['variants']) == 2) $f_tag = 'int(1)';
				else $f_tag = 'int(11)';
				break;
			case 'email':
			case 'phone': $f_tag = 'varchar(100)'; break;
			case 'timestamp': $f_tag = 'timestamp'; break;
		}
		if (empty($f_tag)) continue;
		$fields_query_el = "`$f_name` $f_tag NOT NULL";
		if ($f_options['type'] == 'timestamp') {
			if (!$timestamp_exists) { $fields_query_el .= " DEFAULT CURRENT_TIMESTAMP"; $timestamp_exists = true; }
			else $fields_query_el .= " DEFAULT '0000-00-00 00:00:00'";
		}
		$fields_query_arr[] = $fields_query_el;
	}
	$fields_query_arr[] = "PRIMARY KEY (`$row_id`)";
	echo "<textarea style=\"width:100%\" rows=\"50\">CREATE TABLE IF NOT EXISTS `$table` (\r\n";
	echo implode (",\r\n", $fields_query_arr);
	echo "\r\n) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1</textarea>";
	exit();
}


// Действия
if ($data_in['act'] == 'del') {
	// Удаление элемента
	if ( db_request("DELETE FROM `$table` WHERE `$row_id`='".$data_in['id']."' LIMIT 1") ) { header("Location: $self?msg=3"); exit; }
	else $error_msg .= $messages['delete_error'] . '<br />';
} else if ($data_in['act'] == 'add') {
	// Обработка входных данных согласно свойствам полей
	foreach ($fields as $f_name => $f_options) {
		if ($f_options['type'] == 'digit') $data_in[$f_name] = intval ($data_in[$f_name]);
		if (empty($data_in[$f_name])) {
			// Если это обязательное поле - высвечиваем ошибку
			if (!empty($f_options['required'])) add_error ($f_name, 'empty');
			continue;
		}
		// Проверка правильности заполнения определенных типов полей
		switch ($f_options['type']) {
			case 'text': 
				if (!empty($f_options['minlength']) && strlen($data_in[$f_name]) < $f_options['minlength']) add_error ($f_name, 'minlength');
				break;
			case 'email':
				if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $data_in[$f_name])) add_error ($f_name, 'error');
				break;
			case 'phone':
				if (!preg_match("|^[0-9]{10}$|i", $data_in[$f_name])) add_error ($f_name, 'error');
				break;
			case 'digit':
				if (!empty($f_options['minvalue']) && $data_in[$f_name] < $f_options['minvalue']) add_error ($f_name, 'minvalue');
				if (!empty($f_options['maxvalue']) && $data_in[$f_name] > $f_options['maxvalue']) add_error ($f_name, 'maxvalue');
				// Если указаны варианты, то значение должно быть выбрано из их числа
				if (!empty($f_options['variants']) && !isset ($f_options['variants'][$data_in[$f_name]])) add_error ($f_name, 'error');
				break;
		}
	}
	// При отсутствии ошибок производим запись в БД
	if (empty($error_msg)) {	
		// Часть запроса, отвечающая за сохранение полей
		$fields_query_arr = Array();
		$timestamp_exists = false;
		foreach ($fields as $f_name => $f_options) {
			if ($f_options['tag'] == 'list' || $f_options['tag'] == 'checkboxes') continue;
			if ($f_options['type'] == 'timestamp' && !$timestamp_exists) {$timestamp_exists = true; continue;}
			$fields_query_arr[] = "`$f_name`='" . $data_in[$f_name] . "'";
		}
		if (empty($data_in['id'])) {
			// Добавление новой записи
			if ( db_request("INSERT INTO `$table` SET " . implode(", ", $fields_query_arr)) ) {
				$header_msg = 1;
				action_after_insert();
			}
		} else {
			// Редактирование существующей записи
			if ( db_request("UPDATE `$table` SET " . implode(", ", $fields_query_arr) . " 
							WHERE `$row_id`='" . $data_in['id'] . "' LIMIT 1") ) {
				$header_msg = 2;
				action_after_edit();
			}
		}
		if (!empty($header_msg)) {header("Location: $self?msg=$header_msg"); exit;}
		else $error_msg .= $messages['save_error'] . '<br />';
	}
}
if (!empty($data_in['id'])) {
	// Подгрузка информации о редактируемом элементе
	$el_data = db_row ("SELECT * FROM `$table` WHERE `$row_id`='".$data_in['id']."' LIMIT 1");
	$data_in = array_merge($data_in, $el_data);
} else if (!empty($data_in['msg'])) {
	// Высвечивание сообщение об успешности предыдущего действия по переадресации на список элементов
	switch ($data_in['msg']) {
		case 1: $success_msg = $messages['create_success']; break;
		case 2: $success_msg = $messages['save_success']; break;
		case 3: $success_msg = $messages['delete_success']; break;
	}
}
foreach ($fields as $f_options) {
	if ($f_options['tag'] == 'picture') $jcrop_need = true;
	if (!empty($f_options['dadata']['type'])) $dadata_need = true;
	if (!empty($f_options['dadata']['yandex_map'])) $yandex_map_need = true;
}

?><!DOCTYPE html>
<html>
<head>
	<? require MC_ROOT.'/pages/metrics_head.php'; ?>
<?	if ($dadata_need) { ?>
	<link href="https://dadata.ru/static/css/lib/suggestions-15.1.css" type="text/css" rel="stylesheet" />
<?	} ?>
<?	if ($jcrop_need) { ?>
	<link rel="stylesheet" href="plugins/jcrop/css/jquery.Jcrop.css">
<?	} ?>
</head>
<body class="skin-blue">
<? require MC_ROOT.'/pages/metrics_body.php'; ?>
<? if ($jcrop_need) require MC_ROOT.'/pages/des_modal_crop.php'; ?>
<div class="wrapper">
	<? require MC_ROOT.'/pages/des_header.php'; ?>
	<? require MC_ROOT.'/pages/des_sidebar.php'; ?>
	
	<!-- Right side column. Contains the navbar and content of the page -->
	<div class="content-wrapper">
		
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <? echo $messages['page_name']; ?>
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active"><? echo $messages['page_name']; ?></li>
          </ol>
		  
		<!-- Уведомления -->
		<?	if (!empty($success_msg)) { ?>
			<div class="alert alert-success mtop-20">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <? echo $success_msg; ?>
			</div>			
		<?	}
			if (!empty($error_msg)) { ?>
				<div class="alert alert-danger mtop-20">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Ошибка!</strong><br /><? echo $error_msg; ?>
				</div>
		<?	} ?>		  
		  
        </section>
	
		

        <!-- Main content -->
        <section class="content">
			<div class="panel panel-default">
<? if ($act=="add" || $act=="new" || $act=="edit" || !empty($data_in['id'])) { ?>					
			
				
					<div class="panel-heading"><h3><a class="btn btn-default" href="<? echo $self; ?>"><i class="fa fa-arrow-left"></i></a> <?
						if (!empty($data_in['id'])) echo $messages['edit_element'] . " #" . $data_in['id'];
						else echo $messages['create_element'];
						?></h3>
					</div>
					<form action="?act=add&id=<? echo $data_in['id']; ?>" method="post" enctype="multipart/form-data">
					<div class="panel-body" style="color:#000000;">
<?	
	// Перебор всех полей редактирования
	foreach ($fields as $f_name => $f_options) {
		// Проверка прав доступа
		if (empty($f_options['access_show']) || (!empty($f_options['access_show']) && in_array($user_info['user_role'], $f_options['access_show']))) {
			// Проверка прав изменения
			if (!empty($f_options['access_edit']) && !in_array($user_info['user_role'], $f_options['access_edit'])) $disabled = ' disabled';
			else  $disabled = '';
?>
						<div class="row mtop-20">
							<div class="col-xs-4 text-right"><? echo $f_options['desc']; ?></div>
							<div class="col-xs-8 text-left">	
<?		switch ($f_options['tag']) { 
			default:
			case 'input': 
					if ($f_options['type'] == 'digit') $type = 'text';
					else if ($f_options['type'] == 'timestamp') $type = 'text';
					//else if ($f_options['type'] == 'hidden') $type = 'text';
					else $type = $f_options['type'];
					if (!empty($f_options['default']) && empty($data_in[$f_name])) $data_in[$f_name] = $f_options['default'];
					echo '<input type="' . $type . '" name="' . $f_name . '" class="form-control'.$disabled.'" id="" value="' . $data_in[$f_name] . '">';
					break;
			case 'textarea': 
					echo '<textarea name="' . $f_name . '" rows="5" class="form-control'.$disabled.'" id="">' . $data_in[$f_name] . '</textarea>';
					break;
			case 'select': 
					echo '<select name="' . $f_name . '" class="form-control" id=""' . $disabled . '>';
					if (!empty($f_options['variants'])) {
						foreach ($f_options['variants'] as $num => $val) {
							echo '<option value="' . $num . '"';
							if ($num == $data_in[$f_name]) echo ' selected';
							echo '>' . $val . '</option>';
						}
					} else if (!empty($f_options['mysql_query'])) {
						$variants = db_array ($f_options['mysql_query']);
						foreach ($variants as $var) {
							echo '<option value="' . $var[$f_options['row_id']] . '"';
							if ($var[$f_options['row_id']] == $data_in[$f_name]) echo ' selected';
							echo '>' . $var[$f_options['row_title']] . '</option>';
						}
					} 
					echo '</select>';
					break;
			case 'list': ?>
					<div class="row mtop-20">
                        <div class="col-xs-8">
                            <input type="text" id="<? echo $f_name; ?>_input" class="form-control input-xs-8" placeholder="<? echo $f_options['input_placeholder']; ?>">
                        </div>
                        <div class="col-xs-4">
                            <button type="button" id="<? echo $f_name; ?>_button" class="btn btn-default form-control col-xs-4"><? echo $f_options['add_button_txt']; ?></button>
                        </div>
                    </div>
					<input type="hidden" id="<? echo $f_name; ?>_id">
					<div id="<? echo $f_name; ?>_list"></div>
				<?	break;
			case 'picture': ?>
					<div class="row mtop-20">
                        <div class="col-xs-8">
							<div class="row" id="<? echo $f_name; ?>_holder">
<?		$pictures = db_array ("SELECT * FROM `lim_pictures` WHERE `picture_type`='" . $f_options['picture_type'] . "' AND `picture_field`='$f_name' AND `picture_element`='" . $data_in['id'] . "' AND `picture_width`>0 ORDER BY `picture_tst` DESC", true);
		if (empty($pictures)) echo '<div class="col-xs-12 text-muted">не загружено ни одной фотографии</div>';
		else foreach ($pictures as $picture) { ?>
					<div class="col-md-4" id="picture_<? echo $picture['picture_id']; ?>">
                        <div class="thumbnails thumbnail-style">
                            <a class="fancybox-button zoomer" data-rel="fancybox-button" title="" href="pictures/<? echo $f_options['picture_type'] . '/' . $picture['picture_filename']; ?>.jpg">
                                <span class="overlay-zoom">  
                                    <img class="img-responsive" src="pictures/<? echo $f_options['picture_type'] . '/' . $picture['picture_filename']; ?>.jpg" alt="" />
                                    <span class="zoom-icon"></span>                   
                                </span>                                              
                            </a>                    
                            <div class="caption">
                                <p><button type="button" class="btn btn-default btn-xs" onclick="picture_delete(<? echo $picture['picture_id']; ?>);"><i class="fa fa-trash-o"></i></button>
								<input type="radio" name="<? echo $f_name; ?>" value="<? echo $picture['picture_filename']; ?>"<?
									if ($picture['picture_filename'] == $data_in[$f_name]) echo ' checked';
								?>> Обложка
								</p>
                            </div>
                        </div>
                    </div>
<?		} ?>				
							</div>
                        </div>
                        <div class="col-xs-4">
                            <button type="button" id="<? echo $f_name; ?>_btn_upload" class="btn btn-default form-control">Загрузить фото</button>
							<i class="fa fa-spin fa-spinner" id="<? echo $f_name; ?>_pbar" style="display:none;"></i>
                        </div>
                    </div>
				<?	break;
			case 'checkboxes':
					echo '{Элемент '.$f_options['tag'].' в данный момент недоступен}';
					break;
		}
		if (!empty($f_options['dadata']['yandex_map'])) echo '<div id="' . $f_name . '_map" style="width: 100%; height: 180px"></div>';
?>
							</div>
						</div>		
<?		}
	}
?>
					</div>
					<div class="panel-footer" style="color:black;">
						<a class="btn btn-default" href="<? echo $self; ?>"><i class="fa fa-arrow-left"></i> Вернуться</a>
						<input type="submit" class="btn btn-success" value="Сохранить изменения"><? //echo $log; ?>
					</div>
					</form>					
				</div>
				
<?	} else { ?>

					<div class="panel-heading"><h3><? echo $messages['elements_list']; ?></h3>
					</div>
					<table class="table">
					<tr>
						<th>#id</th>
						<?	// Заголовки колонок таблицы
						foreach ($table_list_fields as $field) echo '<th>' . $field['desc'] . '</th>'; ?>
						<th>Действия</th>
					</tr>
<?	// Построение таблицы со списком элементов
	$elements = db_array ($list_request);
	foreach ($elements as $element) {
		$element['self'] = $self; ?>
					<tr>
						<td><? echo $element[$row_id]; ?></td>
						<?	foreach ($table_list_fields as $f_name => $f_options) {
								// Подстановка переменных вместо шаблонов в строковых переменных
								foreach ($f_options as $f_key => $f_option) {
									if (!is_string($f_option)) continue;
									preg_match_all('/\{\{(\w+)\}\}/', $f_options[$f_key], $matches);
									// если найдены шаблонные вставки
									if (!empty($matches[1])) foreach ($matches[1] as $tmpl_fieldname) {
										$f_options[$f_key] = str_replace('{{' . $tmpl_fieldname . '}}', $element[$tmpl_fieldname], $f_options[$f_key]);
									}
								}
								echo '<td>';
									if (!empty($f_options['href'])) echo '<a href="' . $f_options['href'] . '">';
									if (!empty($f_options['db_request'])) echo db_result( $f_options['db_request'] );
									else if (!empty($f_options['value'])) echo $f_options['value'];
									else echo $element[$f_name];
									if (!empty($f_options['href'])) echo '</a>';
								echo '</td>';
							} ?>
						<td>
							<a class="btn btn-sm btn-info" href="<? echo $self; ?>?id=<? echo $element[$row_id]; ?>&act=edit">
								<i class="fa fa-edit"></i>
							</a>
							<a class="btn btn-sm btn-danger" href="<? echo $self; ?>?id=<? echo $element[$row_id]; ?>&act=del" onclick="return confirm('Вы действительно хотите удалить элемент?');">
								<i class="fa fa-trash-o"></i>
							</a>
						</td>
					</tr>
<?	} ?>					
					</table>
					<div class="panel-footer">
						<a class="btn btn-success" href="<? echo $self; ?>?act=new"><? echo $messages['new_element']; ?></a>
					</div>
<?	} ?>
			</div>
		</section>
	</div>
</div>
<? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

<?	if ($jcrop_need) { ?>
	<!-- Upload & crop photo -->
	<script src="plugins/jcrop/js/jquery.Jcrop.js"></script>
	<script type="text/javascript" src="scripts/js/ajaxupload.3.5.js" ></script>
<?	} ?>
<?	if ($dadata_need) { ?>
	<script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-15.1.min.js"></script>
<?	} ?>
<?	if ($yandex_map_need) { ?>
	<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<?	} ?>


<script>
    jQuery(document).ready(function() {
        //App.init();
        //OwlCarousel.initOwlCarousel();
	});
<?	foreach ($fields as $f_name => $f_options) {
		if (!empty($f_options['dadata'])) { 
			if ($f_options['dadata']['type'] == 'ADDRESS') { 
?>
	// Подсказки Dadata.ru (Кладр) при вводе адреса
	$("input[name=<? echo $f_name; ?>]").suggestions({
        serviceUrl: "https://dadata.ru/api/v2",
        token: "154fa715902b207f0c64b376646db03631fa273e",
        type: "ADDRESS",
        // Вызывается, когда пользователь выбирает одну из подсказок
        onSelect: function(suggestion) {
			$('input[name=<? echo $f_options['dadata']['prefix']; ?>country]').val(suggestion.data.country);
			$('input[name=<? echo $f_options['dadata']['prefix']; ?>region]').val(suggestion.data.region);
			$('input[name=<? echo $f_options['dadata']['prefix']; ?>city]').val(suggestion.data.city);
			var address = '';
			if (suggestion.data.street_type != null) 	address += suggestion.data.street_type 	+ ' ';
			if (suggestion.data.street != null) 		address += suggestion.data.street 		+ ' ';
			if (suggestion.data.house_type != null) 	address += suggestion.data.house_type 	+ ' ';
			if (suggestion.data.house != null) 			address += suggestion.data.house 		+ ' ';
			if (suggestion.data.block_type != null) 	address += suggestion.data.block_type 	+ ' ';
			if (suggestion.data.block != null) 			address += suggestion.data.block 		+ ' ';
			if (suggestion.data.flat_type != null) 		address += suggestion.data.flat_type 	+ ' ';
			if (suggestion.data.flat != null) 			address += suggestion.data.flat;
			$('input[name=<? echo $f_options['dadata']['prefix']; ?>address]').val(address);
			$('input[name=<? echo $f_options['dadata']['prefix']; ?>geolocation_id]').val(suggestion.data.kladr_id);
			
				// Перерисовка яндекс-карты
				<? echo $f_name?>_map.destroy();
				// Запрос геокодирования
				ymaps.geocode(suggestion.value, { results: 1 }).then(function (res) {
					// Панорамируем карту на точку
					var firstGeoObject = res.geoObjects.get(0);
					<? echo $f_name?>_map = new ymaps.Map("<? echo $f_name?>_map", {
						center: firstGeoObject.geometry.getCoordinates(),
						zoom: 10
					});
					<? echo $f_name?>_map.controls.add('zoomControl', {
						float: 'none',
						position: {
							right: 40,
							top: 5
						}
					});
					// Задаем изображение для иконок меток.
					res.geoObjects.options.set('iconImageHref', 'images/favicon.png');
					res.geoObjects.options.set('iconImageSize', [32, 32]);
					res.geoObjects.options.set('iconImageOffset', [-16, -16]);
					// Добавляем полученную коллекцию на карту.
					<? echo $f_name?>_map.geoObjects.add(res.geoObjects);
					// Заполняем значение широты и долготы
					$('input[name=<? echo $f_options['dadata']['prefix']; ?>long]').val(firstGeoObject.geometry.getCoordinates()[0]);
					$('input[name=<? echo $f_options['dadata']['prefix']; ?>atti]').val(firstGeoObject.geometry.getCoordinates()[1]);
				});			
			
			
        }
    });	
<?				if (!empty($f_options['dadata']['yandex_map'])) { ?>

<!-- ymaps -->
ymaps.ready(init);
var <? echo $f_name?>_map, myGeoObjects = [];

function init(){
    // Создаем карту с нужным центром и масштабом
	<? echo $f_name?>_map = new ymaps.Map("<? echo $f_name?>_map", {
		center: [55,37],
		zoom: 10
	});
	<? echo $f_name?>_map.controls.add('zoomControl');

	// Поиск координат стартовой точки
	var user_full_address = '<? echo $data_in[$f_name]; ?>';
	console.log('user_full_address='+user_full_address);
    ymaps.geocode(user_full_address, { results: 1 }).then(function (res) {
		// Выбираем первый результат геокодирования.
        var firstGeoObject = res.geoObjects.get(0);
		<? echo $f_name?>_map.setCenter(firstGeoObject.geometry.getCoordinates());
		
		// Задаем изображение для иконок меток.
		//res.geoObjects.options.set('preset', 'twirl#redStretchyIcon');
		res.geoObjects.options.set('iconImageHref', 'images/favicon.png');
		res.geoObjects.options.set('iconImageSize', [32, 32]);
		res.geoObjects.options.set('iconImageOffset', [-16, -16]);
		// Добавляем полученную коллекцию на карту.
		<? echo $f_name?>_map.geoObjects.add(res.geoObjects);
	}, function (err) {
        // Если геокодирование не удалось, сообщаем об ошибке.
        //alert(err.message);
    });
}
<!-- /ymaps -->	

<?				}
			}
		} 
	
		if ($f_options['tag'] == 'list' & $f_options['type'] == 'autocomplete') {
			// Список прикрепляемых элементов ?>
			// Навешиваем автоподсказки на поле ввода тега
			$('#<? echo $f_name; ?>_input').autocomplete({
				source: "pages/ajax/<? echo $f_options['ajax_url']; ?>?method=autocomplete",
				minLength: 1,
				select: function( event, ui ) {
					$('#<? echo $f_name; ?>_button').removeAttr('disabled');
					if (ui.item.id != undefined) $('#<? echo $f_name; ?>_id').val(ui.item.id);
					else $('#like_tag_id').val(0);
				}
			});
			// Действия при ручном наборе текста в теге
			$('#<? echo $f_name; ?>_input').on('keyup', function() {
				$('#<? echo $f_name; ?>_id').val(0);
				$('#<? echo $f_name; ?>_button').addAttr('disabled');
			});
			// Кнопка добавления
			$('#<? echo $f_name; ?>_button').on('click', function() {
				id = $('#<? echo $f_name; ?>_id').val();
				if (!!! id) { alert ('Не выбран элемент для добавления'); return; }
				$.ajax({
					url: "pages/ajax/<? echo $f_options['ajax_url']; ?>",
					type: "GET",
					data: 'method=add&parent_id=<? echo $data_in['id']; ?>&id=' + id,
					dataType: "html",
					success: function(answer) {
						if (answer != '') {
							$('#<? echo $f_name; ?>_list').html(answer);
						} else alert ('Ошибка соединения');
					}
				});
			});
			jQuery(document).ready(function() {
				$.ajax({
					url: "pages/ajax/<? echo $f_options['ajax_url']; ?>",
					type: "GET",
					data: 'method=list&parent_id=<? echo $data_in['id']; ?>',
					dataType: "html",
					success: function(answer) {
						if (answer != '') {
							$('#<? echo $f_name; ?>_list').html(answer);
						}
					}
				});				
			});
	
<?		}
		
		if ($f_options['tag'] == 'picture') { ?>
			
	// Jquery-загрузчик фотографии на сервер
	$(function(){
		var btnUpload=$('#<? echo $f_name; ?>_btn_upload');
		var pbar_avatar=$('#<? echo $f_name; ?>_pbar');
		new AjaxUpload(btnUpload, {
			action: './scripts/php/upload-photo.php?picture_type=<? echo $f_options['picture_type']; ?>&picture_element=<? echo $data_in['id']; ?>&picture_field=<? echo $f_name; ?>',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|jpeg)$/.test(ext))){ 
                    // extension is not allowed 
					alert('Разрешенный формат изображения - JPG');
					return false;
				}
				// Высвечиваем прогресс-бар
				pbar_avatar.show();
				btnUpload.hide();
				// Уничтожаем предыдущие картинки и плагины, если они были
				try {jcrop_api.destroy(); $('#target').remove();} catch (e) {}
			},
			onComplete: function(file, response){
				// По факту завершения загрузки гасим прогресс-бар
				console.log(response);
				pbar_avatar.hide();
				btnUpload.show();
				var answer;
				try {answer = $.parseJSON(response);} catch (e) {}
				if (answer != undefined) {
					if (answer.result === "ok") {
						// Высвечиваем в окне имя закачанного файла
						$('#modal_crop_filename').html(file);
						// Открываем модальное окно
						$('#modal_crop').modal({
						  backdrop: 'static',
						  keyboard: false
						})
						// Открываем картинку в модальном окне
						$('#modal_crop_body').html('<img src="/pictures/tmp/compact/'+answer.filename+'" id="target" alt="" />');
						// По факту подгрузки картинки навешиваем на нее Crop-плагин
						$('#target').load(function() {initJcrop(<? 	if (empty($f_options['picture_proportions'])) echo 1;
																	else echo $f_options['picture_proportions']; ?>);});
						// Запоминаем id текущего изображения
						cur_crop_picture_id = answer.picture_id;
					} else alert('Ошибка!\r\n' + answer.error_txt); 
				} else alert('Ошибка соединения с сервером!'); 
			}
		});
	});		
		
<?		}
	} // end foreach ?>
	
	function list_element_delete(rl_id, el_url, el_id) { 
		if (!!! rl_id) return;
		console.log("pages/ajax/" + el_url);
		$.ajax({
					url: "pages/ajax/" + el_url,
					type: "GET",
					data: 'method=delete&parent_id=<? echo $data_in['id']; ?>&id=' + rl_id,
					dataType: "html",
					success: function(answer) {
						if (answer != '') {
							$('#' + el_id + '_list').html(answer);
						} else alert ('Ошибка соединения');
					}
		});
	}
	
	function picture_delete(picture_id) { 
		if (!!! picture_id) return;
		if (!confirm('Вы действительно хотите удалить элемент?')) return;
		console.log("pages/ajax/picture_delete.php");
		$.ajax({
					url: "pages/ajax/picture_delete.php",
					type: "POST",
					data: 'picture_id=' + picture_id,
					dataType: "html",
					success: function(answer) {
						if (answer == 'ok') {
							$('#picture_' + picture_id).remove();
						} else alert ('Ошибка!\r\n' + answer);
					}
		});
	}	
	
</script>

</body>
</html>