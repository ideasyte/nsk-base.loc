<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

if (!empty($el_id)) {
	$el_data = db_row ("SELECT *, DATE_FORMAT(`obj_create_tst`,'%d.%m.%Y') AS `obj_date`
                        FROM `lim_objects`, `lim_users`
                        WHERE `user_id`=`obj_user` AND
                              `obj_id`='$el_id'");
} else echo '<div class="modal-dialog modal-lg">ошибка!</div>';


?>
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
    <li class="active"><a href="#info" data-toggle="tab">Описание</a></li>
    <li><a href="#views" data-toggle="tab">Просмотры</a></li>
    <li><a href="#comments" data-toggle="tab">Комментарии</a></li>
    <li><a href="#events" data-toggle="tab">События</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="info">
    <div class="box box-solid">
        <div class="box-header with-border">
            <i class="fa fa-eye"></i>

            <h3 class="box-title"><?=$el_data['obj_fio']?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row margin">
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt>Дата:</dt><dd><?=$el_data['obj_date']?></dd>
                        <dt>Адрес:</dt><dd><?=$el_data['obj_address'] .
                            (($el_data['obj_flat']) ? "кв. {$el_data['obj_flat']}" : '') ?></dd>

                        <dt>Тип:</dt>
                        <dd><?

                            if ($el_data['obj_rooms_1']) echo '1-шка<br />';
                            else if ($el_data['obj_rooms_2']) echo '2-шка<br />';
                            else if ($el_data['obj_rooms_3']) echo '3-шка<br />';
                            else if ($el_data['obj_rooms_4']) echo '4-шка<br />';
                            else if ($el_data['obj_rooms_etc']) echo '&gt; 4 комнат<br />';
                            else if ($el_data['obj_room_only']) echo 'Комната<br />';

                            if ($el_data['obj_studio']) echo 'Студия<br />';

                            if ($el_data['obj_house']) echo '<i class="fa fa-home"></i> Дом<br />';
                            else if ($el_data['obj_land_only']) echo '<i class="fa fa-tree"></i> Земля<br />';
                            else if ($el_data['obj_house_with_land']) echo '<i class="fa fa-home"></i> <i class="fa fa-tree"></i> Дом с землей<br />';
                            else if ($el_data['obj_non_residential']) echo '<i class="fa fa-building-o"></i> Нежилое помещение<br />';
                            else if ($el_data['obj_garage']) echo '<i class="fa fa-building"></i> Гараж<br />';

                            if ($el_data['obj_sharing']) echo '<i class="fa fa-pie-chart"></i> Доля<br />';

                            if ($el_data['obj_secoundary'])
                                echo '<span class="label label-default"><i class="fa fa-refresh"></i> Вторичка</span><br />';
                            else if ($el_data['obj_new'])
                                echo '<span class="label label-info"><i class="fa fa-caret-square-o-up"></i> Новостройка</span><br />';
                            else if ($el_data['obj_new_with_keys'])
                                echo '<span class="label label-success"><i class="fa fa-key"></i> Новостройка с ключами</span><br />';

                            ?>
                        </dd>
                        <br />

                        <dt>ФИО агента:</dt><dd><?=$el_data['user_surname'].' '.$el_data['user_name']?></dd>
                        <dt>Телефон:</dt><dd><?=($el_data['obj_phone']) ? "+7{$el_data['obj_phone']}" : ""?></dd>
                        <dt>Этаж/Этажность:</dt><dd><?="{$el_data['obj_floor']} / {$el_data['obj_floors']}"?></dd>

                        <dt>Общая площадь:</dt><dd><?=($el_data['obj_area']) ? "{$el_data['obj_area']} м<sup>2</sup>" : ""?></dd>
                        <dt>Жилая площадь:</dt><dd><?=($el_data['obj_area_life']) ? "{$el_data['obj_area_life']} м<sup>2</sup>" : ""?></dd>
                        <dt>Кухня:</dt><dd><?=($el_data['obj_area_kitchen']) ? "{$el_data['obj_area_kitchen']} м<sup>2</sup>" : ""?></dd>
                        <br />

                        <dt>Дом:</dt>
                        <dd><?
                            switch ($el_data['obj_housematerial']) {
                                case 1: echo 'М/К'; break;
                                case 2: echo 'панель'; break;
                                case 3: echo 'кирпич'; break;
                                case 4: echo 'монолит'; break;
                                default: echo 'не указан'; break;
                            }
                            ?>
                        </dd>

                        <dt>Лоджия:</dt>
                        <dd><?
                            $loggia_arr = Array();
                            if ($el_data['obj_balcony_warmed']) $loggia_arr[] = 'утепленная';
                            if ($el_data['obj_balcony_glass']) $loggia_arr[] = 'застекленная';
                            if ($el_data['obj_balcony_glasspackets']) $loggia_arr[] = 'стеклопакеты';
                            echo implode(', ', $loggia_arr);
                            ?>
                        </dd>
                        <dt>Сур</dt>
                        <dd>-</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h3 class="panel-title">
                        <strong>Цена: <?=$el_data['obj_price']?> руб.</strong>
                        <? if ($el_data['obj_bargain']) echo '<small>Торг</small>'; ?>
                    </h3><hr />

                    <div class="col-md-12">
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
            </div>

            <div class="row margin">
                <div class="col-md-6">
                    <?=($el_data['obj_furniture_desc']) ? "<h4>Мебель:</h4><p>{$el_data['obj_furniture_desc']}</p>" : ""?>
                </div>
                <div class="col-md-6">
                    <?=($el_data['obj_repaired']) ? "<h4>Ремонт:</h4><p>{$el_data['obj_repaired']}</p>" : ""?>
                </div>
                <div class="col-md-6">
                    <?=($el_data['obj_documents_desc']) ? "<h4>Документы:</h4><p>{$el_data['obj_documents_desc']}</p>" : ""?>

                    <div class="radio">
                        В ДКП:
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios1"
                                   value="option1" checked="">
                            <?=$el_data['obj_dkp']?>
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <?=($el_data['obj_desc']) ? "<h4>Документы:</h4><p>{$el_data['obj_desc']}</p>" : ""?>
                </div>
            </div>

            <div class="row margin">
                <div class="col-md-3">
                    <a class="btn btn-block btn-success" onclick="open_modal(<?=$el_data['obj_id']?>, 'objects', 'edit');">Редактировать</a></div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
    <?
    require_once (MC_ROOT.'/pages/modals/tabs/tab_reviews.php');
    require_once (MC_ROOT.'/pages/modals/tabs/tab_comments.php');
    require_once (MC_ROOT.'/pages/modals/tabs/tab_events.php');
    ?>

</div>
<!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->
</div>
</div>
<script type="text/javascript">
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