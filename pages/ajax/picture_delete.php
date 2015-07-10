<? $security_inc = true;
require_once ('../../config.php');
require_once ('../../scripts/php/f_mysql.php');
require_once ('../../scripts/php/sessions.php');
require_once ('../../scripts/php/f_json.php');
// Доступ к странице
if (empty($enter_user)) {echo 'Error authorization!'; exit;}

// Входные параметры
$picture_id = intval($_POST['picture_id']);

if (!empty($picture_id)) {
		// Проверка безопасности, принадлежит ли данное фото текущему юзеру
		$picture = db_row("SELECT * FROM `lim_pictures` WHERE `picture_id`='$picture_id'");
		if ($picture['picture_user'] == $enter_user) {
			if ( db_request("DELETE FROM `lim_pictures` WHERE `picture_id`='$picture_id'") ) {
				@unlink ('../../pictures/'.$picture['picture_type'].'/' . $picture['picture_filename'] . '.jpg');
				echo 'ok';
			}
		} else echo 'Отсутствуют права доступа к данному изображению!';
} else echo 'Отсутствует id изображения!'; 

?>