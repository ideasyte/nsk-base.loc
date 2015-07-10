<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if ($user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') {header("Location: login.php"); exit;}
// Склонение названия элемента редактирования
$messages = Array(
	'page_name' => 			'Пользователи',
	'elements_list' => 		'Список пользователей',
	'delete_success' => 	'Пользователь успешно удален',
	'delete_error' => 		'Ошибка при удалении пользователя',
	'create_success' => 	'Пользователь успешно создан',
	'save_success' => 		'Информация о пользователе успешно отредактирована',
	'create_error' => 		'Ошибка при создании пользователя',
	'save_error' => 		'Ошибка при сохранении данных пользователя',
	'edit_element' => 		'Редактирование пользователя',
	'create_element' => 	'Добавление нового пользователя',
	'new_element' => 		'Добавить пользователя'
);
// Редактируемая таблица
$table = 'lim_users';
// Идентификатор строки
$row_id = 'user_id';
// Поля для редактирования
$fields = Array(
	'user_surname' => Array( 'desc' => 'Фамилия',
			'type' => 'text',
			'required' => true
		),
	'user_name' => Array( 'desc' => 'Имя',
			'type' => 'text',
			'required' => true
		),
	'user_login' => Array( 'desc' => 'Логин',
			'type' => 'text',
			'required' => true
		),
	'user_pass' => Array( 'desc' => 'Пароль',
			'type' => 'text',
			'required' => true
		),
	'user_avatar' => Array( 'desc' => 'Аватар',
			'tag' => 'picture',
			'type' => 'text',
			'picture_type' => 'avatar',
			'picture_proportions' => 1
		),
	'user_role' => Array( 'desc' => 'Роль в системе',
			'tag' => 'select',
			'type' => 'text',
			'variants' => Array (
				'admin'	=>	'admin',
				'user'	=>	'user'
			)
		),
	'user_reg_tst' => Array( 'desc' => 'Дата регистрации',
			'type' => 'timestamp',
			'access_edit' => Array ('none')
		)		
);
// Запрос списка элементов
if ($user_info['user_role'] == 'superadmin') 
	$list_request = "SELECT * FROM `$table` WHERE `user_role`<>'superadmin' ORDER BY `user_id` ASC";
else 
	$list_request = "SELECT * FROM `$table` WHERE `user_role`!='superadmin' AND `user_role`!='admin' ORDER BY `user_id` ASC";
	
// Настройки колонок таблицы
$table_list_fields = Array(
	'user_name' => Array( 'desc' => 'Имя',
			'href' => '{{self}}?id={{' . $row_id . '}}'
		),
	'user_role' => Array( 'desc' => 'Роль' )
);

function action_after_insert() {}
function action_after_edit() {}

require MC_ROOT.'/pages/table_editor_core.php'; ?>
