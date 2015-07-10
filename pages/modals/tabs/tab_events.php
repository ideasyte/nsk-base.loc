<div class="tab-pane" id="events">
    <div class="row margin">
        <?  $events = db_array("SELECT *, DATE_FORMAT(`ev_tst`, '%d.%m.%Y') AS `ev_date`
                             FROM `lim_events`, `lim_users` WHERE
                             `user_id` = `ev_author` AND
                             `ev_object` = '{$el_data['obj_id']}'
                             ORDER BY `ev_tst` DESC");
        if (!empty($events)) { ?>
            <table class="table no-border table-striped table-responsive">
                <tbody>
                <?  $num = 0;
                foreach ($events as $event) {
                    $num ++; ?>
                    <tr class="text-center">
                        <td><b><?=$event['ev_date']?></b></td>
                        <td><i class="fa fa-user"></i> <?="{$event['user_name']} {$event['user_surname']}"?></td>
                        <td><p class="text-muted no-margin"><?=$event['ev_message']?></p></td>
                    </tr>
                <?  } ?>
                </tbody>
            </table>
        <?  } ?>
    </div>
</div>