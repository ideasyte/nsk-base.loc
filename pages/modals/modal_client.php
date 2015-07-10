<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

if (!empty($el_id)) {
	$client = db_row ("SELECT * FROM `lim_objects` WHERE `cl_id`='$el_id'");
}
?>
<div class="modal-dialog modal-md">
	<div class="modal-content">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#edit" data-toggle="tab">Редактирование</a></li>
                                <li><a href="#views_edit" data-toggle="tab">Просмотры</a></li>
                                <li><a href="#events_edit" data-toggle="tab">События</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="edit">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <i class="fa fa-pencil"></i>

                                            <h3 class="box-title">Редактирование клиента: <?=$client['cl_fio']?></h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i
                                                                class="fa fa-clock-o"></i></span>
                                                        <input type="date" class="form-control"
                                                               placeholder="Дата и время просмотра">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control"
                                                               placeholder="ФИО Клиента">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-phone"></i></span>
                                                        <input type="text" class="form-control" id="phone"
                                                               placeholder="Телефон" data-toggle="tooltip"
                                                               data-placement="top" title=""
                                                               data-original-title="Предупреждение: введеный номер
                                                            уже заполнен у клиента: {{ user.full_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control"
                                                               placeholder="ФИО Агента">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-sm-2">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> 1-шка
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> 4-х
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Комната
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Нежилое помещение
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> 2-шка
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Более
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Дом
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> 3-шка
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Подселение
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> Гараж
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control"
                                                               placeholder="Цена до:">
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"> 3-шка
                                                        </label>
                                                        <label>
                                                            <input type="checkbox"> Подселение
                                                        </label>
                                                        <label>
                                                            <input type="checkbox"> Гараж
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="fa fa-user"></i></span>
                                                        <input type="text" class="form-control"
                                                               placeholder="Площадь от:">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row margin">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="3"
                                                                  placeholder="Комментарий"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <a data-toggle="modal" data-target="#edit"
                                                       class="btn btn-block btn-success">Редактировать</a></div>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
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