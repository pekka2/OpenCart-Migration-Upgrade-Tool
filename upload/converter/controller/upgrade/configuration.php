<?php
class ControllerUpgradeConfiguration extends Controller {
	public function index() {
		$this->language->load('upgrade/configuration');
                $this->lmodel->set('upgrade_configuration',$this->language->load('upgrade/configuration'));

		$this->load->model('upgrade/settings');

		$this->data['heading_title'] = $this->language->get('heading_title');
                $this->data['heading_database'] = $this->language->get('heading_database');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_configuration_files'),
			'href'      => $this->url->link('upgrade/configuration')
		);
               $this->data['breadcrumbs'][] = array(
                       'text'      => $this->language->get('btn_start'),
                       'href'      => $this->url->link('upgrade/start')
               );

		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 4;
			$step = 1;
		}

		if(isset($this->request->post['upgrade'])){
		  $this->data['upgrade'] = $this->request->post['upgrade'];
		} else {
			$this->data['upgrade'] = 2031;
		}
                 $simulate = 1;

					if( !isset($_COOKIE['UpgradeMigration']) ){
									$this->redirect($this->url->link('common/login'));
					}
                if( isset( $this->request->post ) ){
                  $simulate = ( !empty( $_POST['simulate'] ) ? true : false );
                  $showOps = ( !empty( $_POST['showOps'] ) ? true : false );
                  $dirAdmin = ( !empty( $_POST['dirAdmin'] ) ? true : 'admin' );
                  $this->data['images'] = $images;
                  $this->data['dirAdmin'] = $dirAdmin;
                  $this->data['showOps'] = $showOps;
                  $this->data['simulate'] = $simulate;
                }
                if( !isset( $this->request->post['skip'] ) && isset( $this->request->post['step']) ){

                  $this->data['add_settings'] = $this->model_upgrade_settings->addSetting( $this->request->post );
                } 
                if( isset( $this->request->post['skip']) && !isset( $this->request->post['back']) ){
                 $this->data['skip'] = 'skip';
                 $this->data['msg_setting_skipped'] = $this->language->get('msg_setting_skipped');
                }
    if(isset($this->request->post['back'])){
      $this->data['back'] = true;
    }
                $this->data['action'] = $this->url->link('upgrade/images');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_configuration_info'] = $this->language->get('text_configuration_info');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['header_step_setting'] = $this->language->get('header_step_setting');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_cancel'] = $this->language->get('btn_cancel');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);
                $this->data['step'] = $step+1;
                $this->data['steps'] = $steps;
                $this->data['entry_adminDir'] = $this->language->get('entry_adminDir');
                $this->data['help_adminDir'] = $this->language->get('help_adminDir');

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
		$this->template = 'upgrade/configuration.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
}
?>
