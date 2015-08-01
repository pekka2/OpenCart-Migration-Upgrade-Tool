<?php
class ControllerUpgradeConfiguration extends Controller {   
	public function index() {
		$this->language->load('upgrade/configuration');
                $this->lmodel->set('upgrade_configuration',$this->language->load('upgrade/configuration'));

		$this->load->model('upgrade/configuration');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_configuration_files'),
			'href'      => $this->url->link('upgrade/configuration''),
			'separator' => false
		);
                 $simulate = 1;

                 $images = ( !empty( $_POST['images'] ) ? true : 'image' );
                if( isset( $this->request->post ) )
	  	   if( !isset($_COOKIE['UpgradeMigration']) ){
			$this->redirect($this->url->link('common/login'));
	 	   }
                  $simulate = ( !empty( $_POST['simulate'] ) ? true : false );
                  $showOps = ( !empty( $_POST['showOps'] ) ? true : false );
                  $dirOld = ( !empty( $_POST['dirOld'] ) ? true : false );
                  $dirAdmin = ( !empty( $_POST['dirAdmin'] ) ? true : 'admin' );
                  $this->data['dirOld'] = $dirOld;
                  $this->data['images'] = $images;
                  $this->data['dirAdmin'] = $dirAdmin;
                  $this->data['showOps'] = $showOps;
                  $this->data['simulate'] = $simulate;
                }
                if( !isset( $this->request->post['skip'] ) && isset( $this->request->post['step2']) ){
                  $this->data['step2'] = 'step2';
                  $this->data['upgrade_data'] = $this->model_upgrade_configuration->editConfig( $simulate, $dirOld, $dirAdmin );
                } 
                if( isset( $this->request->post['skip'] ) ){
                 $this->data['skip'] = 'skip';
                 $this->data['msg_config_skipped'] = $this->language->get('msg_config_skipped');
                }

	$path		          = DIR_DOCUMENT_ROOT . 'system/logs/';
	$this->data['permission'] = substr( sprintf( '%o', fileperms( $path ) ), -4 );
	$this->data['perm']	  = array( '755', '775', '777' );
	$this->data['data']       = $images . '/data';

                $this->data['database'] = $this->url->link('upgrade/database');
                $this->data['imagepaths'] = $this->url->link('upgrade/images');
                $this->data['text_step_3_3'] = $this->language->get('text_step_3_3');
                $this->data['text_intro_step_3'] = $this->language->get('text_intro_step_3');
                $this->data['entry_perms'] = $this->language->get('entry_perms');
                $this->data['help_perms'] = $this->language->get('help_perms');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['header_step_3'] = $this->language->get('header_step_3');
                $this->data['button_database'] = $this->language->get('button_database');
                $this->data['langCur'] = $this->language->get('langCur');
                $this->data['text_curr_setting'] = $this->language->get('text_curr_setting');
                $this->data['text_images_info'] = $this->language->get('text_images_info');
                $this->data['btn_continue'] = $this->language->get('btn_continue');
                $this->data['btn_cancel'] = $this->language->get('btn_cancel');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');

		$this->template = 'upgrade/configuration.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
}
?>
