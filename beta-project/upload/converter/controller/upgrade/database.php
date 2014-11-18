<?php
class ControllerUpgradeDatabase extends Controller {   
        private $error = array();
   public function index() {
		$this->language->load('upgrade/database');
                $this->lmodel->set('upgrade_database',$this->language->load('upgrade/database'));
		$this->load->model('upgrade/database');
		$this->load->model('upgrade/table_columns');
		$this->load->model('upgrade/settings');

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_upgrade_info'),
			'href'      => $this->url->link('upgrade/info', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

                if( isset( $this->request->post['step1']) && $this->validate() ){
                 $this->data['dirOld'] = ( !empty( $_POST['dirOld'] ) ? true : false );
                 $this->data['images'] = ( !empty( $_POST['images'] ) ? true : 'image' );
                 $this->data['showOps'] = ( !empty( $_POST['showOps'] ) ? true : false );
                 $this->data['simulate'] = ( !empty( $_POST['simulate'] ) ? true : false );
                 $this->data['adminDir'] = ( !empty( $_POST['adminDir'] ) ? true : 'admin' );
                 $this->data['step1'] = 'step1';
                 $this->data['upgrade_data'] = $this->model_upgrade_database->addTables( $this->request->post );
                 $this->data['change_taxrate'] = $this->model_upgrade_table_columns->changeTaxRate( $this->request->post );
                 $this->data['add_columns'] = $this->model_upgrade_table_columns->addColumns( $this->request->post );
                 $this->data['drop_tables'] = $this->model_upgrade_table_columns->deleteTables( $this->request->post );
                 $this->data['add_settings'] = $this->model_upgrade_settings->getChangeModule( $this->request->post );
                }
                $this->data['database'] = $this->url->link('upgrade/database', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['configuration'] = $this->url->link('upgrade/configuration', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['text_intro_1'] = $this->language->get('text_intro_1');
                $this->data['text_intro_2'] = $this->language->get('text_intro_2');
                $this->data['text_intro_3'] = $this->language->get('text_intro_3');
                $this->data['text_step_1_3'] = $this->language->get('text_step_1_3');
                $this->data['text_step_2_3'] = $this->language->get('text_step_2_3');
                $this->data['text_simulation'] = $this->language->get('text_simulation');
                $this->data['text_on'] = $this->language->get('text_on');
                $this->data['text_off'] = $this->language->get('text_off');
                $this->data['text_upgrade_info'] = $this->language->get('text_upgrade_info');
                $this->data['text_update_config'] = $this->language->get('text_update_config');
                $this->data['header_step_2'] = $this->language->get('header_step_2');
                $this->data['help_simulate'] = $this->language->get('help_simulate');
                $this->data['help_ops'] = $this->language->get('help_ops');
                $this->data['button_database'] = $this->language->get('button_database');
                $this->data['entry_adminDir'] = $this->language->get('entry_adminDir');
                $this->data['help_adminDir'] = $this->language->get('help_adminDir');
                $this->data['entry_oldDir'] = $this->language->get('entry_oldDir');
                $this->data['help_oldDir'] = $this->language->get('help_oldDir');
                $this->data['text_exa_store_path'] = $this->language->get('text_exa_store_path');
                $this->data['entry_imageDir'] = $this->language->get('entry_imageDir');
                $this->data['help_imageDir'] = $this->language->get('help_imageDir');
                $this->data['langCur'] = $this->language->get('langCur');
                $this->data['btn_start'] = $this->language->get('btn_start');
                $this->data['btn_config'] = $this->language->get('btn_config');
                $this->data['btn_skip'] = $this->language->get('btn_skip');
                $this->data['text_toggle'] = $this->language->get('text_toggle');
                $this->data['text_toggle_help'] = $this->language->get('text_toggle_help');
                $this->data['help_usage'] = $this->language->get('help_usage');

		$this->template = 'upgrade/database.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'upgrade/database')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else { 
			return false;
		}
   }
}
?>
