<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}
// Склонение названия элемента редактирования
$messages = Array(
	'page_name' => 			'Продажа',
	'elements_list' => 		'Список объектов',
	'delete_success' => 	'Объект успешно удален',
	'delete_error' => 		'Ошибка при удалении объекта',
	'create_success' => 	'Объект успешно создан',
	'save_success' => 		'Информация об объекте успешно отредактирована',
	'create_error' => 		'Ошибка при создании объекта',
	'save_error' => 		'Ошибка при сохранении данных объекта',
	'edit_element' => 		'Редактирование объекта',
	'create_element' => 	'Добавление нового объекта',
	'new_element' => 		'Добавить объект'
);
// Редактируемая таблица
$table = 'lim_objects';
// Идентификатор строки
$row_id = 'obj_id';
// Поля для редактирования
$fields = Array(
	'obj_name' => Array( 'desc' => 'Название объекта',
			'type' => 'text',
			'required' => true
		),
	'obj_price' => Array( 'desc' => 'Цена',
			'type' => 'digit'
		),		
	'obj_picture' => Array( 'desc' => 'Фотография',
			'tag' => 'picture',
			'type' => 'text',
			'picture_type' => 'objects',
			'picture_proportions' => 4/3
		),		
	'obj_desc' => Array( 'desc' => 'Текстовое описание',
			'tag' => 'textarea',
			'type' => 'text'
		),
	'obj_full_address' => Array( 'desc' => 'Адрес',
			'type' => 'text',
			'dadata' => Array( 'type' => 'ADDRESS', 'yandex_map' => true, 'prefix' => 'obj_' )
		),
	'obj_country' => 		Array( 'type' => 'hidden' ),
	'obj_region' => 		Array( 'type' => 'hidden' ),
	'obj_city' =>			Array( 'type' => 'hidden' ),
	'obj_address' => 		Array( 'type' => 'hidden' ),
	'obj_geolocation_id' => Array( 'type' => 'hidden', 'maxlength' => 15 ),	
	'obj_atti' => Array( 'desc' => 'Широта',
			'type' => 'text',
			'maxlength' => 20
		),
	'obj_long' => Array( 'desc' => 'Долгота',
			'type' => 'text',
			'maxlength' => 20
		),
	'obj_owner_name' => Array( 'desc' => 'ФИО собственника',
			'type' => 'text'
		),		
	'obj_owner_phone' => Array( 'desc' => 'Номер телефона собственника',
			'type' => 'phone',
			'length' => 10
		),
	'obj_type' => Array( 'desc' => 'Тип объекта',
			'tag' => 'select',
			'type' => 'digit',
			'variants' => Array (
				1	=>	'1-шка',
				2	=>	'2-шка',
				3	=>	'3-шка',
				4	=>	'4-х',
				5	=>	'Более',
				6	=>	'Студия',
				7	=>	'Комната',
				8	=>	'Доля',
				9	=>	'Новостройка',
				10	=>	'Новостройка с ключами',
				11	=>	'Дом',
				12	=>	'Земля',
				13	=>	'Дом с землей',
				14	=>	'Гараж',
				15	=>	'Нежил.помещение'
			)
		),
	'obj_status' => Array( 'desc' => 'Статус',
			'tag' => 'select',
			'type' => 'digit',
			'variants' => Array (
				0	=>	'Продается',
				1	=>	'Продана',
			)
		),
	'obj_create_tst' => Array( 'desc' => 'Дата создания',
			'type' => 'timestamp',
			'access_edit' => Array ('none')
		),		
);
// Запрос списка элементов
$list_request = "SELECT * FROM `$table` WHERE `obj_id`>0 ORDER BY `obj_status` ASC, `obj_create_tst` ASC";
// Настройки колонок таблицы
$table_list_fields = Array(
	'obj_picture' => Array( 'desc' => 'Фотография',
			'href' => '/pictures/objects/{{obj_picture}}.jpg',
			'value' => '<img src="/pictures/objects/{{obj_picture}}.jpg" width="50" />'
		),
	'obj_name' => Array( 'desc' => 'Название объекта',
			'href' => '{{self}}?id={{' . $row_id . '}}',
			'value' => '{{obj_name}} ({{obj_type}})'
		),
	'obj_owner_name' => Array( 'desc' => 'Собственник' ),
	'obj_owner_phone' => Array( 'desc' => 'Телефон' )
);

function action_after_insert() {}
function action_after_edit() {}

require MC_ROOT.'/pages/table_editor_core.php'; ?>