<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/f_json.php');

if (!$enter_user) $data_out['result_txt'] = 'Отсутствует авторизация';
else {
	$el_id = intval($_POST['el_id']);
	$el_type = $_POST['el_type'];

	db_request("INSERT INTO `lim_log_error` SET `log_type`='save_modal_edit.php' `log_data`='" . print_r($_POST, true) . "'"); 
	switch ($el_type) {
		case 'clients':
			if (!empty($el_id)) $client = db_row("SELECT * FROM `lim_clients` WHERE `cl_id`='$el_id'");
			if (!empty($el_id) && empty($client['cl_id']))	$data_out['result_txt'] = "Ошибка удаления: клиент с id#$el_id не найден!";
			else if (!empty($el_id) && $enter_user != $client['cl_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют удалять клиентов, созданных другими пользователями!";
			else {
				if ( db_request("DELETE FROM `lim_clients` WHERE `cl_id`='$el_id' LIMIT 1") ) 	{
                    $data_out['result'] = 'ok';
                    $data_out['result_txt'] = 'Клиент успешно удален';
                }
				else $data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
			}
			break;
        
        case 'objects':
            if (!empty($el_id)) $object = db_row("SELECT * FROM `lim_objects` WHERE `obj_id`='$el_id'");
            if (!empty($el_id) && empty($object['obj_id']))	$data_out['result_txt'] = "Ошибка удаления: объект с id#$el_id не найден!";
            else if (!empty($el_id) && $enter_user != $object['obj_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют удалять объекты, созданные другими пользователями!";
            else {
                if ( db_request("DELETE FROM `lim_objects` WHERE `obj_id`='$el_id' LIMIT 1") ) 	{
                    $data_out['result'] = 'ok';
                    $data_out['result_txt'] = 'Объект успешно удален';
                }
                else $data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
            }
            break;

        case 'reviews':
            if (!empty($el_id)) $review = db_row("SELECT * FROM `lim_reviews` WHERE `rw_id`='$el_id'");
            if (!empty($el_id) && empty($review['rw_id']))	$data_out['result_txt'] = "Ошибка удаления: просмотр с id#$el_id не найден!";
            else if (!empty($el_id) && $enter_user != $review['rw_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют удалять просмотры, созданные другими пользователями!";
            else {
                if ( db_request("DELETE FROM `lim_reviews` WHERE `rw_id`='$el_id' LIMIT 1") ) 	{
                    $data_out['result'] = 'ok';
                    $data_out['result_txt'] = 'Просмотр успешно удален';
                }
                else $data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
            }
            break;

        case 'comments':
            if (!empty($el_id)) $comment = db_row("SELECT * FROM `lim_comments` WHERE `cm_id`='$el_id'");
            if (!empty($el_id) && empty($comment['cm_id']))	$data_out['result_txt'] = "Ошибка удаления: комментарий с id#$el_id не найден!";
            else if (!empty($el_id) && $enter_user != $comment['cm_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют удалять комментарии, созданные другими пользователями!";
            else {
                if ( db_request("DELETE FROM `lim_comments` WHERE `cm_id`='$el_id' LIMIT 1") ) 	{
                    $data_out['result'] = 'ok';
                    $data_out['result_txt'] = 'Комментарий успешно удален';
                }
                else $data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
            }
            break;        
        
	}
}
if ($data_out['result'] != 'ok') {
	$data_out['result'] = 'error';
	if (empty($data_out['result_txt'])) $data_out['result_txt'] = "Неизвестная ошибка";
}
// Вывод информации клиенту
header("Cache-Control: no-store, no-cashe, must-revalidate, max-age=0"); 
echo json_encode_cyr($data_out);
?>