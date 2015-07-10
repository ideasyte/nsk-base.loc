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
            Главная страница
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li class="active"><a href="index.php"><i class="fa fa-dashboard"></i> Главная</a></li>
          </ol>
        </section>
		
		<hr />

        <!-- Main content -->
        <section class="content">
		
			<div class="row">
				<div class="col-md-6 col-md-push-3 col-lg-4 col-lg-push-4">
					<p>
						<a href="updates.php" class="btn btn-block btn-success btn-lg">
							Обновления 
							<span class="badge bg-red">2</span>
						</a>
					</p>
					<p>
						<a href="profile.php" class="btn btn-block btn-success btn-lg">
							Личный кабинет 
							<span class="badge bg-red">5</span>
						</a>
					</p>
					<p>
						<a href="sale.php" class="btn btn-block btn-success btn-lg">
							Продажа 
							<span class="badge bg-red">120</span>
						</a>
					</p>
					<p>
						<a href="#" class="btn btn-block btn-success btn-lg" disabled>
							Аренда 
							<span class="badge bg-red">1</span>
						</a>
					</p>					
				</div>
			</div>
			

        </section><!-- /.content -->
	</div><!-- /.content-wrapper -->

	<? require MC_ROOT.'/pages/des_footer.php'; ?>
</div>

<? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

<script>

</script>

</body>
</html>