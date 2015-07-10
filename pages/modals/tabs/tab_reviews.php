<div class="tab-pane" id="views">
    <div class="row margin">
        <?  $reviews = db_array("SELECT *, DATE_FORMAT(`rw_create_tst`, '%d.%m.%Y') AS `rw_create_date`
                             FROM `lim_reviews`, `lim_clients`
                             WHERE `cl_id`=`rw_client` AND `rw_object` = '{$el_data['obj_id']}'
                             ORDER BY `rw_review_date` DESC");
        if (!empty($reviews)) { ?>
            <table class="table table-bordered table-hover table-striped table-responsive">
                <tr>
                    <th style="width: 10%">№</th>
                    <th class="text-center" style="width: 15%">Дата добавления</th>
                    <th class="text-center" style="width: 25%">Клиент</th>
                    <th class="text-center" style="width: 15%">Дата просмотра</th>
                    <th class="text-center" style="width: 15%">Резерв</th>
                    <th class="text-center" style="width: 20%">Комментарии</th>
                    <th></th>
                </tr>
                <?  $num = 0;
                foreach ($reviews as $review) {
                    $num ++; ?>
                    <tr class="text-center">
                        <td><?=$num?></td>
                        <td><?=$review['rw_create_date']?></td>
                        <td><p class="text-muted no-margin"><?=$review['cl_fio']?></p></td>
                        <td><?=$review['rw_review_date']?></td>
                        <td><?=($review['rw_reserve'] != '0000-00-00') ? "Дата: {$review['rw_reserve']}" : "Нет"?></td>
                        <td><p class="text-muted no-margin"><?=$review['rw_comment']?></p></td>
                        <td>
                            <a href="#" class="text-success" onclick="open_modal(<?=$review['rw_id']?>, 'reviews', <?=$el_data['obj_id']?>);">
                                <i class="fa fa-fw fa-pencil"></i>
                            </a><br/>
                            <a href="#" class="text-danger">
                                <i class="fa fa-fw fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?  } ?>

            </table>
        <?  } ?>
        <div class="row">
            <div class="col-md-3 pull-right">
                <a class="btn btn-block btn-success" onclick="open_modal(0, 'reviews', <?=$el_data['obj_id']?>);">Добавить просмотр</a></div>
        </div>
    </div>
</div>