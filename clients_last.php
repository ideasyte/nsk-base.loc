<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}
// Склонение названия элемента редактирования
$messages = Array(
	'page_name' => 			'Клиенты',
	'elements_list' => 		'Список клиентов',
	'delete_success' => 	'Клиент успешно удален',
	'delete_error' => 		'Ошибка при удалении клиента',
	'create_success' => 	'Клиент успешно создан',
	'save_success' => 		'Информация о клиенте успешно отредактирована',
	'create_error' => 		'Ошибка при создании клиента',
	'save_error' => 		'Ошибка при сохранении данных клиента',
	'edit_element' => 		'Редактирование клиента',
	'create_element' => 	'Добавление нового клиента',
	'new_element' => 		'Добавить клиента'
);
// Редактируемая таблица
$table = 'lim_clients';
// Идентификатор строки
$row_id = 'cl_id';
// Поля для редактирования
$fields = Array(
	'cl_fio' => Array( 'desc' => 'ФИО клиента',
			'type' => 'text',
			'required' => true
		),
	'cl_phone' => Array( 'desc' => 'Номер телефона',
			'type' => 'phone',
			'length' => 10
		),
	'cl_price_to' => Array( 'desc' => 'Цена до',
			'type' => 'digit'
		),
	'cl_area_from' => Array( 'desc' => 'Площадь от',
			'type' => 'digit'
		),		
	'cl_desc' => Array( 'desc' => 'Комментарии',
			'tag' => 'textarea',
			'type' => 'text'
		),
	'cl_create_tst' => Array( 'desc' => 'Дата создания',
			'type' => 'timestamp',
			'access_edit' => Array ('none')
		),		
);
// Запрос списка элементов
$list_request = "SELECT * FROM `$table` WHERE `cl_id`>0 ORDER BY `cl_create_tst` DESC";
// Настройки колонок таблицы
$table_list_fields = Array(
	'cl_fio' => Array( 'desc' => 'ФИО',
			'href' => '{{self}}?id={{' . $row_id . '}}'
		),
	'cl_phone' => Array( 'desc' => 'Телефон' ),
	'cl_price_to' => Array( 'desc' => 'Цена до' ),
	'cl_area_from' => Array( 'desc' => 'Площадь от' )
);

function action_after_insert() {}
function action_after_edit() {}

require MC_ROOT.'/pages/table_editor_core.php'; ?>