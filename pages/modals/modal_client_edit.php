<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

// Поиск элемента в базе
$el_id = intval($_POST['el_id']);
if (!empty($el_id)) $el_data = db_row("SELECT * FROM `lim_clients` WHERE `cl_id`='$el_id'");

// Проверки существования и прав доступа
if (!empty($el_id) && empty($el_data))	{
	die ('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка!</h3></div><div class="modal-body">клиент с id#' . $el_id . ' не найден!</div></div></div>');
} else if (!empty($el_id) && $enter_user != $el_data['cl_user'] && $user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') {
	die ('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка!</h3></div><div class="modal-body">Ваши текущие права доступа не позволяют редактировать клиентов, созданных другими пользователями!</div></div></div>');
}
?>
				<div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#edit" data-toggle="tab">Редактирование</a></li>
                                <li><a href="#views_edit" data-toggle="tab">Просмотры</a></li>
                                <li><a href="#events_edit" data-toggle="tab">События</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="edit">
								<form action="#" onsubmit="save_modal_edit(this, <?=$el_id?>, 'clients')">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <i class="fa fa-pencil"></i>
                                            <h3 class="box-title">
												<?	echo (!$el_id) ? 'Создание нового' : 'Редактирование'; ?> клиента: 
												<?=$el_data['cl_fio']?>
											</h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="row margin">
                                                <div class="col-md-6"><? f_input('cl_fio ?ФИО_Клиента . <fa-user'); ?></div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6"><? f_input('cl_phone ?Номер_телефона . <fa-phone'); ?></div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
													<label>ФИО Агента:</label>
													<? 
													if ($user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $value = $enter_user;
													f_input("cl_user ~select @superadmin @admin {user_id|user_fio} . =$value", false, "SELECT `user_id`, CONCAT (`user_surname`, ' ', `user_name`) AS `user_fio` FROM `lim_users`");?>
												</div>
                                            </div>

                                            <div class="row margin small">
                                                <div class="col-sm-2">
													<? f_input('cl_rooms_1 ~checkbox ?1-шка'); ?>
													<? f_input('cl_rooms_4 ~checkbox ?4-х'); ?>
													<? f_input('cl_room_only ~checkbox ?Комната'); ?>
                                                    <? f_input('cl_house ~checkbox ?Дом'); ?>
                                                    <? f_input('cl_non_residential ~checkbox ?Нежилое_помещение'); ?>
                                                </div>
                                                <div class="col-sm-2">
													<? f_input('cl_rooms_2 ~checkbox ?2-шка'); ?>
													<? f_input('cl_rooms_etc ~checkbox ?Более'); ?>
                                                    <? f_input('cl_studio ~checkbox ?Студия'); ?>
                                                    <? f_input('cl_land_only ~checkbox ?Земля'); ?>
                                                </div>
                                                <div class="col-sm-2">
													<? f_input('cl_rooms_3 ~checkbox ?3-шка'); ?>
													<? f_input('cl_sharing ~checkbox ?Подселение'); ?>
													<? f_input('cl_garage ~checkbox ?Гараж'); ?>
                                                    <? f_input('cl_house_with_land ~checkbox ?Земля_с_домом'); ?>
                                                </div>
                                            </div>

                                            <div class="row margin small">
                                                <div class="col-sm-2">
                                                    <? f_input('cl_secoundary ~checkbox ?Вторичное'); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <? f_input('cl_new ~checkbox ?Новостройки'); ?>
                                                </div>
                                                <div class="col-sm-2">
                                                    <? f_input('cl_new_with_keys ~checkbox ?Новостр._с_ключами'); ?>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
													<label>Цена до:</label>
													<? f_input('cl_price_to ?Укажите_конечную_цену... . >fa-rouble'); ?>
  
                                                    <div class="checkbox">
                                                        <? f_input('cl_pay_type =1 ~radio ?Наличные'); ?>
														<? f_input('cl_pay_type =2 ~radio ?Ипотека'); ?>
														<? f_input('cl_pay_type =0 ~radio ?Иное'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <label>Площадь от:</label>
													<? f_input('cl_area_from ?Укажите_минимальную_площадь... . >кв.м'); ?>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <label>Комментарий:</label>
													<? f_input('cl_desc ~textarea ?Введите_текст .'); ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-block btn-success">
														<i class="fa fa-spin fa-spinner progressbar" style="display: none;"></i>
														Сохранить
													</button>
												</div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </form>
								</div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="views_edit">
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
                                <div class="tab-pane" id="events_edit">
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