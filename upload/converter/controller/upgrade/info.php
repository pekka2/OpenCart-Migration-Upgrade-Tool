<?php
class ControllerUpgradeInfo extends Controller {   
	public function index() {
		$this->language->load('upgrade/info');
		$this->load->model('upgrade/info');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['heading_title'] = $this->language->get('heading_title');

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
		   if( !isset($_COOKIE['UpgradeMigration']) ){
			$this->redirect($this->url->link('common/login'));
	 	   }
                $same_tables = array();
		$this->data['text_database_info'] = $this->language->get('text_database_info');
                $this->data['text_missing_tables'] = $this->language->get('text_missing_tables');
                $this->data['text_your_db_tables'] = $this->language->get('text_your_db_tables');
                $this->data['text_oc2_db_tables'] = $this->language->get('text_oc2_db_tables');
                $this->data['text_expired_tables'] = $this->language->get('text_expired_tables');
                $this->data['text_your_database'] = $this->language->get('text_your_database');
                $this->data['text_database'] = DB_DATABASE;
                $this->data['button_database'] = $this->language->get('button_database');

		$this->data['button_permission'] = $this->language->get('button_permission');
                $this->data['permission'] = $this->url->link('common/home/user');
                $this->data['database'] = $this->url->link('upgrade/database');

                $tables = $this->model_upgrade_info->listTables();            
                $new_oc_verions = $this->model_upgrade_info->getVersion2Tables();
                $this->data['your_database_tables'] = count($tables);
                $this->data['upgrade_database_tables'] = $new_oc_verions;
                $missing_tables = $new_oc_verions - count($tables);
                $expired_tables = count($tables) - $new_oc_verions;
                if($expired_tables < 0){
                       $expired_tables = 0;
                }

                $this->data['expirend'] = $expired_tables;

                if($missing_tables === 0){
                  $this->data['text_tables_complete'] = $this->language->get('text_tables_complete');
                } 

                 $this->data['missing'] = $missing_tables;

		$this->template = 'upgrade/info.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
}
?>
