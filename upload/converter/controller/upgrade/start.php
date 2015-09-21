<?php
class ControllerUpgradeStart extends Controller {
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
    $this->lmodel->set('upgrade_database',$this->language->load('upgrade/database'));
		$this->load->model('upgrade/info');

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

    $info = $this->model_upgrade_info->getInfo();
    $version = $info['version'];
    $vdata = $info['version'];
          if( !$info['collate'] ){
           $this->data['action'] = $this->url->link('upgrade/column');
           $this->data['text_version'] = sprintf($this->language->get('text_version'),$vdata,'');
           $steps = 9;
         }
         if( $info['collate'] ){
           $this->data['action'] = $this->url->link('upgrade/collate');
           $this->data['text_version'] = sprintf($this->language->get('text_version'), $vdata,'');
           $this->data['collate'] = true;
           $steps = 10;
         }
        $version_data = array('level' => $steps, 'oc' => $vdata ); 
        $cache = $this->cache->get('version');

        if(empty($cache)){
			  $this->cache->set('version', $version_data );
		  }
 
                $this->data['text_intro_1'] = $this->language->get('text_intro_1');
                $this->data['text_intro_2'] = $this->language->get('text_intro_2');
                $this->data['text_intro_3'] = $this->language->get('text_intro_3');
                $this->data['text_step'] = sprintf($this->language->get('text_step'),1,$steps);
                $this->data['text_step_2_3'] = $this->language->get('text_step_2_3');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_table_info'] = $this->language->get('text_table_info');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['text_toggle'] = $this->language->get('text_toggle');
                $this->data['text_toggle_help'] = $this->language->get('text_toggle_help');
                $this->data['text_exa_store_path'] = $this->language->get('text_exa_store_path');
 
                $this->data['header_step_2'] = $this->language->get('header_step_2');

                $this->data['entry_up_1564'] = $this->language->get('entry_up_1564');
                $this->data['entry_up_2030'] = $this->language->get('entry_up_2030');
                $this->data['entry_up_201_202'] = $this->language->get('entry_up_201_202');
                $this->data['entry_up_2100'] = $this->language->get('entry_up_2100');
                
                $this->data['btn_start'] = $this->language->get('btn_start');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['btn_database'] = $this->language->get('btn_database');
                
                $this->data['help_usage'] = $this->language->get('help_usage');
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
                
                $this->data['steps'] = $steps;
		$this->template = 'upgrade/database.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/start')) {
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
