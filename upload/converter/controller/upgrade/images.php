<?php
class ControllerUpgradeImages extends Controller{
	private $error = array();
    public function index(){
		$this->language->load('upgrade/images');
                $this->lmodel->set('upgrade_images',$this->language->load('upgrade/images'));

		$this->load->model('upgrade/configuration');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['heading_config'] = $this->language->get('heading_config');
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
			'text'      => $this->language->get('text_upgrade_images'),
			'href'      => $this->url->link('upgrade/images'),
			'separator' => false
		);
		
		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		}

	if( !empty( $this->request->post['skip'] ) ) {
		if( !isset($_COOKIE['UpgradeMigration']) ){
						$this->redirect($this->url->link('common/login'));
		}
		$this->data['msg_image_skipped'] = $this->language->get('msg_image_skipped');
		$this->data['skip'] = 'skip';
	}
	if( isset($this->request->post['step']) && !isset($this->request->post['back']) ) { 
                if( isset( $this->request->post ) ){
                  $simulate = ( !empty( $_POST['simulate'] ) ? true : false );
                  $showOps = ( !empty( $_POST['showOps'] ) ? true : false );
                  $dirOld = ( !empty( $_POST['dirOld'] ) ? true : false );
                  $dirAdmin = ( !empty( $_POST['dirAdmin'] ) ? true : 'admin' );
                  $this->data['showOps'] = $showOps;
                  $this->data['simulate'] = $simulate;
                }
	   if( $this->validateAdmin()){
                  $this->data['update_configuration'] = $this->model_upgrade_configuration->editConfig( $this->request->post );
       }
     }
                $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );


		if(isset($this->request->post['upgrade'])){
		  $this->data['upgrade'] = $this->request->post['upgrade'];
		} else {
			$this->data['upgrade'] = 2031;
		}
		if(isset($this->request->post['back'])){
			$this->data['back'] = true;
		}
                $this->data['action'] = $this->url->link('upgrade/clean');
                $this->data['text_images_info'] = $this->language->get('text_images_info');
                $this->data['header_step'] = $this->language->get('header_step_configuration');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['btn_images'] = $this->language->get('btn_images');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['btn_preview'] = $this->language->get('btn_preview');
                $this->data['btn_back'] = $this->language->get('btn_back');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);
                $this->data['step'] = $step+1;
                $this->data['steps'] = $steps;
                $this->data['entry_imageDir'] = $this->language->get('entry_imageDir');
                $this->data['help_imageDir'] = $this->language->get('help_imageDir');
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

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
                $this->data['step'] = $step-1;
                $this->data['previous'] = $this->url->link('upgrade/configuration');
		} else {
			$this->data['error_warning'] = '';
		}
		$this->template = 'upgrade/images.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/images')) {
			$this->error['warning'] = $this->language->get('error_permission');
			return true;
		}
		if (!$this->error) {
			return true;
		} else { 
			return false;
		}
   }
   protected function validateAdmin(){
   	if( isset($this->request->post['dirAdmin']) ){
   		if(!is_dir( DIR_DOCUMENT_ROOT . $this->request->post['dirAdmin'] ) ){
   			$this->error['warning'] = sprintf($this->language->get('error_admin_not_found'), $this->request->post['dirAdmin'],'');
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
