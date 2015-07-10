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
</head>
<body class="skin-blue">
	<? require MC_ROOT.'/pages/metrics_body.php'; ?>
<div class="wrapper">
	<? require MC_ROOT.'/pages/des_header.php'; ?>
	<? require MC_ROOT.'/pages/des_sidebar.php'; ?>
	
	<!-- Right side column. Contains the navbar and content of the page -->
	<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Обновления
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li class="active">Обновления</li>
          </ol>
        </section>
		
		<hr />

        <!-- Main content -->
        <section class="content">

            <div class="tab-pane" id="events">
                <div class="row margin">
                    <?  $events = db_array("SELECT *, DATE_FORMAT(`ev_tst`, '%d.%m.%Y') AS `ev_date`
                             FROM `lim_events`, `lim_users` WHERE
                             `user_id` = `ev_author`
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
            <!-- /.tab-pane -->
		
        </section><!-- /.content -->
	</div><!-- /.content-wrapper -->

	<? require MC_ROOT.'/pages/des_footer.php'; ?>
</div>

<? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

<script>

</script>

</body>
</html>