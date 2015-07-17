<div class="tab-pane" id="comments">
    <div class="row margin">
        <div class="box box-success">
            <div class="box-body chat" id="chat-box">
                <?  $comments = db_array("SELECT * FROM `lim_comments`, `lim_users`
                             WHERE `user_id`=`cm_author` AND `cm_object` = '{$el_data['obj_id']}'
                             ORDER BY `cm_create_tst` DESC");
                foreach ($comments as $comment) { ?>
                    <div class="item">
                        <img src="<?=avatar_echo($comment['user_avatar'])?>" class="offline"/>
                        <p class="message">
                            <span class="name">
                                <small class="text-muted pull-right">
                                    <i class="fa fa-clock-o"></i> <?=$comment['cm_create_tst']?>&nbsp;&nbsp;&nbsp;
                                    <a href="#" class="text-danger" onclick="delete_element(<?=$review['rw_id']?>, 'comments');">
                                        <i class="fa fa-fw fa-trash"></i>
                                    </a>
                                </small>
                                <?="{$comment['user_name']} {$comment['user_surname']}"?>
                            </span>
                            <?=$comment['cm_value']?>
                        </p>
                    </div>
                <?  } ?>

            </div>
            <!-- /.chat -->
            <div class="box-footer">
                <form action="#" onsubmit="save_modal_edit(this, 0, 'comments')">
                    <textarea class="form-control" rows="3" name="cm_value" placeholder="Введите текст комментария ..."></textarea>

                    <div class="row">
                        <div class="col-md-3 pull-right">
                            <input type="hidden" name="obj_id" value="<?=$el_id?>">
                            <button type="submit" class="btn btn-block btn-success">
                                <i class="fa fa-spin fa-spinner progressbar" style="display: none;"></i>
                                Отправить комментарий
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box (chat box) -->
    </div>
</div>