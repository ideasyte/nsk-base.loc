<? $security_inc = true;
require_once ('config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');
require_once (MC_ROOT.'/scripts/php/content.php');
// Доступ к странице
if (empty($enter_user)) {header("Location: login.php"); exit;}

?><!DOCTYPE html>
<html>
<head>
	<? require MC_ROOT.'/pages/metrics_head.php'; ?>
    <!-- Ion Slider -->
    <link href="plugins/ionslider/ion.rangeSlider.css" rel="stylesheet" type="text/css">
    <!-- ion slider Nice -->
    <link href="plugins/ionslider/ion.rangeSlider.skinNice.css" rel="stylesheet" type="text/css">
    <!-- PrettyPhoto -->
    <link href="plugins/pretty-photo/pp.css" rel="stylesheet" type="text/css">
    <!-- DaData -->
    <link href="https://dadata.ru/static/css/lib/suggestions-15.1.css" type="text/css" rel="stylesheet" />
    <!-- Jcrop -->
    <link rel="stylesheet" href="plugins/jcrop/css/jquery.Jcrop.css">
    <!-- colorbox -->
    <link href="plugins/colorbox/colorbox.css" rel="stylesheet">
</head>
<body class="skin-blue">
	<? require MC_ROOT.'/pages/metrics_body.php'; ?>
	<!-- /Modals -->
	<? require MC_ROOT.'/pages/modals/modal_large.php'; ?>
    <? require MC_ROOT.'/pages/des_modal_crop.php'; ?>
	
<div class="wrapper">
	<? require MC_ROOT.'/pages/des_header.php'; ?>
	<? require MC_ROOT.'/pages/des_sidebar.php'; ?>
	
	<!-- Right side column. Contains the navbar and content of the page -->
	<div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

			<!-- /Filters -->
			<? require MC_ROOT.'/pages/filters/filter_objects.php'; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-body">
                            <div class="row margin" id="list">
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.col -->
			


        </section>
        <!-- /.content -->
	</div><!-- /.content-wrapper -->

    <? require MC_ROOT.'/pages/des_footer.php'; ?>
</div>

    <?  // Инициализация фильтров цены и площади
    $extrem_values = db_row("SELECT
    MIN(`lim_clients`.`cl_price_to`) AS `min_price`,
    MAX(`lim_clients`.`cl_price_to`) AS `max_price`,
    MIN(`lim_clients`.`cl_area_from`) AS `min_area`,
    MAX(`lim_clients`.`cl_area_from`) AS `max_area`
    FROM `lim_clients`");

    /*print_r($extrem_values);
    echo 'floor=' . round($extrem_values['min_price'], -3);*/

    // Расширение интервалов
    $min_price = floor($extrem_values['min_price'] * 0.9 / 1000) * 1000;
    $max_price = ceil($extrem_values['max_price'] * 1.1 / 1000) * 1000;
    $min_area = floor($extrem_values['min_area'] * 0.9 / 10) * 10;
    $max_area = ceil($extrem_values['max_area'] * 1.1 / 10) * 10;
    ?>


    <? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

    <!-- Ion Slider -->
    <script src="plugins/ionslider/ion.rangeSlider.min.js" type="text/javascript"></script>

    <!-- prettyPhoto -->
    <script src="plugins/pretty-photo/pp.js" type="text/javascript"></script>

    <!-- DaData -->
    <script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-15.1.min.js"></script>

    <!-- Upload & crop photo -->
    <script src="plugins/jcrop/js/jquery.Jcrop.js"></script>
    <script type="text/javascript" src="scripts/js/ajaxupload.3.5.js" ></script>

    <!-- colorbox -->
    <script src="plugins/colorbox/jquery.colorbox.min.js"></script>

    <script type="text/javascript">
        var page_type = 'objects';

        $(function () {
            /* ION SLIDER */
            $("#price").ionRangeSlider({
                min: <?=$min_price?>,
                max: <?=$max_price?>,
                from: <? echo floor($extrem_values['min_price'] / 1000) * 1000; ?>,
                to: <? echo ceil($extrem_values['max_price'] / 1000) * 1000; ?>,
                type: 'double',
                step: 1000,
                prefix: "руб. ",
                prettify: false,
                hasGrid: true,
                onFinish: function (data) {
                    filter.price_from = data.fromNumber;
                    filter.price_to = data.toNumber;
                    load_list();
                }
            });
            $("#area").ionRangeSlider({
                min: <?=$min_area?>,
                max: <?=$max_area?>,
                from: <?=floor($extrem_values['min_area'] / 10) * 10?>,
                to: <?=ceil($extrem_values['max_area'] / 10) * 10?>,
                type: 'double',
                step: 10,
                prefix: "кв.м ",
                prettify: false,
                hasGrid: true,
                onFinish: function (data) {
                    filter.area_from = data.fromNumber;
                    filter.area_to = data.toNumber;
                    load_list();
                }
            });

            $('input[name=pay_type]').each(function(){
                var self = $(this),
                    label = self.next(),
                    label_text = label.text();

                label.remove();
                self.iCheck({
                    checkboxClass: 'icheckbox_line-green',
                    radioClass: 'iradio_line-green',
                    insert: '<div class="icheck_line-icon"></div>' + label_text
                });
            });
            $('input[name=pay_type]').on('ifChecked', function(event){
                var n = $(this).attr('name');
                if (!! n) filter[n] = $(this).val();
                load_list();
            });

            $('.checkbox input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
            $('.checkbox input[type=checkbox]').on('ifChanged', function(event){
                var n = $(this).attr('name');
                if (!! n) filter[n] = $(this).prop("checked");
                load_list();
            });

            // Загрузка списка
            load_list();
        });

        function picture_delete(picture_id) {
            if (!!! picture_id) return;
            if (!confirm('Вы действительно хотите удалить элемент?')) return;
            console.log("pages/ajax/picture_delete.php");
            $.ajax({
                url: "pages/ajax/picture_delete.php",
                type: "POST",
                data: 'picture_id=' + picture_id,
                dataType: "html",
                success: function(answer) {
                    if (answer == 'ok') {
                        $('#picture_' + picture_id).remove();
                    } else alert ('Ошибка!\r\n' + answer);
                }
            });
        }

    </script>

</body>
</html>