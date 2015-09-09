<?php
class ControllerUpgradeCollate extends Controller {   
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
		$this->load->model('upgrade/table');
		
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
		
		if(isset($this->request->post['steps'])){
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 8;
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
                 $this->data['upgrade_data'] = $this->model_upgrade_table->addTables( $this->request->post );
       
                   $this->model_upgrade_info->addPermissions($this->post['simulate']);

          }
          $this->data['action'] = $this->url->link('upgrade/column');
          $this->data['text_step'] = sprintf($this->language->get('text_step'),2,$steps);  
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
