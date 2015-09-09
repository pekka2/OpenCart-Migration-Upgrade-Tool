<?php
class ControllerUpgradeColumn extends Controller {   
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
		$this->load->model('upgrade/collate');

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
			'text'      => $this->language->get('text_column'),
			'href'      => $this->url->link('upgrade/column'),
			'separator' => false
		);

		
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
                 $this->data['step'] = $step+1;
                 
                 $this->data['add_collate'] = $this->model_upgrade_collate->addCollate( $this->request->post );

                 $this->model_upgrade_info->addPermissions($this->post['simulate']);
          }
	             $this->data['database'] = $this->url->link('upgrade/database');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps); 
                
                if($steps == 8){
                	$this->data['header_step'] = $this->language->get('header_step_1');
                } 
                if($steps == 9){
                	$this->data['header_step'] = $this->language->get('header_step_collate');
                } 
                $this->data['action'] = $this->url->link('upgrade/module');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_column_info'] = $this->language->get('text_column_info');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['steps'] = $steps;
                
                $this->data['btn_column'] = $this->language->get('btn_column');
                $this->data['btn_skip'] = $this->language->get('btn_skip');

		$this->template = 'upgrade/column.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/column')) {
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
