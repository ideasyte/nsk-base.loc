<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

if (!empty($el_id)) {
	$object = db_row ("SELECT * FROM `lim_objects` WHERE `obj_id`='$el_id'");
}
?>
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="nav-tabs-custom">
<ul class="nav nav-tabs">
    <li><a href="#info" data-toggle="tab">Описание</a></li>
    <li class="active"><a href="#views" data-toggle="tab">Просмотры</a></li>
    <li><a href="#comments" data-toggle="tab">Комментарии</a></li>
    <li><a href="#events" data-toggle="tab">События</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane" id="info">
    <div class="box box-solid">
        <div class="box-header with-border">
            <i class="fa fa-eye"></i>

            <h3 class="box-title">{{ object.name }}</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row margin">
                <div class="col-md-6">
                    <dl class="dl-horizontal">
                        <dt>Дата:</dt>
                        <dd>21.08.2014</dd>
                        <dt>Адрес:</dt>
                        <dd>Улица, дом, корпус</dd>
                        <dt>Тип:</dt>
                        <dd>Комната</dd>
                        <dt>ФИО:</dt>
                        <dd>Фамилия Имя Отчество</dd>
                        <dt>Телефон:</dt>
                        <dd>+79295930716</dd>
                        <dt>Этаж/Этажность:</dt>
                        <dd>11/18</dd>
                        <dt>Общая площадь:</dt>
                        <dd>30 м2</dd>
                        <dt>Жилая площадь:</dt>
                        <dd>20 м2</dd>
                        <dt>Кухня:</dt>
                        <dd>10 м2</dd>
                        <dt>Дом:</dt>
                        <dd>Панель</dd>
                        <dt>Лоджия:</dt>
                        <dd>Утеплённая</dd>
                        <dt>Сур</dt>
                        <dd>-</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <h3 class="panel-title"><b>Цена: 10 000 000 руб.</b></h3>
                    <small>Торг</small>
                    <div class="col-md-12">
                        <img src="http://placehold.it/350x199/cccccc/ffffff">
                    </div>
                    <!-- End Carousel Inner -->
                    <div class="col-md-12">
                        <div class="row">
                            <ul class="nav nav-pills margin">
                                <li><a data-gal="prettyPhoto[view]"
                                       href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                       title="La Chaux De Fonds"><img
                                            src="http://placehold.it/90x77"></a>
                                </li>
                                <li><a data-gal="prettyPhoto[view]"
                                       href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                       title="La Chaux De Fonds"><img
                                            src="http://placehold.it/90x77"></a>
                                </li>
                                <li><a data-gal="prettyPhoto[view]"
                                       href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                       title="La Chaux De Fonds"><img
                                            src="http://placehold.it/90x77"></a>
                                </li>
                                <li><a data-gal="prettyPhoto[view]"
                                       href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                       title="La Chaux De Fonds"><img
                                            src="http://placehold.it/90x77"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Carousel -->
                </div>
            </div>

            <div class="row margin">
                <div class="col-md-6">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                        euismod
                        bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et
                        viverra
                        justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque
                        penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus.
                        Nodio.</p>
                </div>
                <div class="col-md-6">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                        euismod
                        bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et
                        viverra
                        justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque
                        penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus.
                        Nodio.</p>
                </div>
                <div class="col-md-6">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                        euismod
                        bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et
                        viverra
                        justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque
                        penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus.
                        Nodio.</p>

                    <div class="radio">
                        В ДКП:
                        <label>
                            <input type="radio" name="optionsRadios" id="optionsRadios1"
                                   value="option1" checked="">
                            более 3х лет
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean
                        euismod
                        bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et
                        viverra
                        justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque
                        penatibus et magnis dis parturient montes, nascetur ridiculus
                        mus.
                        Nodio.</p>
                </div>
            </div>

            <div class="row margin">
                <div class="col-md-3">
                    <a data-toggle="modal" data-target="#info_edit"
                       class="btn btn-block btn-success">Редактировать</a></div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<!-- /.tab-pane -->
<div class="tab-pane active" id="views">
    <div class="row margin">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <tr>
                <th style="width: 10px">№</th>
                <th class="text-center">Дата добавления</th>
                <th class="text-center" style="width: 25%">Клиент</th>
                <th class="text-center">Дата просмотра</th>
                <th class="text-center">Резерв</th>
                <th class="text-center">Комментарии</th>
                <th></th>
            </tr>
            <tr class="text-center">
                <td>1.</td>
                <td>21.08.2014</td>
                <td><p class="text-muted no-margin">Фамилия Имя</p></td>
                <td>21.08.2014</td>
                <td>Дата: 21.08.2014</td>
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
                <td><p class="text-muted no-margin">Фамилия Имя</p></td>
                <td>21.08.2014</td>
                <td>Дата: 21.08.2014</td>
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
                <td><p class="text-muted no-margin">Фамилия Имя</p></td>
                <td>21.08.2014</td>
                <td>Дата: 21.08.2014</td>
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
        </table>
        <div class="row">
            <div class="col-md-3 pull-right">
                <a data-toggle="modal" data-target="#add_view"
                   class="btn btn-block btn-success">Добавить просмотр</a></div>
        </div>
    </div>
</div>
<!-- /.tab-pane -->
<div class="tab-pane" id="comments">
    <div class="row margin">
        <div class="box box-success">
            <div class="box-body chat" id="chat-box">
                <!-- chat item -->
                <div class="item">
                    <img src="dist/img/user4-128x128.jpg" alt="user image"
                         class="online"/>

                    <p class="message">
                        <a href="#" class="name">
                            <small class="text-muted pull-right"><i
                                    class="fa fa-clock-o"></i> 2:15
                            </small>
                            Mike Doe
                        </a>
                        I would like to meet you to discuss the latest news about
                        the arrival of the new theme. They say it is going to be one the
                        best themes on the market
                    </p>
                    <div class="attachment">
                        <h4>Прикрепленные файлы:</h4>

                        <p class="filename">
                            <a href="https://codeload.github.com/almasaeed2010/AdminLTE/zip/v2.0.3"
                               target="_blank" class="margin">AdminLTE.zip</a>
                            <a href="https://codeload.github.com/almasaeed2010/AdminLTE/zip/v2.0.3"
                               target="_blank" class="margin">AdminLTE.zip</a>
                            <a href="https://codeload.github.com/almasaeed2010/AdminLTE/zip/v2.0.3"
                               target="_blank" class="margin">AdminLTE.zip</a>
                        </p>
                        <hr/>
                        <h4>Прикрепленные фото:</h4>

                        <div class="col-md-12 filename">
                            <div class="row">
                                <ul class="nav nav-pills margin">
                                    <li><a data-gal="prettyPhoto[view_comments]"
                                           href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                           title="La Chaux De Fonds"><img
                                                src="http://placehold.it/90x77"></a>
                                    </li>
                                    <li><a data-gal="prettyPhoto[view_comments]"
                                           href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                           title="La Chaux De Fonds"><img
                                                src="http://placehold.it/90x77"></a>
                                    </li>
                                    <li><a data-gal="prettyPhoto[view_comments]"
                                           href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                           title="La Chaux De Fonds"><img
                                                src="http://placehold.it/90x77"></a>
                                    </li>
                                    <li><a data-gal="prettyPhoto[view_comments]"
                                           href="http://www.wowthemes.net/demo/serenity/img/temp/masonry/1.jpg"
                                           title="La Chaux De Fonds"><img
                                                src="http://placehold.it/90x77"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- /.attachment -->
                </div>
                <!-- /.item -->
                <!-- chat item -->
                <div class="item">
                    <img src="dist/img/user3-128x128.jpg" alt="user image"
                         class="offline"/>

                    <p class="message">
                        <a href="#" class="name">
                            <small class="text-muted pull-right"><i
                                    class="fa fa-clock-o"></i> 5:15
                            </small>
                            Alexander Pierce
                        </a>
                        I would like to meet you to discuss the latest news about
                        the arrival of the new theme. They say it is going to be one the
                        best themes on the market
                    </p>
                </div>
                <!-- /.item -->
                <!-- chat item -->
                <div class="item">
                    <img src="dist/img/user2-160x160.jpg" alt="user image"
                         class="offline"/>

                    <p class="message">
                        <a href="#" class="name">
                            <small class="text-muted pull-right"><i
                                    class="fa fa-clock-o"></i> 5:30
                            </small>
                            Susan Doe
                        </a>
                        I would like to meet you to discuss the latest news about
                        the arrival of the new theme. They say it is going to be one the
                        best themes on the market
                    </p>
                </div>
                <!-- /.item -->
            </div>
            <!-- /.chat -->
            <div class="box-footer">
                <textarea class="form-control" rows="3"
                          placeholder="Введите текст комментария ..."></textarea>

                <div class="row">
                    <div class="col-md-3 pull-right">
                        <a data-toggle="modal" data-target="#submit"
                           class="btn btn-block btn-success">Отправить комментарий</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box (chat box) -->
    </div>
</div>
<!-- /.tab-pane -->
<div class="tab-pane" id="events">
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
        <div class="row margin">
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