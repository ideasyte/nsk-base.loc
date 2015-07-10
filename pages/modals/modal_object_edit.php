<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

// Поиск элемента в базе
$el_id = intval($_POST['el_id']);
if (!empty($el_id)) $el_data = db_row("SELECT * FROM `lim_objects` WHERE `obj_id`='$el_id'", true);

// Проверки существования и прав доступа
if (!empty($el_id) && empty($el_data))	{
	die ('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка!</h3></div><div class="modal-body">клиент с id#' . $el_id . ' не найден!</div></div></div>');
} else if (!empty($el_id) && $enter_user != $el_data['obj_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') {
	die ('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка!</h3></div><div class="modal-body">Ваши текущие права доступа не позволяют редактировать клиентов, созданных другими пользователями!</div></div></div>');
}

// Конвертация типа жилья
if ($el_data['obj_rooms_1'])        $obj_type = 'obj_rooms_1';
if ($el_data['obj_rooms_2'])        $obj_type = 'obj_rooms_2';
if ($el_data['obj_rooms_3'])        $obj_type = 'obj_rooms_3';
if ($el_data['obj_rooms_4'])        $obj_type = 'obj_rooms_4';
if ($el_data['obj_rooms_etc'])      $obj_type = 'obj_rooms_etc';
if ($el_data['obj_room_only'])      $obj_type = 'obj_room_only';
if ($el_data['obj_studio'])         $obj_type = 'obj_studio';
if ($el_data['obj_house'])          $obj_type = 'obj_house';
if ($el_data['obj_land_only'])      $obj_type = 'obj_land_only';
if ($el_data['obj_house_with_land']) $obj_type = 'obj_house_with_land';
if ($el_data['obj_non_residential']) $obj_type = 'obj_non_residential';
if ($el_data['obj_sharing'])        $obj_type = 'obj_sharing';
if ($el_data['obj_garage'])         $obj_type = 'obj_garage';


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

// Конвертация типа жилья по новизне
if ($el_data['obj_secoundary'])     $obj_housetype = 'obj_secoundary';
if ($el_data['obj_new'])            $obj_housetype = 'obj_new';
if ($el_data['obj_new_with_keys'])  $obj_housetype = 'obj_new_with_keys';

// Гашение незаполненных дат и конвертация
if ($el_data['obj_advance_for'] == '0000-00-00') $el_data['obj_advance_for'] = '';
else $el_data['obj_advance_for'] = substr($el_data['obj_advance_for'], 8, 2) . '.' . substr($el_data['obj_advance_for'], 5, 2) . '.' . substr($el_data['obj_advance_for'], 0, 4);

if ($el_data['obj_stopped_for'] == '0000-00-00') $el_data['obj_stopped_for'] = '';
else $el_data['obj_stopped_for'] = substr($el_data['obj_stopped_for'], 8, 2) . '.' . substr($el_data['obj_stopped_for'], 5, 2) . '.' . substr($el_data['obj_stopped_for'], 0, 4);

if ($el_data['obj_deadline'] == '0000-00-00') $el_data['obj_deadline'] = '';
else $el_data['obj_deadline'] = substr($el_data['obj_deadline'], 8, 2) . '.' . substr($el_data['obj_deadline'], 5, 2) . '.' . substr($el_data['obj_deadline'], 0, 4);

// Подготовка полей комнат и долей
switch ($el_data['obj_roomstructure_']) {
    case 1: // Комната в
        $el_data['obj_total_rooms_1'] = $el_data['obj_total_rooms'];
        $el_data['obj_room_num'] = '';
        break;
    case 2: // Доля в
        $el_data['obj_total_rooms_2'] = $el_data['obj_total_rooms'];
        $el_data['obj_room_num'] = '';
        break;
    case 3: // Доля в (с указанием комнаты)
        $el_data['obj_total_rooms_3'] = $el_data['obj_total_rooms'];
        break;
    default:
        $el_data['obj_room_num'] = '';
}


?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#info" data-toggle="tab">Продавец</a></li>
                <li><a href="#views" data-toggle="tab">Просмотры</a></li>
                <li><a href="#comments" data-toggle="tab">Комментарии</a></li>
                <li><a href="#events" data-toggle="tab">События</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="info">
                    <form action="#" onsubmit="save_modal_edit(this, <?=$el_id?>, 'objects')">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-pencil"></i>
                            <h3 class="box-title">
                                <?	echo (!$el_id) ? 'Создание нового' : 'Редактирование'; ?> собственника:
                                <?=$el_data['obj_fio']?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <!-- left column -->
                                <div class="col-md-6">
                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Объект</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body">
                                        <div class="row margin">
                                            <div class="col-sm-4">
                                                <? f_input('obj_type =obj_rooms_1 ~radio ?1-шка'); ?>
                                                <? f_input('obj_type =obj_rooms_4 ~radio ?4-х'); ?>
                                                <? f_input('obj_type =obj_room_only ~radio ?Комната'); ?>
                                                <? f_input('obj_type =obj_house ~radio ?Дом'); ?>
                                                <? f_input('obj_type =obj_non_residential ~radio ?Нежилое_помещение'); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <? f_input('obj_type =obj_rooms_2 ~radio ?2-шка'); ?>
                                                <? f_input('obj_type =obj_rooms_etc ~radio ?Более'); ?>
                                                <? f_input('obj_type =obj_studio ~radio ?Студия'); ?>
                                                <? f_input('obj_type =obj_land_only ~radio ?Земля'); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <? f_input('obj_type =obj_rooms_3 ~radio ?3-шка'); ?>
                                                <? f_input('obj_type =obj_sharing ~radio ?Подселение'); ?>
                                                <? f_input('obj_type =obj_garage ~radio ?Гараж'); ?>
                                                <? f_input('obj_type =obj_house_with_land ~radio ?Земля_с_домом'); ?>
                                            </div>
                                        </div>

                                        <div class="row margin">
                                            <div class="col-sm-4">
                                                <? f_input('obj_housetype =obj_secoundary ~radio ?Вторичное'); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <? f_input('obj_housetype =obj_new ~radio ?Новостройки'); ?>
                                            </div>
                                            <div class="col-sm-4">
                                                <? f_input('obj_housetype =obj_new_with_keys ~radio ?Новостр._с_ключами'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Цена</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-9"">
                                                <? f_input('obj_price ?Укажите_цену... . >fa-rouble'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <? f_input('obj_bargain ~checkbox ?Торг'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <? f_input('obj_deposit ~checkbox ?Депозит'); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <? f_input('obj_light ~checkbox ?Свет'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <? f_input('obj_kommunals ~checkbox ?Коммунальные'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <? f_input('obj_water ~checkbox ?Вода'); ?>
                                            </div>
                                            <div class="col-xs-12">
                                                <? f_input('obj_price_desc ~textarea . ?Иное...'); ?>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Адрес</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Укажите адрес</label>
                                                <? f_input('obj_address ?Укажите_адрес... . <fa-map-marker'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Квартира</label>
                                                <? f_input('obj_flat ?... . <кв.'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Этаж / Этажность</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <? f_input('obj_floor ?... . <этаж'); ?><br />
                                                <? f_input('obj_two_floors ~checkbox ?Двухуровневая'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <? f_input('obj_floors ?... . <из'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Дом</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <? f_input('obj_housematerial =1 ~radio ?М/К'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <? f_input('obj_housematerial =2 ~radio ?Панель'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <? f_input('obj_housematerial =3 ~radio ?Кирпич'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <? f_input('obj_housematerial =4 ~radio ?Монолит'); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <? f_input('obj_housematerial_desc ~textarea . ?Иное...'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Площадь</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Общая</label>
                                                <? f_input('obj_area ?... .'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Жилая</label>
                                                <? f_input('obj_area_life ?... .'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Кухня</label>
                                                <? f_input('obj_area_kitchen ?... .'); ?><br /><br />

                                                <? /*f_input('obj_area ?Кухня-студия');*/ ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Площадь участка</label>
                                                <? f_input('obj_area_land ?... .'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>СУР</label>
                                                <? f_input('obj_area_sanuzel_r .'); ?>
                                                <? f_input('obj_sanuzel_common ~checkbox ?Общий_СУ'); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <label>СУС</label>
                                                <? f_input('obj_area_sanuzel_s .'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Лоджия</label>
                                                <? f_input('obj_area_loggia .'); ?>
                                                <? f_input('obj_balcony_warmed ~checkbox ?Утеплен(ая)'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Балкон</label>
                                                <? f_input('obj_area_balcony .'); ?>
                                                <? f_input('obj_balcony_glass ~checkbox ?Застеклен(ная)'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Мансарда</label>
                                                <? f_input('obj_area_mansard .'); ?>
                                                <? f_input('obj_balcony_glasspackets ~checkbox ?Стеклопакеты'); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <? f_input('obj_balcony_desc ~textarea . ?Иное...'); ?>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Окна</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <? f_input('obj_window_glasspackets ~checkbox ?Стеклопакеты'); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <? f_input('obj_window_orient =1 ~radio ?Двор'); ?>
                                            </div>
                                            <div class="col-md-2">
                                                <? f_input('obj_window_orient =2 ~radio ?Улица'); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <? f_input('obj_window_orient =3 ~radio ?Распашенка'); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <? f_input('obj_window_orient_desc ~textarea . ?Иное...'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <button type="button" id="upload_photo" class="btn btn-default btn-sm pull-right">
                                            <i class="fa fa-camera"></i>&nbsp;&nbsp; Добавить фото
                                        </button>
                                        <button type="button" id="upload_pbar" class="btn btn-default btn-sm pull-right" style="display: none;">
                                            <i class="fa fa-spin fa-spinner progressbar"></i>&nbsp;&nbsp; Загрузка фото
                                        </button>
                                        <h3 class="box-title">Фото</h3>
                                    </div>
                                    <!-- /.box-header -->

                                    <div class="box-body">
                                        <div class="row" id="<? echo $f_name; ?>_holder"><?

$pictures = db_array ("SELECT * FROM `lim_pictures`
                        WHERE   `picture_type`='objects' AND
                                `picture_element`='$el_id' AND
                                `picture_width`>0
                        ORDER BY `picture_tst` DESC");

if (empty($pictures)) echo '<div class="col-xs-12 text-muted">не загружено ни одной фотографии</div>';
else foreach ($pictures as $picture) {
    $picture_big = "/pictures/objects/{$picture['picture_filename']}.jpg";
    $picture_preview = "/pictures/objects/{$picture['picture_filename']}_min.jpg";
    if (file_exists(MC_ROOT . $picture_big) && file_exists(MC_ROOT . $picture_preview)) {
    ?>
        <div class="col-md-4" id="picture_<? echo $picture['picture_id']; ?>">
            <a href="<? echo $picture_big; ?>" class="img-group-gallery">
                <img src="<? echo $picture_preview; ?>" class="img-responsive" alt="">
            </a>
        </div>
<?	}
} ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="box box-success">
                                    <div class="box-header">
                                        <h3 class="box-title">Примечания</h3>
                                    </div>
                                    <!-- /.box-header -->

                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <? f_input('obj_additional_alt ~checkbox ?Альтернатива'); ?>
                                                <? f_input('obj_additional_sale_free ~checkbox ?Свободная_продажа'); ?>
                                                <? f_input('obj_additional_physically_free ~checkbox ?Физ._свободна'); ?>
                                                <? f_input('obj_additional_legacy_free ~checkbox ?Юр._свободная'); ?>
                                                <? f_input('obj_additional_through_rooms ~checkbox ?Проходные_комнаты'); ?>
                                            </div>
                                            <div class="col-md-12">
                                                <? f_input('obj_desc ~textarea . ?Примечания_и_комментарии...'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="row margin">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-block btn-success">
                                            <i class="fa fa-spin fa-spinner progressbar" style="display: none;"></i>
                                            Сохранить
                                        </button>
                                    </div>
                                </div>
                                </div>
                                <!--/left column -->

                                <!-- right column -->
                                <div class="col-md-6">
                                    <div class="box box-danger">
                                        <!-- form start -->
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Аванс до:</label>
                                                    <? f_input('obj_advance_for . >fa-calendar'); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Приостановленно до:</label>
                                                    <? f_input('obj_stopped_for . >fa-calendar'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">ФИО</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <? f_input('obj_fio . <fa-user'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Телефон</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <? f_input('obj_phone . <fa-phone'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">ФИО Агента:</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?
                                                    if ($user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $value = $enter_user;
                                                    f_input("obj_user ~select @superadmin @admin {user_id|user_fio} . =$value", false, "SELECT `user_id`, CONCAT (`user_surname`, ' ', `user_name`) AS `user_fio` FROM `lim_users`");?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Документы</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <? f_input('obj_documents_desc ~textarea .'); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <? f_input('obj_documents_status =2 ~radio ?Более'); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <? f_input('obj_documents_status =1 ~radio ?Менее'); ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <? f_input('obj_dkp . <в_ДКП'); ?>
                                                    <? f_input('obj_mortgage_possible ~checkbox ?Возможна_ипотека'); ?>
                                                    <? f_input('obj_encumbrance . <Обременение'); ?>
                                                </div>
                                            </div><hr />
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span>Файлы:</span>
                                                    <a class="btn btn-default btn-sm pull-right">
                                                        <i class="fa fa-upload"></i>&nbsp;&nbsp; Загрузить
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Комментарий</label>
                                                    <? f_input('obj_additional_desc2 ~textarea .'); ?>

                                                    <label>Банер</label>
                                                    <? f_input('obj_banner .'); ?>

                                                    <label>Ключи</label>
                                                    <? f_input('obj_keys .'); ?>

                                                    <label>Фото</label>
                                                    <? f_input('obj_photos .'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <? f_input('obj_roomstructure_ =1 ~radio ?Комната_в'); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <? f_input('obj_total_rooms_1 ?... .'); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    - комн. квартире
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <? f_input('obj_roomstructure_ =2 ~radio ?Доля_в'); ?>
                                                </div>
                                                <div class="col-md-2">
                                                    <? f_input('obj_total_rooms_2 ?... .'); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    - комн. квартире
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <? f_input('obj_roomstructure_ =3 ~radio ?Доля_в'); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <? f_input('obj_room_num ?... .'); ?>
                                                </div>
                                                <div class="col-md-1">/</div>
                                                <div class="col-md-3">
                                                    <? f_input('obj_total_rooms_3 ?... .'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label>Застройщик</label>
                                                    <? f_input('obj_developer ?... .'); ?>
                                                </div>
                                                <div class="col-md-5">
                                                    <label>Срок сдачи</label>
                                                    <? f_input('obj_deadline . >fa-calendar'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Мебель</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <? f_input('obj_furniture_desc ~textarea .'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <? f_input('obj_furniture =2 ~radio ?Есть'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <? f_input('obj_furniture =1 ~radio ?Нет'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <? f_input('obj_furniture =3 ~radio ?Обсуждается'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Ремонт</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <? f_input('obj_repaired ~textarea .'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->

                                    <div class="box box-success">
                                        <div class="box-header">
                                            <h3 class="box-title">Иное</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <? f_input('obj_additional_desc3 ~textarea .'); ?>

                                                    <? f_input('obj_additional_internet ~checkbox ?Интернет'); ?>
                                                    <? f_input('obj_additional_phone ~checkbox ?Телефон'); ?>
                                                    <? f_input('obj_additional_cabeltv ~checkbox ?Кабельное_ТВ'); ?>
                                                    <? f_input('obj_additional_domofon ~checkbox ?Домофон'); ?>
                                                    <? f_input('obj_additional_video ~checkbox ?Видеонаблюдение'); ?>
                                                    <? f_input('obj_additional_kpp ~checkbox ?КПП'); ?>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!--/.col (right) -->
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            <?
            require_once (MC_ROOT.'/pages/modals/tabs/tab_reviews.php');
            require_once (MC_ROOT.'/pages/modals/tabs/tab_comments.php');
            require_once (MC_ROOT.'/pages/modals/tabs/tab_events.php');
            ?>
</div>
</div>

<!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->
</div>

<script type="text/javascript">
    // Подсказки Dadata.ru (Кладр) при вводе адреса
    $("#obj_address").suggestions({
        serviceUrl: "https://dadata.ru/api/v2",
        token: "154fa715902b207f0c64b376646db03631fa273e",
        type: "ADDRESS",
        // Вызывается, когда пользователь выбирает одну из подсказок
        onSelect: function(suggestion) {}
    });
    // Датапикер
    $('#obj_advance_for').datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        todayHighlight: true,
        todayBtn: true
    });
    $('#obj_stopped_for').datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        todayHighlight: true,
        todayBtn: true
    });
    $('#obj_deadline').datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        todayHighlight: true,
        todayBtn: true
    });

    // Jquery-загрузчик фотографии на сервер
    $(function(){
        var btnUpload = $('#upload_photo');
        var progressbar = $('#upload_pbar');
        new AjaxUpload(btnUpload, {
            action: './scripts/php/upload-photo.php?picture_type=objects&picture_element=<?=$el_id?>',
            name: 'uploadfile',
            onSubmit: function(file, ext){
                if (! (ext && /^(jpg|jpeg)$/.test(ext))){
                    // extension is not allowed
                    alert('Разрешенный формат изображения - JPG');
                    return false;
                }
                // Высвечиваем прогресс-бар
                progressbar.show();
                btnUpload.hide();
                // Уничтожаем предыдущие картинки и плагины, если они были
                try {jcrop_api.destroy(); $('#target').remove();} catch (e) {}
            },
            onComplete: function(file, response){
                // По факту завершения загрузки гасим прогресс-бар
                console.log(response);
                progressbar.hide();
                btnUpload.show();
                var answer;
                try {answer = $.parseJSON(response);} catch (e) {}
                if (answer != undefined) {
                    if (answer.result === "ok") {
                        // Высвечиваем в окне имя закачанного файла
                        $('#modal_crop_filename').html(file);
                        // Временно закрываем модалку с объектом
                        $('#modal_large').modal('hide');
                        // Открываем модальное окно
                        $('#modal_crop').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        need_show_last_modal = true; // После загрузки фото высветить обратно модалку с редактированием объекта
                        // Открываем картинку в модальном окне
                        $('#modal_crop_body').html('<img src="/pictures/tmp/compact/'+answer.filename+'" id="target" alt="" />');
                        // По факту подгрузки картинки навешиваем на нее Crop-плагин
                        $('#target').load(function() {initJcrop(4/3);});
                        // Запоминаем id текущего изображения
                        cur_crop_picture_id = answer.picture_id;
                    } else alert('Ошибка!\r\n' + answer.error_txt);
                } else alert('Ошибка соединения с сервером!');
            }
        });
    });

    var colorbox_options = {
        rel: 	'gallery-images',
        maxWidth: 	'90%',
        maxHeight:	'90%',
        opacity: 0.7,
        current:'{current} из {total}',
    };

    // Colorbox
    $(window).ready(function(){
        $(".img-group-gallery").colorbox(colorbox_options);
    });

</script>