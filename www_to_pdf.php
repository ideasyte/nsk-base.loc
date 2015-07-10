<? 
$q = $_GET['q'];
if ( get_magic_quotes_gpc()) {
	$q=addslashes(stripslashes(trim($q)));
}

// Отправляем e-mail
$title = "convert"; 
$headers = "From: iromanser<iromanser@kindle.com>\r\nReply-To: iromanser<iromanser@kindle.com>\r\nContent-type: text/plain; charset=utf-8\r\n";
// iromanser<iromanser@kindle.com

$ret = mail('submit@web2pdfconvert.com', $title, "http://frimmy.ru/www.php?exp=1&q=$q", $headers); //submit@web2pdfconvert.com
if ($ret == 1) echo 'ok';
else echo $ret;
?>