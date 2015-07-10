// AJAX
function getXmlHttp() {
  var xmlhttp;
  try {xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}
  catch (e) {
    try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}
	catch (E) {xmlhttp = false;}
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

// ajax-подгрузка тела приложения
function www_to_pdf(q) {
	var req = getXmlHttp();
	req.onreadystatechange = function() {  
		if (req.readyState == 4) { 
			if(req.status == 200) { 
				if (req.responseText == "ok") {
					alert('Запрос на конвертацию документа успешно отправлен!');
				} else alert(req.responseText);
			} else alert(req.status);
		} //else alert ('Неизвестная ошибка!');
	}
	req.open('GET', 'www_to_pdf.php?q=' + encodeURIComponent(q), true);
	req.send(null);  // отослать запрос
}