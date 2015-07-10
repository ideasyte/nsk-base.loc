<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

//if (!$enter_admin && !$enter_user) exit;
$el_type = $_POST['el_type'];

if ( get_magic_quotes_gpc()) {
    $el_type=addslashes(stripslashes(trim($el_type)));
}

db_request("INSERT INTO lim_log_error SET `log_data`='".print_r($_POST, true)."'");
header("Cache-Control: no-store, no-cashe, must-revalidate, max-age=0"); 
switch ($el_type) {
	case 'objects':
		require MC_ROOT.'/pages/lists/list_objects.php';
		break;
	case 'clients':
		require MC_ROOT.'/pages/lists/list_clients.php';
		break;
}
?>