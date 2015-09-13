<?php
class ControllerUpgradeClean extends Controller {   
        private $error = array();
   public function index() {
		$this->language->load('upgrade/clean');
		$this->load->model('upgrade/images');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_images'] = $this->language->get('heading_images');
		$this->document->setTitle($this->language->get('heading_title'));
	//	$version = 3;

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
			'text'      => $this->language->get('text_clean_info'),
			'href'      => $this->url->link('upgrade/clean'),
			'separator' => false
		);

		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		}
			$this->data['data'] = 'image/data';
		
     if(isset($this->request->post['step']) && !isset($this->request->post['back'])){
                $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );
                $this->data['showOps'] = ( !empty( $_POST['showOps'] ) ? true : false );
	   if( $this->validateImage()){

                  $this->data['upgrade_imagepath'] = $this->model_upgrade_images->imagePaths( $this->request->post );

	   }
	} else {
     	    $this->data['start'] = true;
			$steps = 2;
			$step = 1;
     }
		$oc_path  = DIR_DOCUMENT_ROOT . 'system/modification';
		$oc_path2 = DIR_DOCUMENT_ROOT . 'system/library/db';
		$oc_path3 = DIR_DOCUMENT_ROOT . 'catalog/controller/api';
		
		 if(!is_dir($oc_path) && !is_dir($oc_path2) && !is_dir($oc_path3) ){
                $this->data['text_pause'] = $this->language->get('text_pause');
                $this->data['text_upload'] = $this->language->get('text_upload'); 
                $this->data['admin'] = false;
		 }
                $this->data['action'] = $this->url->link('upgrade/clean/structure');
                $this->data['text_clean_info'] = $this->language->get('text_clean_info');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_imageDir'] = $this->language->get('text_imageDir');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps); 

                $this->data['header_step'] = $this->language->get('header_step_images');

                $this->data['help_imageDir'] = $this->language->get('help_imageDir');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['steps'] = $steps;
                $this->data['step'] = $step+1;
                
                $this->data['btn_back'] = $this->language->get('btn_back');
                $this->data['btn_clean'] = $this->language->get('btn_clean');
                $this->data['btn_preview'] = $this->language->get('btn_preview');
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

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
                $this->data['step'] = $step-1;
                $this->data['previous'] = $this->url->link('upgrade/images');
		} else {
			$this->data['error_warning'] = '';
		}

		$this->template = 'upgrade/clean.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   public function structure() {
		$this->language->load('upgrade/clean');
		$this->load->model('upgrade/database');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_clean'] = $this->language->get('heading_clean');
		$this->document->setTitle($this->language->get('heading_title'));

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
			$steps = 9;
			$step = 9;
		}
           if( isset( $this->request->post['step']) && $this->validate() ){
					if( !isset($_COOKIE['UpgradeMigration']) ){
									$this->redirect($this->url->link('common/login'));
					}
                 $this->data['showOps'] = ( !empty( $_POST['showOps'] ) ? true : false );
                 $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );
               //  $this->data['drop'] = $this->model_upgrade_database->dropdColumns( $this->request->post );
            
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
                $this->data['step'] = $step;

                $this->data['step_start'] = $this->language->get('step_start');
                $this->data['step_collate'] = $this->language->get('step_collate');
                $this->data['step_column'] = $this->language->get('step_column');
                $this->data['step_data'] = $this->language->get('step_data');
                $this->data['step_module'] = $this->language->get('step_module');
                $this->data['step_setting'] = $this->language->get('step_setting');
                $this->data['step_configuration'] = $this->language->get('step_configuration');
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
   protected function validateImage(){
   	if( isset($this->request->post['dirImage']) ){
   		if(!is_dir( DIR_DOCUMENT_ROOT . $this->request->post['dirImage'] ) ){
   			$this->error['warning'] = sprintf($this->language->get('error_image_not_found'), $this->request->post['dirImage'],'');
   		}
   	}
   	
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
   }
}
?>
