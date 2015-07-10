<? $security_inc = true;
require_once ('../../config.php');
require_once (MC_ROOT.'/scripts/php/f_mysql.php');
require_once (MC_ROOT.'/scripts/php/sessions.php');

//if (!$enter_admin && !$enter_user) exit;
$el_id = $_POST['el_id'];
$el_type = $_POST['el_type'];
$el_action = $_POST['el_action'];
$parent_id = intval($_POST['parent_id']);

if ( get_magic_quotes_gpc()) {
	$el_id=addslashes(stripslashes(trim($el_id)));
}
header("Cache-Control: no-store, no-cashe, must-revalidate, max-age=0"); 
switch ($el_type) {
	case 'objects':
        if ($el_action == 'view') require MC_ROOT.'/pages/modals/modal_object_view.php';
        else if ($el_action == 'edit') require MC_ROOT.'/pages/modals/modal_object_edit.php';
		break;
	case 'clients':
		if ($el_action == 'view') require MC_ROOT.'/pages/modals/modal_client_view.php';
		else if ($el_action == 'edit') require MC_ROOT.'/pages/modals/modal_client_edit.php';
		break;
    case 'reviews':
        require MC_ROOT.'/pages/modals/modal_review.php';
}
?>