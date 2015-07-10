<? $security_inc = true;
include("../../config.php");
require_once ('f_mysql.php');
require_once ('sessions.php');
require_once ('f_json.php');


/* --------- Настройки обработки -------------*/
// Качество Jpeg
$jpeg_quality = 90;
/* -------------------------------------------*/


if (empty($enter_user)) {
	$data_out['result'] = 'error';
	$data_out['error_txt'] = 'Отсутствует авторизация'; 
	echo json_encode_cyr($data_out);
	exit;
} else {
	// id фото в БД
	$picture_id = intval($_POST['picture_id']);
	// Координаты для обрезки фото
	$x1 = intval($_POST['x1']);
	$y1 = intval($_POST['y1']);
	$w = intval($_POST['w']);
	$h = intval($_POST['h']);
	$bx = intval($_POST['bx']);
	$by = intval($_POST['by']);

	$l = print_r($_POST, true);

	if (!empty($picture_id)) {
		// Проверка безопасности, принадлежит ли данное фото текущему юзеру
		$picture = db_row("SELECT * FROM `lim_pictures` WHERE `picture_id`='$picture_id'");
		if ($picture['picture_user'] == $enter_user) {
			// Обрезка фото - за основу берется оригинал
			$path_original = '../../pictures/tmp/original/' . $picture['picture_filename'] . '.jpg';
			$path_compact = '../../pictures/tmp/compact/' . $picture['picture_filename'] . '_c.jpg';
			list($width_original, $height_original) = getimagesize($path_original);
			// Расчет соотношения bound (то, что было выведено в модалке и смасштабировано браузером) vs original
			$scale = $bx / $width_original;
			$scale_ = $by / $height_original;
			if (($scale - $scale_) < -0.01 || ($scale - $scale_) > 0.01 || $scale == 0 || $scale_ == 0) {
				$data_out['result'] = 'error';
				$data_out['error_txt'] = "Ошибка пересчета пропорций фото: $scale <> $scale_"; 
			} else {
				// Пересчет координат
				$x1_ = $x1 / $scale;
				$y1_ = $y1 / $scale;
				$w_ = $w / $scale;
				$h_ = $h / $scale;
				// Определение final размера в зависимости от назначения загружаемого фото
				switch ($picture['picture_type']) {

					case 'avatar':	$final_width = 100; $final_height = 100;
									$path_final = '../../pictures/avatars/' . $picture['picture_filename'] . '.jpg';
									db_request("UPDATE `lim_users` SET `user_avatar`='".$picture['picture_filename']."' WHERE `user_id`='$enter_user'");
									$picture['picture_field'] = 'avatar'; // для мгновенной смены аватарки в html
									break;

					case 'objects':	$final_width = 640; $final_height = 480;
									$path_final = '../../pictures/objects/' . $picture['picture_filename'] . '.jpg';
                                    $path_final_min = '../../pictures/objects/' . $picture['picture_filename'] . '_min.jpg';
                                    db_request("UPDATE `lim_organizations` SET `org_picture`='".$picture['picture_filename']."' WHERE `org_id`='".$picture['picture_element']."'");
									$data_out['append_element'] = '<div class="col-md-4" id="picture_' . $picture['picture_id'] . '"><a href="'.$path_final.'" class="img-group-gallery"><img src="'.$path_final_min.'" class="img-responsive" alt=""></a></div><script>$(".img-group-gallery").colorbox(colorbox_options);</script>';
									break;

					default:		$final_width = 1280; $final_height = 960;
									$path_final = '../../pictures/gallery/' . $picture['picture_filename'] . '.jpg'; break;
				}
				
				
				$img_final = imagecreatetruecolor($final_width, $final_height);
                if ($picture['picture_type'] == 'objects') $img_final_min = imagecreatetruecolor(128, 96);
				if (@$img_original = imagecreatefromjpeg($path_original)) {
					imagecopyresampled($img_final, $img_original, 0, 0, $x1_, $y1_, $final_width, $final_height, $w_, $h_);
					imagejpeg($img_final, $path_final, $jpeg_quality);
                    // Создание thumbnail-а
                    if ($picture['picture_type'] == 'objects') {
                        imagecopyresampled($img_final_min, $img_original, 0, 0, $x1_, $y1_, 128, 96, $w_, $h_);
                        imagejpeg($img_final_min, $path_final_min, $jpeg_quality);
                    }
					db_request("UPDATE `lim_pictures` SET `picture_width`='$final_width', `picture_height`='$final_height' WHERE `picture_id`='$picture_id'");// Удаление original и compact
					@unlink($path_original);
					@unlink($path_compact);
					$data_out['result'] = 'ok';
					$data_out['new_photo_src'] = $path_final; 
					$data_out['picture_field'] = $picture['picture_field']; 
				}
			}
		} else {
			$data_out['result'] = 'error';
			$data_out['error_txt'] = 'Ошибка прав доступа к данному изображению'; 
		}
	} else {
		$data_out['result'] = 'error';
		$data_out['error_txt'] = 'Потерян id изображения для обрезки!'; 
	}
}
echo json_encode_cyr($data_out);
?>