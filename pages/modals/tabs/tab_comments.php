<div class="tab-pane" id="comments">
    <div class="row margin">
        <div class="box box-success">
            <div class="box-body chat" id="chat-box">
                <?  $comments = db_array("SELECT * FROM `lim_comments`, `lim_users`
                             WHERE `user_id`=`cm_author` AND `cm_object` = '{$el_data['obj_id']}'
                             ORDER BY `cm_create_tst` DESC");
                foreach ($comments as $comment) { ?>
                    <div class="item">
                        <img src="<?=avatar_echo($comment['user_avatar'])?>" alt="user image" class="offline"/>
                        <p class="message">
                            <a href="#" class="name">
                                <small class="text-muted pull-right">
                                    <i class="fa fa-clock-o"></i> <?=$comment['cm_create_tst']?>
                                </small>
                                <?="{$comment['user_name']} {$comment['user_surname']}"?>
                            </a>
                            <?=$comment['cm_value']?>
                        </p>
                    </div>
                <?  } ?>

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