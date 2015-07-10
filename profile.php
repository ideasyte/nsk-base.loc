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
	<link rel="stylesheet" href="plugins/jcrop/css/jquery.Jcrop.css">	
	<style>
		section {margin-top:20px;}
	</style>
</head>
<body class="skin-blue">
	<? require MC_ROOT.'/pages/metrics_body.php'; ?>
	<? require MC_ROOT.'/pages/des_modal_crop.php'; ?>
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
				
                    <form action="?act=edit" method="post" id="sky-form1" class="log-reg-block sky-form">
                        <a href="index.php" style="text-decoration: none;">
							<h2>
							<? echo $user_info['user_name']; ?></h2>
						</a>
						
		<? if (!empty($auth_success)) { ?>
			<div class="alert alert-success alert-bold-border fade in alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<? echo $auth_success; ?>
			</div>
		<? } ?>
		<? if (!empty($auth_error)) { ?>
			<div class="alert alert-warning alert-bold-border fade in alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong>Ошибка!</strong><br /> <? echo $auth_error; ?>
			</div>
		<? } ?>	
						
						<section>
							<div class="row">
								<div class="col-sm-6 col-lg-4">
									<img src="<?=$user_avatar?>" id="avatar_picture" style="width:100%;">
									<button type="button" id="btn_upload" class="btn btn-info btn-xs" style="width:100%">
										<i class="fa fa-camera-retro"></i> 
										Сменить аватар
									</button>
								</div>
							</div>
							<div id="pbar_avatar" class="progress no-rounded progress-striped active" style="width:100%; display:none;">
								<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only"></span></div>
							</div>
						</section>
		
						<section>
                            <label class="input login-input">Имя</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" name="user_name" placeholder="Имя" value="<? echo $user_info['user_name'];?>">
                                </div>
                        </section>
						
						<section>
                            <label class="input login-input">Логин</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-home"></i></span>
                                    <input type="text" class="form-control" name="user_login" placeholder="Логин" value="<? echo $user_info['user_login'];?>">
                                </div>
                        </section>
						
						<section>
                            <label class="input login-input">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                    <input type="text" class="form-control" name="user_email" placeholder="E-mail" value="<? echo $user_info['user_email'];?>">
                                </div>
                        </section>
						
						<section>
                            <label class="input login-input">Номер телефона для SMS-уведомлений</label>
                                <div class="input-group">
                                    <span class="input-group-addon">+7</span>
                                    <input type="text" class="form-control" name="user_phone" placeholder="Номер телефона" value="<? echo $user_info['user_phone'];?>">
                                </div>
                        </section>
						
						<section>
							<button class="btn btn-success" type="submit">Сохранить</button>
						</section>
                    </form>

                </div>
				
        </section><!-- /.content -->
	</div><!-- /.content-wrapper -->

	<? require MC_ROOT.'/pages/des_footer.php'; ?>
</div>

<? require MC_ROOT.'/pages/metrics_end_page.php'; ?>

	<!-- Upload & crop photo -->
	<script src="plugins/jcrop/js/jquery.Jcrop.js"></script>
	<script type="text/javascript" src="scripts/js/ajaxupload.3.5.js" ></script>

<script>
	// Jquery-загрузчик фотографии на сервер
	$(function(){
		var btnUpload=$('#btn_upload');
		var pbar_avatar=$('#pbar_avatar');
		new AjaxUpload(btnUpload, {
			action: './scripts/php/upload-photo.php?picture_type=avatar',
			name: 'uploadfile',
			onSubmit: function(file, ext){
				if (! (ext && /^(jpg|jpeg|png|gif)$/.test(ext))){ 
                    // extension is not allowed 
					alert('Разрешенный формат изображения - JPG');
					return false;
				}
				// Высвечиваем прогресс-бар
				pbar_avatar.show();
				btnUpload.hide();
				// Уничтожаем предыдущие картинки и плагины, если они были
				try {jcrop_api.destroy(); $('#target').remove();} catch (e) {}
			},
			onComplete: function(file, response){
				// По факту завершения загрузки гасим прогресс-бар
				console.log(response);
				pbar_avatar.hide();
				btnUpload.show();
				var answer;
				try {answer = $.parseJSON(response);} catch (e) {}
				if (answer != undefined) {
					if (answer.result === "ok") {
						// Высвечиваем в окне имя закачанного файла
						$('#modal_crop_filename').html(file);
						// Открываем модальное окно
						$('#modal_crop').modal({
						  backdrop: 'static',
						  keyboard: false
						})
						// Открываем картинку в модальном окне
						$('#modal_crop_body').html('<img src="/pictures/tmp/compact/'+answer.filename+'" id="target" alt="" />');
						// По факту подгрузки картинки навешиваем на нее Crop-плагин
						$('#target').load(function() {initJcrop(<? 	if (empty($f_options['picture_proportions'])) echo 1;
																	else echo $f_options['picture_proportions']; ?>);});
						// Запоминаем id текущего изображения
						cur_crop_picture_id = answer.picture_id;
					} else alert('Ошибка!\r\n' + answer.error_txt); 
				} else alert('Ошибка соединения с сервером!'); 
			}
		});
	});	
</script>

</body>
</html>