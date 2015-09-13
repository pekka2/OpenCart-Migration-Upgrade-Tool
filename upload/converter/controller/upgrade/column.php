<?php
class ControllerUpgradeColumn extends Controller {
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
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
			'text'      => $this->language->get('text_column'),
			'href'      => $this->url->link('upgrade/column'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('btn_start'),
			'href'      => $this->url->link('upgrade/start'),
			'separator' => false
		);

		
		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 7;
			$step = 1;
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
                 
                 $this->data['add_collate'] = $this->model_upgrade_database->addCollate( $this->request->post );

          }
	            $this->data['database'] = $this->url->link('upgrade/database');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps); 
                
                if($steps == 9){
                	$this->data['header_step'] = $this->language->get('header_step_1');
                } 
                if($steps == 10){
                	$this->data['header_step'] = $this->language->get('header_step_collate');
                } 
                $this->data['action'] = $this->url->link('upgrade/column/new_data');
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
		$this->template = 'upgrade/column.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   public function new_data() {
		$this->language->load('upgrade/database');
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
			'text'      => $this->language->get('text_column'),
			'href'      => $this->url->link('upgrade/column'),
			'separator' => false
		);

		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 6;
			$step = 1;
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
                 $this->data['add_columns'] = $this->model_upgrade_database->addColumns( $this->request->post );

             }

                $this->data['action'] = $this->url->link('upgrade/module');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps); 
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_data_info'] = $this->language->get('text_data_info');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['header_step'] = $this->language->get('header_step_column');
                $this->data['steps'] = $steps;
                $this->data['step'] = $step+1;
		

                $this->data['step_start'] = $this->language->get('step_start');
                $this->data['step_collate'] = $this->language->get('step_collate');
                $this->data['step_column'] = $this->language->get('step_column');
                $this->data['step_data'] = $this->language->get('step_data');
                $this->data['step_module'] = $this->language->get('step_module');
                $this->data['step_setting'] = $this->language->get('step_setting');
                $this->data['step_configuration'] = $this->language->get('step_configuration');
                $this->data['step_clean_module'] = $this->language->get('step_clean_module');
                $this->data['step_clean_table'] = $this->language->get('step_clean_table');
 
                $this->data['step_images'] = $this->language->get('step_images');
                $this->data['btn_data'] = $this->language->get('btn_data');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
		$this->template = 'upgrade/new_data.tpl';

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
