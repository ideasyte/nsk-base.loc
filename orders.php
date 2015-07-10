<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
//if (empty($enter_user)) {header("Location: login.php"); exit;}
// Склонение названия элемента редактирования
$messages = Array(
	'page_name' => 			'Заявки',
	'elements_list' => 		'Список заявок на бронирование',
	'delete_success' => 	'Заявка успешно удалена',
	'delete_error' => 		'Ошибка при удалении заявки',
	'create_success' => 	'Заявка успешно создана',
	'save_success' => 		'Информация о заявке успешно отредактирована',
	'create_error' => 		'Ошибка при создании заявки',
	'save_error' => 		'Ошибка при сохранении данных заявки',
	'edit_element' => 		'Редактирование заявки',
	'create_element' => 	'Добавление новой заявки',
	'new_element' => 		'Добавить заявку'
);
// Редактируемая таблица
$table = 'lim_orders';
// Идентификатор строки
$row_id = 'ord_id';
// Поля для редактирования
$fields = Array(
	'ord_uid' => Array( 'desc' => 'uid пользователя приложения',
			'type' => 'digits',
			'access_edit' => Array ('none')
		),
	'ord_name' => Array( 'desc' => 'Имя',
			'type' => 'text'
		),
	'ord_phone' => Array( 'desc' => 'Телефон',
			'type' => 'phone'
		),			
	'ord_email' => Array( 'desc' => 'Email',
			'type' => 'email'
		),
	'ord_master' => Array( 'desc' => 'Мастер',
			'tag' => 'select',
			'type' => 'digit',
			'mysql_query' => "SELECT * FROM `lim_masters`",
			'row_id' => 'm_id',
			'row_title' => 'm_surname',
		),
	'ord_status' => Array( 'desc' => 'Бронь подтверждена',
			'tag' => 'select',
			'type' => 'digit',
			'variants' => Array (
				0	=>	'Не проверено',
				1	=>	'Подтверждено',
			)
		),		
	'ord_create_tst' => Array( 'desc' => 'Дата создания',
			'type' => 'timestamp',
			'access_edit' => Array ('none')
		),
	'ord_order_tst' => Array( 'desc' => 'Дата бронирования',
			'type' => 'timestamp',
		)		
);
// Запрос списка элементов
if (!empty($enter_user)) $list_request = "SELECT * FROM `$table` ORDER BY `ord_create_tst` ASC";
// Настройки колонок таблицы
$table_list_fields = Array(
	'ord_name' => Array( 'desc' => 'Имя клиента',
			'href' => '{{self}}?id={{' . $row_id . '}}'
		),
	'ord_phone' => Array( 'desc' => 'Телефон' ),			
	'ord_email' => Array( 'desc' => 'Email' ),
	'ord_status' => Array( 'desc' => 'Подтверждено' )
);

function action_after_insert() {}
function action_after_edit() {}

require MC_ROOT.'/pages/table_editor_core.php'; ?>