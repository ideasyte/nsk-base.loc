// Скрипты шаблона

// Глобальные переменные хранения размера контейнера фото и координат клика
var mask_width, mask_height, xClick, yClick;

$(window).ready(function(){

});


// Обрезка фото (отправка координат на сервер)
var cur_crop_picture_id = 0;
var need_show_last_modal = false; // После загрузки фото высветить обратно модалку с редактированием объекта
function ajax_photo_crop () {
	if (cur_crop_picture_id != 0) {
		var x1 = $("#x1").val();
		var y1 = $("#y1").val();
		var w = $("#w").val();
		var h = $("#h").val();
		var bx = $("#bx").val();
		var by = $("#by").val();
		var post_query = 'picture_id=' + cur_crop_picture_id
						+ '&x1=' + x1
						+ '&y1=' + y1
						+ '&w=' + w
						+ '&h=' + h
						+ '&bx=' + bx
						+ '&by=' + by;
		
		$.ajax({
				url: 'scripts/php/ajax_photo_crop.php',
				type: "POST",
				data: post_query,
				dataType: "json",
				success: function(answer){
					if (answer != '') {
						if (answer.result == 'ok') {
							console.log(answer);
							// Два варианта - либо заменяем src картинки (аватара, ковера), либо добавляем фото к галерее
							if (!! answer.append_element) $("#" + answer.picture_field + '_holder').prepend(answer.append_element);
							else $("#" + answer.picture_field + '_picture').attr('src', answer.new_photo_src);
							// Закрываем модальное окно
							$('#modal_crop').modal('hide');
                            if (need_show_last_modal) $('#modal_large').modal('show');
                            need_show_last_modal = false;
						} else alert ('Ошибка!\r\n' + answer.error_txt); 
					} else alert ('Неизвестная ошибка сервера!\r\nПопробуйте еще раз'); 
				}
		});	
	} else alert ("Ошибка! Потерян id изображения для обрезки");
}

// Инициализация Crop-плагина обрезки фото
function initJcrop(crop_proportions) {
			var jcrop_api, boundx, boundy;
			$('#target').Jcrop({
				onChange:   showCoords,
				onSelect:   showCoords,
				onRelease:  clearCoords,
				aspectRatio: crop_proportions,
				minSize: [ 80, 60 ]
			},function(){
				// Use the API to get the real image size
				var bounds = this.getBounds();
				boundx = bounds[0];
				boundy = bounds[1];	
				jcrop_api = this;
				// Стартовый рендеринг плагина с небольшими отступами
				jcrop_api.animateTo([20,20,boundx-20,boundy-20]);
			});

			// event handlers, as per the Jcrop invocation above
			function showCoords(c) {
				$('#x1').val(c.x);
				$('#y1').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				$('#w').val(c.w);
				$('#h').val(c.h);
				$('#bx').val(boundx);
				$('#by').val(boundy);
			};
			function clearCoords() { $('#coords input').val('');};	
}

// Фильтр поиска записей в базе
var filter = {};

// Защита от наложений между ajax-запросами и модальными окнами
var ajaxProgress = false;
function ajaxStatus (status) {
	if (!! status) {
		if (ajaxProgress) {
			console.log('Наложение ajax: предыдущий запрос еще в процессе!');
			return false;
		}
		ajaxProgress = true;
		$('.progressbar').show();
	} else {
		ajaxProgress = false;
		$('.progressbar').hide();
	}
	return true;
}
// Защита от наложений между модальными окнами
var modalOpen = false; // Флаг текущего статуса модалки
var modalInProcess = false; // Флаг переходного состояния модалки (анимация в процессе)
var tmpContent = false; // Временное хранилище контента, чтобы сменить его в момент, когда модалка будет погашена
function showModal (content) {
	if (modalInProcess) return;
	if (modalOpen) {
		// Если модалка открыта, и нужно высветить новую модалку, то вначале закрываем старую
		$('#modal_large').modal('hide');
		// Запоминаем контент, чтобы сменить его позднее
		tmpContent = content;
	} else {
		$('#modal_large').html(content);
		$('#modal_large').modal('show');
	}
}
$(window).ready(function(){
	// При старте анимации модалок ставим флаг modalInProcess
	$('#modal_large').on('show.bs.modal', function (e) {
		modalInProcess = true;
	});
	$('#modal_large').on('hide.bs.modal', function (e) {
		modalInProcess = true;
	});
	// По завершении анимации модалок снимаем флаг modalInProcess и меняем статус модалки
	$('#modal_large').on('shown.bs.modal', function (e) {
		modalInProcess = false;
		modalOpen = true;
	});
	$('#modal_large').on('hidden.bs.modal', function (e) {
		modalInProcess = false;
		modalOpen = false;
		// Если есть промежуточный контент, значит, идет переход между модалками
		if (tmpContent) {
			// Сразу же открываем модалку заново
			$('#modal_large').html(tmpContent);
			tmpContent = false; // удаляем (больше не нужен)
			$('#modal_large').modal('show');		
		}
	});
});

// Текущий id элемента, открытого в модалке
var modal_element_id = 0;

// Открытие модального окна. Если el_id=0, то высвечивается форма создания новой записи
function open_modal (el_id, el_type, el_action) {
	console.log('open_modal (' + el_id + ', ' + el_type + ', ' + el_action + ')');
	if (!ajaxStatus(1) || !!! el_type || !!! el_action) return;
    modal_element_id = el_id;
    $.ajax({
			url: '/pages/ajax/ajax_modal_element.php',
			type: "POST",
			data: {"el_id": el_id, "el_type":el_type, "el_action":el_action},
			dataType: "html",
			complete: ajaxStatus(0),
			success: function(answer){
				if (answer != '')	{
					showModal(answer);
				} else alert ('Ошибка соединения');
			}
	});
}
// Сохранение в БД из модального окна
function save_modal_edit (form, el_id, el_type) {
	event.returnValue=false;
	console.log('save_modal_edit (' + el_id + ', ' + el_type + ')');
	if (!ajaxStatus(1) || !!! el_type) return;
	var send_data = $(form).serialize() + '&el_id=' + el_id + '&el_type=' + el_type;
	console.log('sending: ' + send_data);
	$.ajax({
			url: '/pages/ajax/save_modal_edit.php',
			type: "POST",
			data: send_data,
			dataType: "json",
			complete: ajaxStatus(0),
			success: function(answer){
				if (answer != '') {
					if (answer.result == 'ok') {
						$('#modal_large').modal('hide');
						alert(answer.result_txt);
						load_list ();
					} else if (answer.result == 'error') {
						alert ('Ошибка!\r\n' + answer.result_txt);
					} else showModal('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка сервера!</h3></div><div class="modal-body">' + answer + '</div></div></div>');
				} else alert ('Ошибка соединения');
			}
	});
	return false;
}

// Удаление элемента
function delete_element (el_id, el_type) {
    if (!confirm('Вы действительно хотите удалить элемент?')) return;
    console.log('delete_element (' + el_id + ', ' + el_type + ')');
    if (!ajaxStatus(1) || !!! el_type) return;
    $.ajax({
        url: '/pages/ajax/delete_element.php',
        type: "POST",
        data: {"el_id": el_id, "el_type":el_type},
        dataType: "json",
        complete: ajaxStatus(0),
        success: function(answer){
            if (answer != '') {
                if (answer.result == 'ok') {
                    alert(answer.result_txt);
                    load_list ();
                } else if (answer.result == 'error') {
                    alert ('Ошибка!\r\n' + answer.result_txt);
                } else showModal('<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button class="close" data-dismiss="modal">×</button><h3 class="modal-title">Ошибка сервера!</h3></div><div class="modal-body">' + answer + '</div></div></div>');
            } else alert ('Ошибка соединения');
        }
    });
}

// Подгрузка списка элементов
function load_list (el_type) {
	if (!!! el_type) el_type = page_type;
	console.log('load_list: ' + el_type);
	if (!ajaxStatus(1) || !!! el_type) return;
	var send_data = filter;
    send_data.el_type = el_type;
    console.log(send_data);
    $.ajax({
			url: '/pages/ajax/ajax_list_element.php',
			type: "POST",
			data: send_data,
			dataType: "html",
			complete: ajaxStatus(0),
			success: function(answer){
				if (answer != '') {
					$('#list').html(answer);
					console.log('Список ' + el_type + ' успешно загружен!');
				} else alert ('Ошибка соединения');
			}
	});
}


