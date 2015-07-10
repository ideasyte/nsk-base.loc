<?$security_inc = true; // флаг защиты "прямого" открытия php-скриптов из папок
require_once ('../../config.php');
require_once ('f_mysql.php');
require_once ('sessions.php');
if (empty($enter_user)) {echo '{"result":"error","error_txt":"Авторизация пользователя отсутствует!"}'; exit;}

/* --------- Настройки обработки -------------*/

// Качество Jpeg
$jpeg_quality = 90;

// Разрешение фото для вывода в модальное окно и обрезки
$compact_standard_width = 640;
$compact_standard_height = 480;

/* -------------------------------------------*/


$picture_type = $_GET['picture_type'];
$picture_field = $_GET['picture_field'];
if ( get_magic_quotes_gpc()) {
	$picture_type=addslashes(stripslashes(trim($picture_type)));
	$picture_field = addslashes(stripslashes(trim($picture_field)));
}
$picture_element = intval($_GET['picture_element']);


// Случайное имя файла с проверкой на возможные повторы в базе
while (1) {
	$filename_original = uniqid();
	$check_cnt = db_result ("SELECT COUNT(*) FROM `lim_pictures` WHERE `picture_filename`='$filename_original'");
	if (empty($check_cnt)) break;
}
// Путь, куда загружается оригинал фото
$path_original = '../../pictures/tmp/original/' . $filename_original . '.jpg';
$path_compact = '../../pictures/tmp/compact/' . $filename_original . '_c.jpg';

if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
	if ($_FILES['uploadfile']['size'] > 9000000) echo '{"result":"error","error_txt":"Слишком большой размер файла!"}';
	else if ($_FILES['uploadfile']['type'] != 'image/jpeg') echo '{"result":"error","error_txt":"Недопустимый формат файла!"}';
	else {	
		// проверяем пропорции фотографии
		list($width_original, $height_original) = getimagesize($_FILES['uploadfile']['tmp_name']);
		$proportions_original = $width_original / $height_original;
		if ($width_original < 200 || $height_original < 200) echo '{"result":"error","error_txt":"Слишком маленькое разрешение фото (меньше 200 х 200 пикселей). Попробуйте загрузить фото большего размера!"}';
		else if ($width_original > 9000 || $height_original > 6000) echo '{"result":"error","error_txt":"Слишком большое разрешение фото (более 9000 х 6000 пикселей). Попробуйте загрузить фото меньшего размера!"}';
		else if ($proportions_original < 0.25 || $proportions_original > 4) echo '{"result":"error","error_txt":"Слишком вытянутое изображение! Попробуйте загрузить фотографию стандартной прямоугольной формы. Максимальное соотношение сторон: 1 к 4."}';
		else {
			// Перемещаем файл в папку с оригиналами на сервере
			if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $path_original)) {
				// Генерация файла среднего разрешения (compact) для вывода в модальное окно и обрезки
				if ($width_original > $height_original) $scale_compact = $compact_standard_width / $width_original;
				else $scale_compact = $compact_standard_height / $height_original;
				$width_compact = intval($width_original * $scale_compact);
				$height_compact = intval($height_original * $scale_compact);
				$img_compact = imagecreatetruecolor($width_compact, $height_compact);
				if (@$img_original = imagecreatefromjpeg($path_original)) {
					imagecopyresampled($img_compact, $img_original, 0, 0, 0, 0, $width_compact, $height_compact, $width_original, $height_original);
					imagejpeg($img_compact, $path_compact, $jpeg_quality);
					$picture_id = db_insert("INSERT INTO `lim_pictures` SET `picture_filename`='$filename_original', `picture_user`='$enter_user', `picture_element`='$picture_element', `picture_type`='$picture_type', `picture_field`='$picture_field'");
					echo '{"result":"ok","filename":"'.$filename_original.'_c.jpg","picture_id":"'.$picture_id.'"}';
					
					/* --------- Очистка tmp-папок от старых загрузок этого пользователя ----------------*/
					$old_pictures = db_array ("SELECT * FROM `lim_pictures` WHERE `picture_user`='$enter_user' AND `picture_width`=0 AND `picture_id`<>'$picture_id'");
					foreach ($old_pictures as $old_picture) {
						@unlink ('../../pictures/tmp/original/' . $old_picture['picture_filename'] . '.jpg');
						@unlink ('../../pictures/tmp/compact/' . $old_picture['picture_filename'] . '_c.jpg');
					}
					db_request ("DELETE FROM `lim_pictures` WHERE `picture_user`='$enter_user' AND `picture_width`=0 AND `picture_id`<>'$picture_id'");
					/* --------- Очистка tmp-папок от старых загрузок этого пользователя ----------------*/
					
					
				} else echo '{"result":"error","error_txt":"Недопустимый внутренний формат файла!"}';
			} else echo '{"result":"error","error_txt":"Внутренняя ошибка сервера!"}';
		}
	}
} else echo '{"result":"error","error_txt":"Загрузка файла прервана!"}';
?>