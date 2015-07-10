<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

if (!empty($el_id)) {
	$client = db_row ("SELECT * FROM `lim_clients`, `lim_users` WHERE `user_id`=`cl_user` AND `cl_id`='$el_id'");
} else echo '<div class="modal-dialog modal-lg">ошибка!</div>';
?>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#info_all_events" data-toggle="tab">Описание</a></li>
                                <li><a href="#views_all_events" data-toggle="tab">Просмотры</a></li>
                                <li><a href="#events_all_events" data-toggle="tab">События</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="info_all_events">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
											<h3 class="box-title"><?=$client['cl_fio']?></h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <dl class="dl-horizontal">
                                                <dt>Телефон:</dt>
                                                <dd><?=$client['cl_phone']?></dd>
                                                <dt>ФИО Агента:</dt>
                                                <dd><?=$client['user_surname'].' '.$client['user_name']?></dd>
                                                <dt>Цена до:</dt>
                                                <dd><?=intval($client['cl_price_to'])?> руб.
                                                    <?  if ($client['cl_pay_type'] == 1) echo '(наличные)';
                                                        else if ($client['cl_pay_type'] == 2) echo '(ипотека)'; ?>
                                                </dd>
                                                <dt>Площадь от:</dt>
                                                <dd><?=$client['cl_area_from']?> м2</dd>
                                                <dt>Объекты:</dt>
                                                <dd><?

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

                                                    ?></dd>
                                            </dl>
                                            <h3 class="box-title">Комментарий</h3>

                                            <p><?=$client['cl_desc']?></p>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <a class="btn btn-block btn-success" onclick="open_modal(<?=$client['cl_id']?>, 'clients', 'edit');">Редактировать</a></div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="views_all_events">
                                    <div class="row margin">
                                        <table class="table table-condensed table-responsive">
                                            <tr>
                                                <th style="width: 10px">№</th>
                                                <th class="text-center">Дата добавления</th>
                                                <th class="text-center" style="width: 25%">Объекты</th>
                                                <th class="text-center">Дата просмотра</th>
                                                <th class="text-center">Комментарии</th>
                                                <th></th>
                                            </tr>
                                            <tr class="text-center">
                                                <td>1.</td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                    8</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        9</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        10</p>
                                                </td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Lorem ipsum dolor sit amet,
                                                    consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                                                    Proin
                                                    gravida dolor sit amet lacus accumsan et viverra justo commodo.
                                                    Proin sodales pulvinar temp</p></td>
                                                <td>
                                                    <a href="#add_view" data-toggle="modal" data-target="#add_view"
                                                       class="text-success"><i
                                                            class="fa fa-fw fa-pencil"></i></a><br/>
                                                    <a href="#trash" data-toggle="modal" data-target="#trash"
                                                       class="text-danger"><i class="fa fa-fw fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>1.</td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                    8</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        9</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        10</p>
                                                </td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Lorem ipsum dolor sit amet,
                                                    consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                                                    Proin
                                                    gravida dolor sit amet lacus accumsan et viverra justo commodo.
                                                    Proin sodales pulvinar temp</p></td>
                                                <td></td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>1.</td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                    8</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        9</p>

                                                    <p class="text-muted no-margin">Люберцы, ул Улица, д 25, кв
                                                        10</p>
                                                </td>
                                                <td>21.08.2014</td>
                                                <td><p class="text-muted no-margin">Lorem ipsum dolor sit amet,
                                                    consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                                                    Proin
                                                    gravida dolor sit amet lacus accumsan et viverra justo commodo.
                                                    Proin sodales pulvinar temp</p></td>
                                                <td></td>
                                            </tr>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <a data-toggle="modal" data-target="#add_view"
                                                   class="btn btn-block btn-success">Добавить просмотр</a></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="events_all_events">
                                    <div class="row margin">
                                        <table class="table no-border table-striped table-responsive">
                                            <tbody>
                                            <tr class="text-center">
                                                <td><b>22.08.2014</b></td>
                                                <td><span class="label label-primary">Резерв объекта</span></td>
                                                <td><p class="text-light-blue">Название объекта.</p></td>
                                                <td><p class="text-muted no-margin">Добавлен пользователем:
                                                    Кирилл</p>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td><b>22.08.2014</b></td>
                                                <td><span class="label label-success">Новый объект</span></td>
                                                <td><p class="text-light-blue">Название объекта.</p></td>
                                                <td><p class="text-muted no-margin">Добавлен пользователем:
                                                    Кирилл</p>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td><b>22.08.2014</b></td>
                                                <td><span class="label label-info">Новый просмотр</span></td>
                                                <td><p class="text-light-blue">Название объекта.</p></td>
                                                <td><p class="text-muted no-margin">Добавлен пользователем:
                                                    Кирилл</p>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td><b>22.08.2014</b></td>
                                                <td><span class="label label-success">Резерв объекта</span></td>
                                                <td><p class="text-light-blue">Название объекта.</p></td>
                                                <td><p class="text-muted no-margin">Добавлен пользователем:
                                                    Кирилл</p>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <a data-toggle="modal" data-target="#addevent"
                                                   class="btn btn-block btn-success">Добавить событие</a></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->
                    </div>
                </div>


<script type="text/javascript">
		
</script>