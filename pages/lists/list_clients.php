<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;} ?>
                                <table class="table table-hover table-bordered table-striped table-responsive" id="list">
                                    <tr>
                                        <th style="width: 10px">№</th>
                                        <th class="text-center">Объект</th>
                                        <th class="text-center">Цена до, руб</th>
                                        <th class="text-center">ФИО</th>
                                        <th class="text-center">Просмотры</th>
                                        <th class="text-center">События</th>
                                        <th></th>
                                    </tr>
								
<?	$filter_arr = Array();
    if (isset($_POST['pay_type'])) $filter_arr[] = "`cl_pay_type`='" . intval ($_POST['pay_type']) . "'";

    if ($_POST['cl_rooms_1'] == 'true')         $filter_arr[] = "`cl_rooms_1`='1'";
    if ($_POST['cl_rooms_2'] == 'true')         $filter_arr[] = "`cl_rooms_2`='1'";
    if ($_POST['cl_rooms_3'] == 'true')         $filter_arr[] = "`cl_rooms_3`='1'";
    if ($_POST['cl_rooms_4'] == 'true')         $filter_arr[] = "`cl_rooms_4`='1'";
    if ($_POST['cl_rooms_etc'] == 'true')       $filter_arr[] = "`cl_rooms_etc`='1'";
    if ($_POST['cl_room_only'] == 'true')       $filter_arr[] = "`cl_room_only`='1'";
    if ($_POST['cl_studio'] == 'true')          $filter_arr[] = "`cl_studio`='1'";
    if ($_POST['cl_house'] == 'true')           $filter_arr[] = "`cl_house`='1'";
    if ($_POST['cl_land_only'] == 'true')      $filter_arr[] = "`cl_land_only`='1'";
    if ($_POST['cl_house_with_land'] == 'true')$filter_arr[] = "`cl_house_with_land`='1'";
    if ($_POST['cl_non_residential'] == 'true') $filter_arr[] = "`cl_non_residential`='1'";
    if ($_POST['cl_sharing'] == 'true')         $filter_arr[] = "`cl_sharing`='1'";
    if ($_POST['cl_garage'] == 'true')          $filter_arr[] = "`cl_garage`='1'";

    $price_from = intval($_POST['price_from']);
    $price_to = intval($_POST['price_to']);
    $area_from = intval($_POST['area_from']);
    $area_to = intval($_POST['area_to']);

    if (!empty($price_from))    $filter_arr[] = "`cl_price_to`>='$price_from'";
    if (!empty($price_to))      $filter_arr[] = "`cl_price_to`<='$price_to'";
    if (!empty($area_from))    $filter_arr[] = "`cl_area_from`>='$area_from'";
    if (!empty($area_to))    $filter_arr[] = "`cl_area_from`<='$area_to'";

    if (!empty($filter_arr)) $filter_query = ' WHERE ' . implode(' AND ', $filter_arr);
    $clients = db_array("SELECT * FROM `lim_clients` $filter_query" , true);
	$row = 1;
	foreach ($clients as $client) { ?>
                                    <tr class="text-center item">
                                        <td onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'view');"><?=$row?></td>
                                        <td onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'view');"><?

                                            if ($client['cl_rooms_1']) echo '1-шка<br />';
                                            if ($client['cl_rooms_2']) echo '2-шка<br />';
                                            if ($client['cl_rooms_3']) echo '3-шка<br />';
                                            if ($client['cl_rooms_4']) echo '4-шка<br />';
                                            if ($client['cl_rooms_etc']) echo '&gt; 4 комнат<br />';
                                            if ($client['cl_room_only']) echo 'Комната<br />';
                                            if ($client['cl_studio']) echo 'Студия<br />';
                                            if ($client['cl_house']) echo '<i class="fa fa-home"></i> Дом<br />';
                                            if ($client['cl_land_only']) echo '<i class="fa fa-tree"></i> Земля<br />';
                                            if ($client['cl_house_with_land']) echo '<i class="fa fa-home"></i> <i class="fa fa-tree"></i> Дом с землей<br />';
                                            if ($client['cl_non_residential']) echo '<i class="fa fa-building-o"></i> Нежилое помещение<br />';
                                            if ($client['cl_sharing']) echo '<i class="fa fa-pie-chart"></i> Доля<br />';
                                            if ($client['cl_garage']) echo '<i class="fa fa-building"></i> Гараж<br />';

                                            if ($client['cl_secoundary'])
                                                echo '<span class="label label-default"><i class="fa fa-refresh"></i> Вторичка</span><br />';
                                            if ($client['cl_new'])
                                                echo '<span class="label label-info"><i class="fa fa-caret-square-o-up"></i> Новостройки</span><br />';
                                            if ($client['cl_new_with_keys'])
                                                echo '<span class="label label-success"><i class="fa fa-key"></i> Новостройки с ключами</span><br />';

                                            ?>
                                        </td>
                                        <td onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'view');">
                                            <strong><?=$client['cl_price_to']?></strong><br />
                                            <?  switch ($client['cl_pay_type']) {
                                                    case 0: echo '<span class="label label-warning">Иное</span>'; break;
                                                    case 1: echo '<span class="label label-success">Наличка</span>'; break;
                                                    case 2: echo '<span class="label label-info">Ипотека</span>'; break;
                                                } ?>
                                        </td>
                                        <td onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'view');">
											<b><?=$client['cl_fio']?></b>
										</td>
                                        <td>
											<? /*
											<p class="text-muted no-margin">10.02.2015 клиент Иван Иванов</p>
											
                                            <a class="btn btn-default">
												Все просмотры 
												<span class="badge bg-red">531</span>
                                            </a>
											*/ ?>
										</td>
                                        <td>
											<? /*
											<p class="text-muted no-margin">10.02.2015 клиент Иван Иванов</p>
											
                                            <a class="btn btn-default">
												Все события 
												<span class="badge bg-red">531</span>
                                            </a>
											*/ ?>
                                        </td>
                                        <td>
											<a href="#" class="text-info" onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'view');">
												<i class="fa fa-fw fa-info"></i></a><br/>
                                            <?  if ($user_info['user_role'] == 'superadmin' ||
                                                    $user_info['user_role'] == 'admin' ||
                                                    $client['cl_user'] == $enter_user) { ?>
                                                    <a href="#" class="text-success" onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'edit');">
                                                        <i class="fa fa-fw fa-pencil"></i>
                                                    </a><br />
                                                    <a href="#" class="text-danger" onclick="delete_element(<?=$client['cl_id']?>, 'clients');">
                                                        <i class="fa fa-fw fa-trash"></i>
                                                    </a>
                                            <?  } ?>
                                        </td>
                                    </tr>
<?		$row++;
	} ?>
								</table>