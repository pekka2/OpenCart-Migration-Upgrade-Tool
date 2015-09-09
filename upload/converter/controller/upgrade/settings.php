<?php
class ControllerUpgradeSettings extends Controller {   
        private $error = array();
    private $max;
    private $min;
   public function index() {
		$this->language->load('upgrade/database');
      $this->lmodel->set('upgrade_database',$this->language->load('upgrade/database'));
		$this->load->model('upgrade/info');
		$this->load->model('upgrade/module');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));
        $this->max = 8;
        $this->min = 2;

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_upgrade_info'),
			'href'      => $this->url->link('upgrade/info'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_setting_info'),
			'href'      => $this->url->link('upgrade/settings'),
			'separator' => false
		);

		$version = $this->structure->getVersion();

		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 8;
			$step = 2;
		}
		
		if(isset($this->request->post['upgrade'])){
		  $this->data['upgrade'] = $this->request->post['upgrade'];
		} else {
			$this->data['upgrade'] = 2031;
		}
           if( isset( $this->request->post['step']) && $this->validate() ){
					if( !isset($_COOKIE['UpgradeMigration']) ){
									$this->redirect($this->url->link('common/login'));
					}
                 $this->data['showOps'] = ( !empty( $_POST['showOps'] ) ? true : false );
                 $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );
                 $this->data['upgrade'] = $_POST['upgrade'];
                $modules = $this->structure->hasModule();
                 if( !$modules || $this->request->post['modules'] ) {
                        $this->data['add_modules'] = $this->model_upgrade_module->getChangeModule( $this->request->post );
                 } else {
                        $this->data['add_modules'] = '';
                 }
                } 
                
                $this->data['action'] = $this->url->link('upgrade/configuration');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);
                $this->data['step'] = $step + 1;
                $this->data['steps'] = $steps;
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_setting_info'] = $this->language->get('text_setting_info');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['header_step_module'] = $this->language->get('header_step_module');
                $this->data['btn_setting'] = $this->language->get('btn_setting');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_skip'] = $this->language->get('btn_skip');

		$this->template = 'upgrade/settings.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return true;
		}
		if (!$this->error) {
			return true;
		} else { 
			return false;
		}
   }
}
?>
