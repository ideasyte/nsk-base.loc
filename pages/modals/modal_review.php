<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');

// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

if (!empty($el_id)) {
    $el_data = db_row ("SELECT *
                        FROM `lim_reviews`, `lim_users`
                        WHERE `user_id`=`rw_user` AND
                              `rw_id`='$el_id'");
} else $el_data = Array();

?>
<div class="modal-dialog modal-lg">
<div class="modal-content">

    <form action="#" onsubmit="save_modal_edit(this, <?=$el_id?>, 'reviews')">
    <div class="box box-solid">
        <div class="box-header with-border">
            <i class="fa fa-pencil"></i>

            <h3 class="box-title"><?=(!empty($el_id)) ? 'Редактирование' : 'Создание'?> просмотра</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row margin">
                <div class="col-xs-6">
                    <label>Дата просмотра</label>
                    <? f_input('rw_review_date . >fa-calendar'); ?>
                </div>

                <div class="col-xs-6">
                    <label>Дата резерва</label>
                    <? f_input('rw_reserve . >fa-calendar'); ?>
                    <? /*
                        // TODO добавить выбор клиента
                       if ($user_info['user_role'] != 'superadmin' && $user_info['user_role'] != 'admin') $value = $enter_user;
                       f_input("obj_user ~select @superadmin @admin {user_id|user_fio} . =$value", false, "SELECT `user_id`, CONCAT (`user_surname`, ' ', `user_name`) AS `user_fio` FROM `lim_users`");?>

                     <label>Время просмотра</label>
                    <input type="time" class="form-control" placeholder="Время просмотра"> */ ?>
                </div>
            </div>

            <? /*<div class="row margin">
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header">
                            <i class="ion ion-eye"></i>

                            <h3 class="box-title">Показать объекты</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="todo-list">
                                <li>
                                    <!-- todo text -->
                                    <span class="text">Люберцы, ул Улица, д 25, кв 8</span>
                                    <!-- Emphasis label -->
                                    <small class="label label-danger"><i class="fa fa-clock-o"></i>
                                        2 минуты назад
                                    </small>
                                    <!-- General tools such as edit or delete-->
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="text">Люберцы, ул Улица, д 25, кв 9</span>
                                    <small class="label label-info"><i class="fa fa-clock-o"></i> 4
                                        часа назад
                                    </small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="text">Люберцы, ул Улица, д 25, кв 10</span>
                                    <small class="label label-warning"><i class="fa fa-clock-o"></i>
                                        1 день назад
                                    </small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <a data-toggle="modal" data-target="#add_object" class="btn btn-default pull-right"><i class="fa fa-plus"></i>
                                Добавить объект</a>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header">
                            <i class="ion ion-plus"></i>

                            <h3 class="box-title">Предложить объекты</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="todo-list">
                                <li>
                                    <!-- todo text -->
                                    <span class="text">Люберцы, ул Улица, д 25, кв 8</span>
                                    <!-- Emphasis label -->
                                    <small class="label label-danger"><i class="fa fa-clock-o"></i>
                                        2 минуты назад
                                    </small>
                                    <!-- General tools such as edit or delete-->
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="text">Люберцы, ул Улица, д 25, кв 9</span>
                                    <small class="label label-info"><i class="fa fa-clock-o"></i> 4
                                        часа назад
                                    </small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                                <li>
                                    <span class="text">Люберцы, ул Улица, д 25, кв 10</span>
                                    <small class="label label-warning"><i class="fa fa-clock-o"></i>
                                        1 день назад
                                    </small>
                                    <div class="tools">
                                        <i class="fa fa-edit"></i>
                                        <i class="fa fa-trash-o"></i>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix no-border">
                            <a data-toggle="modal" data-target="#add_object" class="btn btn-default pull-right"><i class="fa fa-plus"></i>
                                Добавить объект</a>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>*/ ?>

            <div class="row margin">
                <div class="col-md-6">
                    <div class="form-group">
                        <? f_input('rw_comment ~textarea . ?Комментарий...'); ?>
                    </div>
                </div>
            </div>

            <div class="row margin">
                <div class="col-md-3">
                    <input type="hidden" name="obj_id" value="<?=$el_action?>">
                    <button type="submit" class="btn btn-block btn-success">
                        <i class="fa fa-spin fa-spinner progressbar" style="display: none;"></i>
                        Сохранить
                    </button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    </form>

</div>
</div>
<script type="text/javascript">
    $('#rw_review_date').datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        todayHighlight: true,
        todayBtn: true
    });
    $('#rw_reserve').datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        todayHighlight: true,
        todayBtn: true
    });
</script>