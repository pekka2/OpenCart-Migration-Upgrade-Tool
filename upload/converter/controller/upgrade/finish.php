<?php
class ControllerUpgradeFinish extends Controller {   
        private $error = array();
   public function index() {
		$this->language->load('upgrade/finish');
		$this->load->model('upgrade/database');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading'] = $this->language->get('heading_clean_structure');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_upgrade_info'),
			'href'      => $this->url->link('upgrade/info'),
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('btn_start'),
			'href'      => $this->url->link('upgrade/start')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_clean'),
			'href'      => $this->url->link('upgrade/clean')
		);
		
		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 0;
			$step = 0;
		}
           if( isset( $this->request->post['step']) && $this->validate() ){
					if( !isset($_COOKIE['UpgradeMigration']) ){
									$this->redirect($this->url->link('common/login'));
					}
                 $this->data['showOps'] = ( !empty( $_POST['showOps'] ) ? true : false );
                 $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );

		        $oc_path  = DIR_DOCUMENT_ROOT . 'system/modification';
		        $oc_path2 = DIR_DOCUMENT_ROOT . 'system/library/db';
		        $oc_path3 = DIR_DOCUMENT_ROOT . 'catalog/controller/api';

		        if(is_dir($oc_path) && is_dir($oc_path2) && is_dir($oc_path3) || $this->data['simulate'] ){
                   $this->data['clean_structure'] = $this->model_upgrade_database->cleanStructure( $this->request->post );
                }           
             }  

                $this->data['action'] = $this->url->link('common/home');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps); 
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_data_info'] = $this->language->get('text_data_info');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['header_step'] = $this->language->get('header_step_complete');
                $this->data['steps'] = $steps;
                $this->data['step'] = $step;

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

                $this->data['btn_finish'] = $this->language->get('btn_finish');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
		$this->template = 'upgrade/finish.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/clean')) {
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
