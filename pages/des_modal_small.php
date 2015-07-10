<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;} ?>
<div class="modal" id="modal_small">
	<div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title"></h3>
            </div>
			<div class="modal-body">
			</div>
            <div class="modal-footer">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>