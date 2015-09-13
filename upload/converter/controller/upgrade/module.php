<?php
class ControllerUpgradeModule extends Controller {
        private $error = array();
    private $max;
    private $min;
   public function index() {
		$this->language->load('upgrade/database');
		$this->load->model('upgrade/info');
		$this->load->model('upgrade/database');

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

		$version = $this->structure->getVersion();

		
		if(isset($this->request->post['steps'])){
		  $step = $this->request->post['step'];
		  $steps = $this->request->post['steps'];
		}else {
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
                 $this->data['add_columns'] = $this->model_upgrade_database->addData( $this->request->post );

             }  
                $this->data['action'] = $this->url->link('upgrade/settings');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_module_info'] = $this->language->get('text_module_info');
                $this->data['header_step_column'] = $this->language->get('header_step_column');
                $this->data['header_step'] = $this->language->get('header_step');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),$step,$steps);
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['button_database'] = $this->language->get('button_database');
                $this->data['entry_migration_module'] = $this->language->get('entry_migration_module');
                $this->data['step'] = $step+1;
                $this->data['steps'] = $steps;

                $this->data['text_exa_store_path'] = $this->language->get('text_exa_store_path');
                $this->data['btn_module'] = $this->language->get('btn_module');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['text_toggle'] = $this->language->get('text_toggle');
                $this->data['text_toggle_help'] = $this->language->get('text_toggle_help');
                $this->data['help_usage'] = $this->language->get('help_usage');

                $this->data['themes'] = $this->model_upgrade_info->getThemes();
                $this->data['config_theme'] = $this->config->get('config_template');
 
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
		$this->template = 'upgrade/module.tpl';

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
