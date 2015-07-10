<? // Защита от "прямого" вызова скрипта
if (!isset($security_inc)) {header("Location: /pages/error404.html"); exit;}
?>
    <!--LArge Modal Templates-->
    <div class="modal fade bs-example-modal-lg" id="modal_large">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!--End Large Modal Templates-->