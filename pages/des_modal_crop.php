<?
// Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;} ?>
<div class="modal" id="modal_crop">
    <div class="modal-dialog">
        <div class="modal-content">
			<div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">Кадрирование фото</h3>
				<p class="text-muted" id="modal_crop_filename"></p>
            </div>
			<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 thumbnail" id="modal_crop_body">
							<img src="" id="target" alt="" /><? // class="mfp-fade item-gallery img-responsive" ?>
						</div>
					</div>
					
					<input type="hidden" size="4" id="x1" name="x1" />
					<input type="hidden" size="4" id="y1" name="y1" />
					<input type="hidden" size="4" id="x2" name="x2" />
					<input type="hidden" size="4" id="y2" name="y2" />
					<input type="hidden" size="4" id="w" name="w" />
					<input type="hidden" size="4" id="h" name="h" />
					<input type="hidden" size="4" id="bx" name="bx" />
					<input type="hidden" size="4" id="by" name="by" />
			
			</div>
            <div class="modal-footer">
				<button class="btn btn-success" onclick="ajax_photo_crop();">Кадрировать</button>
            </div>
        </div>
    </div>
</div>