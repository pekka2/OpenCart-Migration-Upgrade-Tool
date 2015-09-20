<?php
class ControllerUpgradeSettings extends Controller {
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
		$this->load->model('upgrade/info');
        $this->load->model('upgrade/module');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_upgrade_info'),
			'href'      => $this->url->link('upgrade/info')
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('btn_start'),
			'href'      => $this->url->link('upgrade/start')
		);

		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		} else {
			$steps = 5;
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
                $modules = $this->structure->hasModule();
                 if( !$modules || isset($this->request->post['modules']) ) {
                         $this->data['add_modules'] = $this->model_upgrade_module->getChangeModule( $this->request->post );
                 } else {
                        $this->data['add_modules'] = '';
                 }
                } 
                $this->data['themes'] = $this->model_upgrade_info->getThemes();

                $this->data['action'] = $this->url->link('upgrade/images');
                $this->data['text_intro_1'] = $this->language->get('text_intro_1');
                $this->data['text_intro_2'] = $this->language->get('text_intro_2');
                $this->data['text_intro_3'] = $this->language->get('text_intro_3');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);
                $this->data['step'] = $step + 1;
                $this->data['steps'] = $steps;
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_setting_info'] = $this->language->get('text_setting_info');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['text_update_theme'] = $this->language->get('text_update_theme');
                $this->data['text_skip_theme'] = $this->language->get('text_skip_theme');
                $this->data['header_step_module'] = $this->language->get('header_step_module');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['button_database'] = $this->language->get('button_database');
 
                $this->data['text_exa_store_path'] = $this->language->get('text_exa_store_path');
                $this->data['entry_imageDir'] = $this->language->get('entry_imageDir');
                $this->data['help_imageDir'] = $this->language->get('help_imageDir');
                $this->data['btn_setting'] = $this->language->get('btn_setting');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_skip'] = $this->language->get('btn_skip');

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
                $this->data['config_theme'] = $this->config->get('config_template');
		$this->template = 'upgrade/settings.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/database')) {
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
