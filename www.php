<? 
$q = $_GET['q'];
if ( get_magic_quotes_gpc()) {
	$q=addslashes(stripslashes(trim($q)));
}
$exp = intval ( $_GET['exp'] );

if ($ch = curl_init()) { 
		
		// Инициализация параметров CURL
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
		
		if (empty($q)) $q = "http://tmfeed.ru";
				
		curl_setopt($ch, CURLOPT_URL, $q );
		$p = curl_exec($ch);
		
		$p = str_replace ('href=\'//',	'href=\'http://' , $p);
		$p = str_replace ('href="//',	'href="http://' , $p);
		$p = str_replace ('src=\'//',	'src=\'http://' , $p);
		$p = str_replace ('src="//',	'src="http://' , $p);
		
		$p = str_replace ('href=\'/',	'href=\'http://m.habrahabr.ru/' , $p);
		$p = str_replace ('href="/', 	'href="http://m.habrahabr.ru/' , $p);
		
		$p = str_replace ('src=\'/',	'src=\'http://m.habrahabr.ru/' , $p);
		$p = str_replace ('src="/', 	'src="http://m.habrahabr.ru/' , $p);
		
		$p = str_replace ('</head>', '<style>body { zoom: ' . ( $exp ? 3 : 2 ) . '; } * {color: #000 !important; } .fixed-buttons {position: fixed; right: 0; bottom: 0; margin-top: 0; background-color:gray; width:40px; height:40px;} .spoiler_text {display:block;} </style><script type="text/javascript" src="http://frimmy.ru/scripts/js/www_to_pdf.js"></script></head>', $p);  
		
		$p = str_replace ('class="spoiler_text"', 'class="spoiler_text" style="display:block; line-height: 120%;"', $p);
		
		$p = str_replace ('<a href="', '<a href="http://frimmy.ru/www.php?q=', $p);
		
		if (empty($exp))		
			$p = str_replace ('</body>', '<div class="fixed-buttons" onclick="www_to_pdf(\'' . $q . '\')">PDF</div></body>', $p);
		
		header("Content-Type: text/html; charset=utf-8");		
		echo $p;
		
		//echo '<html><head></head><body><textarea style="width:600px; height:500px;">' . print_r ( $data1, true ) . '</textarea></body></html>';
		
} else echo 'Ошибка инициализации CURL!';


?>