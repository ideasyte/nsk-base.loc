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

        /*Сохранение данных клиентов*/
		case 'clients':
			if (!empty($el_id)) $client = db_row("SELECT * FROM `lim_clients` WHERE `cl_id`='$el_id'");
			if (!empty($el_id) && empty($client['cl_id']))	$data_out['result_txt'] = "Ошибка сохранения данных: клиент с id#$el_id не найден!";
			else if (!empty($el_id) && $enter_user != $client['cl_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют редактировать клиентов, созданных другими пользователями!";
			else {
				$cl_fio = $_POST['cl_fio'];
				$cl_phone = $_POST['cl_phone'];

                $cl_secoundary = intval($_POST['cl_secoundary']);
                $cl_new = intval($_POST['cl_new']);
                $cl_new_with_keys = intval($_POST['cl_new_with_keys']);

				$cl_rooms_1 = intval($_POST['cl_rooms_1']);
				$cl_rooms_2 = intval($_POST['cl_rooms_2']);
				$cl_rooms_3 = intval($_POST['cl_rooms_3']);
				$cl_rooms_4 = intval($_POST['cl_rooms_4']);
				$cl_rooms_etc = intval($_POST['cl_rooms_etc']);
				$cl_room_only = intval($_POST['cl_room_only']);
                $cl_studio = intval($_POST['cl_studio']);
				$cl_house = intval($_POST['cl_house']);
                $cl_land_only = intval($_POST['cl_land_only']);
                $cl_house_with_land = intval($_POST['cl_house_with_land']);
				$cl_non_residential = intval($_POST['cl_non_residential']);
				$cl_sharing = intval($_POST['cl_sharing']);
				$cl_garage = intval($_POST['cl_garage']);
				$cl_pay_type = intval($_POST['cl_pay_type']);
				$cl_price_to = intval($_POST['cl_price_to']);
				$cl_area_from = intval($_POST['cl_area_from']);
				$cl_desc = $_POST['cl_desc'];
				if ( get_magic_quotes_gpc()) {
					$cl_fio=addslashes(stripslashes(trim($cl_fio)));
					$cl_phone=addslashes(stripslashes(trim($cl_phone)));
					$cl_desc=addslashes(stripslashes(trim($cl_desc)));
				}
				// Админы могут менять агента, закрепленного за клиентом
				if ($user_info['user_role'] == 'superadmin' || $user_info['user_role'] == 'admin') {
                    $cl_user = intval($_POST['cl_user']);
                    $cl_user = (intval($_POST['cl_user'])) ?: $enter_user;
                }
				else $cl_user = $enter_user;

                $rq_data = "`cl_fio`='$cl_fio',
							`cl_phone`='$cl_phone',
                            `cl_secoundary`='$cl_secoundary',
							`cl_new`='$cl_new',
							`cl_new_with_keys`='$cl_new_with_keys',
							`cl_rooms_1`='$cl_rooms_1',
							`cl_rooms_2`='$cl_rooms_2',
							`cl_rooms_3`='$cl_rooms_3',
							`cl_rooms_4`='$cl_rooms_4',
							`cl_rooms_etc`='$cl_rooms_etc',
							`cl_room_only`='$cl_room_only',
							`cl_studio`='$cl_studio',
							`cl_house`='$cl_house',
    						`cl_land_only`='$cl_land_only',
							`cl_house_with_land`='$cl_house_with_land',
							`cl_non_residential`='$cl_non_residential',
							`cl_sharing`='$cl_sharing',
							`cl_garage`='$cl_garage',
							`cl_pay_type`='$cl_pay_type',
							`cl_price_to`='$cl_price_to',
							`cl_area_from`='$cl_area_from',
							`cl_desc`='$cl_desc',
							`cl_user`='$cl_user'";

				if (empty($el_id)) {
					$ret = db_insert("	INSERT INTO `lim_clients` SET $rq_data");
					$data_out['result_txt'] = "Новый клиент успешно сохранен в базе";
				} else {
					$ret = db_request("	UPDATE `lim_clients` SET $rq_data WHERE `cl_id`='$el_id'", true);
					$data_out['result_txt'] = "Информация о клиенте успешно отредактирована";
				}
				if ($ret) 	$data_out['result'] = 'ok';
				else		$data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
			}
			break;


        /*Сохранение данных продавцов*/
        case 'objects':
            if (!empty($el_id)) $client = db_row("SELECT * FROM `lim_objects` WHERE `obj_id`='$el_id'");
            if (!empty($el_id) && empty($client['obj_id']))	$data_out['result_txt'] = "Ошибка сохранения данных: клиент с id#$el_id не найден!";
            else if (!empty($el_id) && $enter_user != $client['obj_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют редактировать клиентов, созданных другими пользователями!";
            else {
                $obj_fio = $_POST['obj_fio'];
                $obj_phone = $_POST['obj_phone'];
                $obj_type = $_POST['obj_type'];
                $obj_housetype = $_POST['obj_housetype'];
                $obj_price = intval($_POST['obj_price']);
                $obj_bargain = intval($_POST['obj_bargain']);
                // Депозит, свет, коммунальное, вода
                $obj_deposit = intval($_POST['obj_deposit']);
                $obj_light = intval($_POST['obj_light']);
                $obj_kommunals = intval($_POST['obj_kommunals']);
                $obj_water = intval($_POST['obj_water']);
                $obj_price_desc = $_POST['obj_price_desc'];
                // Адрес, квартира, этаж
                $obj_address = $_POST['obj_address'];
                $obj_room = intval($_POST['obj_flat']);
                $obj_floor = intval($_POST['obj_floor']);
                $obj_floors = intval($_POST['obj_floors']);
                $obj_two_floors = intval($_POST['obj_two_floors']);
                // Материал дома
                $obj_housematerial = intval($_POST['obj_housematerial']);
                $obj_housematerial_desc = $_POST['obj_housematerial_desc'];
                // Площади
                $obj_area = $_POST['obj_area'];
                $obj_area_life = $_POST['obj_area_life'];
                $obj_area_kitchen = $_POST['obj_area_kitchen'];
                $obj_area_sanuzel_r = $_POST['obj_area_sanuzel_r'];
                $obj_area_sanuzel_s = $_POST['obj_area_sanuzel_s'];
                $obj_sanuzel_common = intval($_POST['obj_sanuzel_common']);
                $obj_area_land = $_POST['obj_area_land'];
                $obj_area_loggia = $_POST['obj_area_loggia'];
                $obj_area_balcony = $_POST['obj_area_balcony'];
                $obj_area_mansard = $_POST['obj_area_mansard'];
                $obj_balcony_desc = $_POST['obj_balcony_desc'];
                $obj_balcony_warmed = intval($_POST['obj_balcony_warmed']);
                $obj_balcony_glass = intval($_POST['obj_balcony_glass']);
                $obj_balcony_glasspackets = intval($_POST['obj_balcony_glasspackets']);
                // Ориентация окон
                $obj_window_glasspackets = intval($_POST['obj_window_glasspackets']);
                $obj_window_orient = intval($_POST['obj_window_orient']);
                $obj_window_orient_desc = $_POST['obj_window_orient_desc'];
                // Примечания
                $obj_additional_alt = intval($_POST['obj_additional_alt']);
                $obj_additional_sale_free = intval($_POST['obj_additional_sale_free']);
                $obj_additional_physically_free = intval($_POST['obj_additional_physically_free']);
                $obj_additional_legacy_free = intval($_POST['obj_additional_legacy_free']);
                $obj_additional_through_rooms = intval($_POST['obj_additional_through_rooms']);
                // Аванс/приостановлено
                $obj_advance_for = $_POST['obj_advance_for'];
                $obj_stopped_for = $_POST['obj_stopped_for'];
                // Документы
                $obj_documents_desc = $_POST['obj_documents_desc'];
                $obj_documents_status = intval($_POST['obj_documents_status']);
                $obj_dkp = $_POST['obj_dkp'];
                $obj_mortgage_possible = intval($_POST['obj_mortgage_possible']);
                $obj_encumbrance = $_POST['obj_encumbrance'];
                $obj_additional_desc2 = $_POST['obj_additional_desc2'];
                $obj_banner = $_POST['obj_banner'];
                $obj_keys = $_POST['obj_keys'];
                $obj_photos = $_POST['obj_photos'];
                // Доли и комнаты
                $obj_roomstructure_ = intval($_POST['obj_roomstructure_']);
                // Если опция не выбрана, но поля заполнены - автовыбор опции на основе полей
                if ($obj_roomstructure_ != 1 && $obj_roomstructure_ != 2 && $obj_roomstructure_ !=3) {
                    if (!empty($_POST['obj_total_rooms_1'])) $obj_roomstructure_ = 1;
                    else if (!empty($_POST['obj_total_rooms_2'])) $obj_roomstructure_ = 2;
                    else if (!empty($_POST['obj_total_rooms_3'])) $obj_roomstructure_ = 3;
                }
                if (!empty($obj_roomstructure_)) switch ($obj_roomstructure_) {
                    case 1: // Комната в
                        $obj_total_rooms = intval($_POST['obj_total_rooms_1']);
                        $obj_room_num = 0;
                        break;
                    case 2: // Доля в
                        $obj_total_rooms = intval($_POST['obj_total_rooms_2']);
                        $obj_room_num = 0;
                        break;
                    case 3: // Доля в (с указанием комнаты)
                        $obj_total_rooms = intval($_POST['obj_total_rooms_3']);
                        $obj_room_num = intval($_POST['obj_room_num']);
                        break;
                }
                // Застройщик
                $obj_developer = $_POST['obj_developer'];
                $obj_deadline = $_POST['obj_deadline'];
                // Мебель, ремонт
                $obj_furniture_desc = $_POST['obj_furniture_desc'];
                $obj_furniture = intval($_POST['obj_furniture']);
                $obj_repaired = $_POST['obj_repaired'];
                $obj_additional_desc3 = $_POST['obj_additional_desc3'];
                // Опции
                $obj_additional_internet = intval($_POST['obj_additional_internet']);
                $obj_additional_phone = intval($_POST['obj_additional_phone']);
                $obj_additional_cabeltv = intval($_POST['obj_additional_cabeltv']);
                $obj_additional_domofon = intval($_POST['obj_additional_domofon']);
                $obj_additional_video = intval($_POST['obj_additional_video']);
                $obj_additional_kpp = intval($_POST['obj_additional_kpp']);
                $obj_desc = $_POST['obj_desc'];

                if ( get_magic_quotes_gpc()) {
                    $obj_fio=addslashes(stripslashes(trim($obj_fio)));
                    $obj_phone=addslashes(stripslashes(trim($obj_phone)));
                    $obj_type=addslashes(stripslashes(trim($obj_type)));
                    $obj_housetype=addslashes(stripslashes(trim($obj_housetype)));
                    $obj_desc=addslashes(stripslashes(trim($obj_desc)));
                    $obj_price_desc=addslashes(stripslashes(trim($obj_price_desc)));
                    $obj_address=addslashes(stripslashes(trim($obj_address)));
                    $obj_housematerial_desc=addslashes(stripslashes(trim($obj_housematerial_desc)));
                    $obj_area=addslashes(stripslashes(trim($obj_area)));
                    $obj_area_life=addslashes(stripslashes(trim($obj_area_life)));
                    $obj_area_kitchen=addslashes(stripslashes(trim($obj_area_kitchen)));
                    $obj_area_sanuzel_r=addslashes(stripslashes(trim($obj_area_sanuzel_r)));
                    $obj_area_sanuzel_s=addslashes(stripslashes(trim($obj_area_sanuzel_s)));
                    $obj_area_land=addslashes(stripslashes(trim($obj_area_land)));
                    $obj_area_loggia=addslashes(stripslashes(trim($obj_area_loggia)));
                    $obj_area_balcony=addslashes(stripslashes(trim($obj_area_balcony)));
                    $obj_area_mansard=addslashes(stripslashes(trim($obj_area_mansard)));
                    $obj_balcony_desc=addslashes(stripslashes(trim($obj_balcony_desc)));
                    $obj_window_orient_desc=addslashes(stripslashes(trim($obj_window_orient_desc)));
                    $obj_advance_for=addslashes(stripslashes(trim($obj_advance_for)));
                    $obj_stopped_for=addslashes(stripslashes(trim($obj_stopped_for)));
                    $obj_documents_desc=addslashes(stripslashes(trim($obj_documents_desc)));
                    $obj_dkp=addslashes(stripslashes(trim($obj_dkp)));
                    $obj_encumbrance=addslashes(stripslashes(trim($obj_encumbrance)));
                    $obj_additional_desc2=addslashes(stripslashes(trim($obj_additional_desc2)));
                    $obj_banner=addslashes(stripslashes(trim($obj_banner)));
                    $obj_keys=addslashes(stripslashes(trim($obj_keys)));
                    $obj_photos=addslashes(stripslashes(trim($obj_photos)));
                    $obj_developer=addslashes(stripslashes(trim($obj_developer)));
                    $obj_deadline=addslashes(stripslashes(trim($obj_deadline)));
                    $obj_furniture_desc=addslashes(stripslashes(trim($obj_furniture_desc)));
                    $obj_repaired=addslashes(stripslashes(trim($obj_repaired)));
                    $obj_additional_desc3=addslashes(stripslashes(trim($obj_additional_desc3)));
                }

                switch ($obj_type) {
                    case 'obj_rooms_1':         $obj_rooms_1 = 1;           break;
                    case 'obj_rooms_2':         $obj_rooms_2 = 1;           break;
                    case 'obj_rooms_3':         $obj_rooms_3 = 1;           break;
                    case 'obj_rooms_4':         $obj_rooms_4 = 1;           break;
                    case 'obj_rooms_etc':       $obj_rooms_etc = 1;         break;
                    case 'obj_room_only':       $obj_room_only = 1;         break;
                    case 'obj_studio':          $obj_studio = 1;            break;
                    case 'obj_house':           $obj_house = 1;             break;
                    case 'obj_land_only':       $obj_land_only = 1;         break;
                    case 'obj_house_with_land': $obj_house_with_land = 1;   break;
                    case 'obj_non_residential': $obj_non_residential = 1;   break;
                    case 'obj_sharing':         $obj_sharing = 1;           break;
                    case 'obj_garage':          $obj_garage = 1;            break;
                }

                switch ($obj_housetype) {
                    case 'obj_secoundary':      $obj_secoundary = 1;    break;
                    case 'obj_new':             $obj_new = 1;           break;
                    case 'obj_new_with_keys':   $obj_new_with_keys = 1; break;
                }

                // Админы могут менять агента, закрепленного за клиентом
                if ($user_info['user_role'] == 'superadmin' || $user_info['user_role'] == 'admin') {
                    $obj_user = (intval($_POST['obj_user'])) ?: $enter_user;
                }
                else $obj_user = $enter_user;
                // Конвертация дат
                $obj_advance_for = substr($obj_advance_for,6,4).'-'.substr($obj_advance_for,3,2).'-'.substr($obj_advance_for,0,2);
                $obj_stopped_for = substr($obj_stopped_for,6,4).'-'.substr($obj_stopped_for,3,2).'-'.substr($obj_stopped_for,0,2);
                $obj_deadline = substr($obj_deadline,6,4).'-'.substr($obj_deadline,3,2).'-'.substr($obj_deadline,0,2);

                $rq_data = "`obj_fio`='$obj_fio',
							`obj_phone`='$obj_phone',
							`obj_secoundary`='$obj_secoundary',
							`obj_new`='$obj_new',
							`obj_new_with_keys`='$obj_new_with_keys',
							`obj_rooms_1`='$obj_rooms_1',
							`obj_rooms_2`='$obj_rooms_2',
							`obj_rooms_3`='$obj_rooms_3',
							`obj_rooms_4`='$obj_rooms_4',
							`obj_rooms_etc`='$obj_rooms_etc',
							`obj_room_only`='$obj_room_only',
							`obj_studio`='$obj_studio',
							`obj_house`='$obj_house',
							`obj_land_only`='$obj_land_only',
							`obj_house_with_land`='$obj_house_with_land',
							`obj_non_residential`='$obj_non_residential',
							`obj_sharing`='$obj_sharing',
							`obj_garage`='$obj_garage',
							`obj_price`='$obj_price',
							`obj_bargain`='$obj_bargain',
							`obj_deposit`='$obj_deposit',
							`obj_light`='$obj_light',
							`obj_kommunals`='$obj_kommunals',
							`obj_water`='$obj_water',
							`obj_price_desc`='$obj_price_desc',
							`obj_address`='$obj_address',
							`obj_flat`='$obj_room',
							`obj_floor`='$obj_floor',
							`obj_floors`='$obj_floors',
							`obj_two_floors`='$obj_two_floors',
							`obj_housematerial`='$obj_housematerial',
							`obj_housematerial_desc`='$obj_housematerial_desc',
							`obj_area`='$obj_area',
							`obj_area_life`='$obj_area_life',
							`obj_area_kitchen`='$obj_area_kitchen',
							`obj_area_sanuzel_r`='$obj_area_sanuzel_r',
							`obj_area_sanuzel_s`='$obj_area_sanuzel_s',
							`obj_sanuzel_common`='$obj_sanuzel_common',
							`obj_area_land`='$obj_area_land',
							`obj_area_loggia`='$obj_area_loggia',
							`obj_area_balcony`='$obj_area_balcony',
							`obj_area_mansard`='$obj_area_mansard',
							`obj_balcony_desc`='$obj_balcony_desc',
							`obj_balcony_warmed`='$obj_balcony_warmed',
							`obj_balcony_glass`='$obj_balcony_glass',
							`obj_balcony_glasspackets`='$obj_balcony_glasspackets',
							`obj_window_glasspackets`='$obj_window_glasspackets',
							`obj_window_orient`='$obj_window_orient',
							`obj_window_orient_desc`='$obj_window_orient_desc',
							`obj_additional_alt`='$obj_additional_alt',
							`obj_additional_sale_free`='$obj_additional_sale_free',
							`obj_additional_physically_free`='$obj_additional_physically_free',
							`obj_additional_legacy_free`='$obj_additional_legacy_free',
							`obj_additional_through_rooms`='$obj_additional_through_rooms',
							`obj_advance_for`='$obj_advance_for',
							`obj_stopped_for`='$obj_stopped_for',
							`obj_documents_desc`='$obj_documents_desc',
							`obj_documents_status`='$obj_documents_status',
							`obj_dkp`='$obj_dkp',
							`obj_mortgage_possible`='$obj_mortgage_possible',
							`obj_encumbrance`='$obj_encumbrance',
							`obj_additional_desc2`='$obj_additional_desc2',
							`obj_banner`='$obj_banner',
							`obj_keys`='$obj_keys',
							`obj_photos`='$obj_photos',
							`obj_roomstructure_`='$obj_roomstructure_',
							`obj_room_num`='$obj_room_num',
							`obj_total_rooms`='$obj_total_rooms',
							`obj_developer`='$obj_developer',
							`obj_deadline`='$obj_deadline',
							`obj_furniture_desc`='$obj_furniture_desc',
							`obj_furniture`='$obj_furniture',
							`obj_repaired`='$obj_repaired',
							`obj_additional_desc3`='$obj_additional_desc3',
							`obj_additional_internet`='$obj_additional_internet',
							`obj_additional_phone`='$obj_additional_phone',
							`obj_additional_cabeltv`='$obj_additional_cabeltv',
							`obj_additional_domofon`='$obj_additional_domofon',
							`obj_additional_video`='$obj_additional_video',
							`obj_additional_kpp`='$obj_additional_kpp',
							`obj_desc`='$obj_desc',
							`obj_user`='$obj_user'";

                if (empty($el_id)) {
                    $ret = db_insert("INSERT INTO `lim_objects` SET $rq_data");
                    $data_out['result_txt'] = "Новый клиент успешно сохранен в базе";
                    db_insert("INSERT INTO `lim_events` SET
                                `ev_object` = '$ret',
                                `ev_author` = '$enter_user',
                                `ev_message` = 'Создание объекта'");
                } else {
                    $ret = db_request("UPDATE `lim_objects` SET $rq_data WHERE `obj_id`='$el_id'", true);
                    $data_out['result_txt'] = "Информация о клиенте успешно отредактирована";
                    db_insert("INSERT INTO `lim_events` SET
                                `ev_object` = '$el_id',
                                `ev_author` = '$enter_user',
                                `ev_message` = 'Информация об объекте отредактирована'");
                }
                if ($ret) 	$data_out['result'] = 'ok';
                else		$data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
            }
            break;

        /*Сохранение данных просмотров*/
        case 'reviews':
            $obj_id = intval($_POST['obj_id']);
            if (empty($obj_id)) $data_out['result_txt'] = "Ошибка сохранения данных: id объекта неизвестен!";
            else {
                $object = db_row("SELECT * FROM `lim_objects` WHERE `obj_id`='$obj_id'");
                if (empty($object['obj_id']))	$data_out['result_txt'] = "Ошибка сохранения данных: объект с id#$obj_id не найден!";
                else {
                    if (!empty($el_id)) $review = db_row("SELECT * FROM `lim_reviews` WHERE `rw_id`='$el_id'");
                    if (!empty($el_id) && empty($review['rw_id']))	$data_out['result_txt'] = "Ошибка сохранения данных: просмотр с id#$el_id не найден!";
                    else if (!empty($el_id) && $enter_user != $review['rw_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $data_out['result_txt'] = "Ваши текущие права доступа не позволяют редактировать просмотры, созданные другими пользователями!";
                    else {
                        $rw_review_date = $_POST['rw_review_date'];
                        $rw_comment = $_POST['rw_comment'];
                        $rw_reserve = $_POST['rw_reserve'];

                        if ( get_magic_quotes_gpc()) {
                            $rw_review_date=addslashes(stripslashes(trim($rw_review_date)));
                            $rw_comment=addslashes(stripslashes(trim($rw_comment)));
                            $rw_reserve=addslashes(stripslashes(trim($rw_reserve)));
                        }
                        // Админы могут менять агента, создавшего данный просмотр
                        if ($user_info['user_role'] == 'superadmin' || $user_info['user_role'] == 'admin') {
                            $rw_user = intval($_POST['rw_user']);
                            $rw_user = (intval($_POST['rw_user'])) ?: $enter_user;
                        }
                        else $cl_user = $enter_user;

                        $rq_data = "`rw_review_date`='$rw_review_date',
							`rw_client`='11',
							`rw_object`='$obj_id',
							`rw_comment`='$rw_comment',
							`rw_reserve`='$rw_reserve',
							`rw_user`='$rw_user'";

                        if (empty($el_id)) {
                            $ret = db_insert("	INSERT INTO `lim_reviews` SET $rq_data");
                            $data_out['result_txt'] = "Новый просмотр успешно сохранен в базе";
                        } else {
                            $ret = db_request("	UPDATE `lim_reviews` SET $rq_data WHERE `rw_id`='$el_id'");
                            $data_out['result_txt'] = "Информация о просмотре успешно отредактирована";
                        }
                        if ($ret) 	$data_out['result'] = 'ok';
                        else		$data_out['result_txt'] = "Во время сохранения данных произошла ошибка!";
                    }
                }
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