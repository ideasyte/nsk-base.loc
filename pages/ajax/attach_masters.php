<? $security_inc = true;
require_once ('../../config.php');
require_once ('../../scripts/php/f_mysql.php');
require_once ('../../scripts/php/sessions.php');
require_once ('../../scripts/php/f_json.php');
// Доступ к странице
if (empty($enter_user)) {echo 'Error authorization!'; exit;}

// Входные параметры
$parent_id = intval($_GET['parent_id']);
$id = intval($_GET['id']);
$q = $_GET['term'];
if ( get_magic_quotes_gpc()) $q = addslashes(stripslashes(trim($q)));

// Массив с ответом сервера
$response = Array();
// Лог
//db_request("INSERT INTO `lim_log_error` SET `log_type`='ajax', `log_data`='".print_r($_GET, true)."'");

function echo_list() {
	global $id, $parent_id;
	$elements = db_array("SELECT * FROM `lim_rel_org_master`, `lim_masters` WHERE `m_id`=`rl_master` AND `rl_organization`='$parent_id'");
	foreach ($elements as $element) { ?>
		<div class="btn-group btn-group-sm" style="margin-bottom:5px;">
			<button type="button" class="btn btn-default btn-sm"><? echo $element['m_surname'] . ' ' . $element['m_name'] . ' ' . $element['m_addname']; ?></button>
			<button type="button" class="btn btn-default btn-sm" onclick="list_element_delete(<? echo $element['rl_id']; ?>, 'attach_masters.php', 'org_masters')"><i class="fa fa-times"></i></button>
		</div><br />
<?	} 
}

function echo_alert($message, $class = 'info') { ?>
	<div class="alert alert-<? echo $class; ?>">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<? echo $message; ?>
	</div><?
}


// Метод
$method = $_GET['method'];
switch ($method) {
	case 'list':
		echo_list();
		break;
	case 'autocomplete':
		$search = db_array ("SELECT * FROM `lim_masters` WHERE `m_surname` LIKE '%$q%' OR `m_name` LIKE '%$q%' OR `m_addname` LIKE '%$q%'");
		foreach ($search as $element) {
			$response[] = array(
				'value' => $element['m_surname'] . ' ' . $element['m_name'] . ' ' . $element['m_addname'],
				'id' => $element['m_id']
			);
		}
		// Вывод информации клиенту
		header("Cache-Control: no-store, no-cashe, must-revalidate, max-age=0"); 
		echo json_encode_cyr($response);
		break;
	case 'add':
		$search = db_result ("SELECT COUNT(*) FROM `lim_rel_org_master`, `lim_masters` WHERE `m_id`=`rl_master` AND `rl_organization`='$parent_id' AND `m_id`='$id'");
		if (!empty($search)) echo_alert('Ошибка! Данный мастер уже прикреплен к салону!', 'warning');
		else if ( db_request ("INSERT INTO `lim_rel_org_master` SET `rl_master`='$id', `rl_organization`='$parent_id'") ) {
			echo_alert('Мастер успешно прикреплен к салону!', 'success');
		} else echo_alert('Ошибка прикрепления мастера!', 'danger'); 
		echo_list();
		break;
	case 'delete':
		if ( db_request ("DELETE FROM `lim_rel_org_master` WHERE `rl_id`='$id'") ) {
			echo_alert('Мастер успешно удален из салона!', 'success');
		} else echo_alert('Ошибка удаления мастера!', 'danger'); 
		echo_list();
		break;
} ?>