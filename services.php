<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}
// Склонение названия элемента редактирования
$messages = Array(
	'page_name' => 			'Услуги',
	'elements_list' => 		'Список услуг',
	'delete_success' => 	'Услуга успешно удалена',
	'delete_error' => 		'Ошибка при удалении услуги',
	'create_success' => 	'Услуга успешно создана',
	'save_success' => 		'Информация об услуге успешно отредактирована',
	'create_error' => 		'Ошибка при создании услуги',
	'save_error' => 		'Ошибка при сохранении данных услуги',
	'edit_element' => 		'Редактирование услуги',
	'create_element' => 	'Добавление новой услуги',
	'new_element' => 		'Добавить услугу'
);
// Редактируемая таблица
$table = 'lim_services';
// Идентификатор строки
$row_id = 'srv_id';
// Поля для редактирования
$fields = Array(
	'srv_name' => Array( 'desc' => 'Название услуги',
			'type' => 'text',
			'required' => true
		),
	'srv_sort' => Array( 'desc' => 'Порядок сортировки',
			'type' => 'digits'
		),		
	'org_desc' => Array( 'desc' => 'Текстовое описание',
			'tag' => 'textarea',
			'type' => 'text'
		),
	'org_create_tst' => Array( 'desc' => 'Дата создания',
			'type' => 'timestamp',
			'access_edit' => Array ('none')
		),		
);
// Запрос списка элементов
$list_request = "SELECT * FROM `$table` ORDER BY `srv_sort` ASC";
// Настройки колонок таблицы
$table_list_fields = Array(
	'srv_sort' => Array( 'desc' => 'Сорт.' ),
	'srv_name' => Array( 'desc' => 'Название услуги',
			'href' => '{{self}}?id={{' . $row_id . '}}'
		)
);

function action_after_insert() {}
function action_after_edit() {}

require MC_ROOT.'/pages/table_editor_core.php'; ?>