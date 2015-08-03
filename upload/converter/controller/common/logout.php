<?php       
class ControllerCommonLogout extends Controller {   
	public function index() { 
		setcookie("UpgradeMigration", HTTP_SERVER, time() - 3600);

		$this->redirect($this->url->link('common/login');
	}
}  
?>
