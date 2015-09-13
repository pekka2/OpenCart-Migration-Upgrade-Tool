<?php
class ControllerUpgradeCollate extends Controller {
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
        $this->lmodel->set('upgrade_database',$this->language->load('upgrade/database'));
		$this->load->model('upgrade/info');
		$this->load->model('upgrade/database');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));
		$version = 3;

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
			'text'      => $this->language->get('text_collate'),
			'href'      => $this->url->link('upgrade/collate'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('btn_start'),
			'href'      => $this->url->link('upgrade/start'),
			'separator' => false
		);

		if(isset($this->request->post['steps'])){
		  $steps = $this->request->post['steps'];
		  $step = $this->request->post['step'];
		} else {
			$steps = 9;
			$step = 1;
     	    $this->data['start'] = true;
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
                 $this->data['upgrade_data'] = $this->model_upgrade_database->addTable($this->request->post );
       
                   $this->model_upgrade_info->addPermissions($this->post['simulate']);
          }
                $this->data['action'] = $this->url->link('upgrade/column');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);  
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_collate_info'] = $this->language->get('text_collate_info');
                $this->data['help_step_1'] = $this->language->get('help_step_1');
                $this->data['step'] = 3;
                $this->data['steps'] = $steps;
                $this->data['header_step_1'] = $this->language->get('header_step_1');
                $this->data['btn_collate'] = $this->language->get('btn_collate');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');

                $this->data['step_start'] = $this->language->get('step_start');
                $this->data['step_collate'] = $this->language->get('step_collate');
                $this->data['step_column'] = $this->language->get('step_column');
                $this->data['step_data'] = $this->language->get('step_data');
                $this->data['step_module'] = $this->language->get('step_module');
                $this->data['step_setting'] = $this->language->get('step_setting');
                $this->data['step_configuration'] = $this->language->get('step_configuration');
                $this->data['step_images'] = $this->language->get('step_images');
                $this->data['step_clean_module'] = $this->language->get('step_clean_module');
                $this->data['step_clean_table'] = $this->language->get('step_clean_table');
		$this->template = 'upgrade/collate.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/collate')) {
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
