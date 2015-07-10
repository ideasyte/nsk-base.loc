<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;} ?>
                                <table class="table table-hover table-bordered table-striped table-responsive" id="list">
                                    <tr>
                                        <th style="width: 10px">№</th>
                                        <th class="text-center">Объект</th>
                                        <th class="text-center">Цена, руб</th>
                                        <th class="text-center">Фото</th>
                                        <th class="text-center">Адрес</th>
                                        <th class="text-center">Площадь</th>
                                        <th class="text-center">Просмотры</th>
                                        <th class="text-center">События</th>
                                        <th></th>
                                    </tr>
<?
    $filter_arr = Array();
    if (isset($_POST['pay_type'])) $filter_arr[] = "`obj_pay_type`='" . intval ($_POST['pay_type']) . "'";

    if ($_POST['obj_rooms_1'] == 'true')         $filter_arr[] = "`obj_rooms_1`='1'";
    if ($_POST['obj_rooms_2'] == 'true')         $filter_arr[] = "`obj_rooms_2`='1'";
    if ($_POST['obj_rooms_3'] == 'true')         $filter_arr[] = "`obj_rooms_3`='1'";
    if ($_POST['obj_rooms_4'] == 'true')         $filter_arr[] = "`obj_rooms_4`='1'";
    if ($_POST['obj_rooms_etc'] == 'true')       $filter_arr[] = "`obj_rooms_etc`='1'";
    if ($_POST['obj_room_only'] == 'true')       $filter_arr[] = "`obj_room_only`='1'";
    if ($_POST['obj_studio'] == 'true')          $filter_arr[] = "`obj_studio`='1'";
    if ($_POST['obj_house'] == 'true')           $filter_arr[] = "`obj_house`='1'";
    if ($_POST['obj_land_only'] == 'true')      $filter_arr[] = "`obj_land_only`='1'";
    if ($_POST['obj_house_with_land'] == 'true')$filter_arr[] = "`obj_house_with_land`='1'";
    if ($_POST['obj_non_residential'] == 'true') $filter_arr[] = "`obj_non_residential`='1'";
    if ($_POST['obj_sharing'] == 'true')         $filter_arr[] = "`obj_sharing`='1'";
    if ($_POST['obj_garage'] == 'true')          $filter_arr[] = "`obj_garage`='1'";

    $price_from = intval($_POST['price_from']);
    $price_to = intval($_POST['price_to']);
    $area_from = intval($_POST['area_from']);
    $area_to = intval($_POST['area_to']);

    if (!empty($price_from))    $filter_arr[] = "`obj_price`>='$price_from'";
    if (!empty($price_to))      $filter_arr[] = "`obj_price`<='$price_to'";
    if (!empty($area_from))    $filter_arr[] = "`obj_area`>='$area_from'";
    if (!empty($area_to))    $filter_arr[] = "`obj_area`<='$area_to'";

    if (!empty($filter_arr)) $filter_query = ' WHERE ' . implode(' AND ', $filter_arr);

    $objects = db_array("SELECT * FROM `lim_objects` $filter_query" , true);
    $row = 1;
	foreach ($objects as $object) { ?>
                                <tr class="text-center">
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');"><?=$row?></td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');"><?

                                        if ($object['obj_rooms_1']) echo '1-шка<br />';
                                        else if ($object['obj_rooms_2']) echo '2-шка<br />';
                                        else if ($object['obj_rooms_3']) echo '3-шка<br />';
                                        else if ($object['obj_rooms_4']) echo '4-шка<br />';
                                        else if ($object['obj_rooms_etc']) echo '&gt; 4 комнат<br />';
                                        else if ($object['obj_room_only']) echo 'Комната<br />';

                                        if ($object['obj_studio']) echo 'Студия<br />';

                                        if ($object['obj_house']) echo '<i class="fa fa-home"></i> Дом<br />';
                                        else if ($object['obj_land_only']) echo '<i class="fa fa-tree"></i> Земля<br />';
                                        else if ($object['obj_house_with_land']) echo '<i class="fa fa-home"></i> <i class="fa fa-tree"></i> Дом с землей<br />';
                                        else if ($object['obj_non_residential']) echo '<i class="fa fa-building-o"></i> Нежилое помещение<br />';
                                        else if ($object['obj_garage']) echo '<i class="fa fa-building"></i> Гараж<br />';

                                        if ($object['obj_sharing']) echo '<i class="fa fa-pie-chart"></i> Доля<br />';

                                        if ($object['obj_secoundary'])
                                            echo '<span class="label label-default"><i class="fa fa-refresh"></i> Вторичка</span><br />';
                                        else if ($object['obj_new'])
                                            echo '<span class="label label-info"><i class="fa fa-caret-square-o-up"></i> Новостройка</span><br />';
                                        else if ($object['obj_new_with_keys'])
                                            echo '<span class="label label-success"><i class="fa fa-key"></i> Новостройка с ключами</span><br />';

                                        ?>
                                        <? /*<span class="label label-danger">Аренда до </span>*/ ?>
                                    </td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');">
                                        <?=$object['obj_price']?><br/>
                                        <?  if ($object['obj_bargain']) echo '<i class="fa fa-check-square-o"></i> торг'; ?>
                                    </td>
                                    <td><?

$pictures = db_array ("SELECT * FROM `lim_pictures`
                        WHERE   `picture_type`='objects' AND
                                `picture_element`='{$object['obj_id']}' AND
                                `picture_width`>0
                        ORDER BY `picture_tst` DESC LIMIT 1");

if (!empty($pictures)) {
    $picture_big = "/pictures/objects/{$pictures[0]['picture_filename']}.jpg";
    $picture_preview = "/pictures/objects/{$pictures[0]['picture_filename']}_min.jpg";
    if (file_exists(MC_ROOT . $picture_big) && file_exists(MC_ROOT . $picture_preview)) {
                                                ?>
        <a href="<? echo $picture_big; ?>" class="img-group-gallery" title="<?="{$object['obj_price']} руб."?>">
            <img src="<? echo $picture_preview; ?>" class="img-responsive" alt="">
        </a>
<?	}
} ?>



									</td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');">
                                        <p class="text-muted no-margin"><?=$object['obj_address'] .
                                            (($object['obj_flat']) ? "кв. {$object['obj_flat']}" : '') ?></p>
                                    </td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');"><?
                                        echo (($object['obj_area']) ? "<b>О</b>:{$object['obj_area']} " : "");
                                        echo (($object['obj_area_life']) ? "<b>Ж</b>:{$object['obj_area_life']} " : "");
                                        echo (($object['obj_area_kitchen']) ? "<b>К</b>:{$object['obj_area_kitchen']} " : ""); ?>
                                    </td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');">
                                        <!--<p class="text-muted no-margin">10.02.2015 клиент Иван Иванов</p>

                                        <p class="text-muted no-margin">11.02.2015 клиент Петр Петров</p>

                                        <p class="text-muted no-margin">14.02.2015 клиент Иван Иванов</p>

                                        <p class="text-muted no-margin">04.03.2015 клиент Иван Иванов</p>
                                        <a data-toggle="modal" data-target="#all_views" class="btn btn-default">Все
                                            просмотры <span
                                                    class="badge bg-red">531</span>
                                        </a>-->
                                    </td>
                                    <td onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');">
                                        <!--<p class="text-muted no-margin">Резерв до 16.08.2014</p>

                                        <p class="text-muted no-margin">Изменена информация</p>

                                        <p class="text-muted no-margin">Добавлен просмотр</p>
                                        <a data-toggle="modal" data-target="#all_events"
                                           class="btn btn-default bottom">Все события <span
                                                class="badge bg-red">531</span>
                                        </a>-->
                                    </td>
                                    <td>
                                        <a href="#" class="text-info" onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'view');">
                                            <i class="fa fa-fw fa-info"></i></a><br/>
                                        <?  if ($user_info['user_role'] == 'superadmin' ||
                                            $user_info['user_role'] == 'admin' ||
                                            $object['obj_user'] == $enter_user) { ?>
                                            <a href="#" class="text-success" onclick="open_modal(<?=$object['obj_id']?>, 'objects', 'edit');">
                                                <i class="fa fa-fw fa-pencil"></i>
                                            </a><br />
                                            <a href="#" class="text-danger" onclick="delete_element(<?=$object['obj_id']?>, 'objects');">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </a>
                                        <?  } ?>                                        
                                    </td>
                                </tr>
<?	} ?>
								</table>
<script type="text/javascript">
	$(document).ready(function () {
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
    });
</script>