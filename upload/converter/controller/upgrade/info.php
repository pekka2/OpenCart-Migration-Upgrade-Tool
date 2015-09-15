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

                $upgrade_level = $this->structure->getVersion();
                $level = $upgrade_level['level'];
                $same_tables = array();
				$this->data['text_database_info'] = $this->language->get('text_database_info');
                $this->data['text_missing_tables'] = $this->language->get('text_missing_tables');
                $this->data['text_your_db_tables'] = $this->language->get('text_your_db_tables');
                $this->data['text_your_oc_tables'] = $this->language->get('text_your_oc_tables');
                $this->data['text_your_other_tables'] = $this->language->get('text_your_other_tables');
                $this->data['text_oc2_db_tables'] = $this->language->get('text_oc2_db_tables');
                $this->data['text_expired_tables'] = $this->language->get('text_expired_tables');
                $this->data['text_your_database'] = $this->language->get('text_your_database');
                $this->data['text_to_version'] = $this->language->get('text_to_version');
                $this->data['text_your_version'] = $this->language->get('text_your_version');
                $this->data['text_database'] = DB_DATABASE;

                $this->data['btn_database'] = $this->language->get('btn_database');
			    $this->data['btn_permission'] = $this->language->get('btn_permission');
                $this->data['permission'] = $this->url->link('common/home/user');
                $this->data['database'] = $this->url->link('upgrade/start');

                $tables = $this->model_upgrade_info->listTables();            
                $new_oc_verions = $this->model_upgrade_info->getVersion2Tables();
                $getinfo = $this->model_upgrade_info->getInfo();
                $this->data['your_db_total_tables'] = $getinfo['tables'];
                $this->data['your_database_opencart_tables'] = $getinfo['oc_tables'];
                $this->data['your_database_other_tables'] =  $getinfo['tables'] - $getinfo['oc_tables'];
                $this->data['your_database_version'] = $getinfo['version'];
                $this->data['upgrade_database_tables'] = $new_oc_verions;
                if(!array_search( DB_PREFIX . 'module', $this->structure->tables())){
                  $missing_tables = $new_oc_verions - $getinfo['oc_tables'];
               }

                if(isset($getinfo['upgrade'])){
                	 $getinfo['upgrade'] = str_replace('2100','2.1.0.0',$getinfo['upgrade']);
                	 $getinfo['upgrade'] = str_replace('2031','2.0.3.1',$getinfo['upgrade']);
                	 $getinfo['upgrade'] = str_replace('2020','2.0.1.0-2.0.2.0', $getinfo['upgrade']);
                   $this->data['upgrade'] = $getinfo['upgrade'];
                   $this->data['steps'] = $getinfo['steps'];
                   $this->data['upgrade_database_tables'] = $getinfo['oc2_tables'];
                   $this->data['text_oc2_db_tables'] = $this->language->get('text_tables_to_version');
                   $missing_tables = $getinfo['oc2_tables'] - $getinfo['oc_tables'];
                } 
                $expired_tables = count($tables) - $new_oc_verions;
                if($expired_tables < 0 && $level == 0){
                       $expired_tables = 0;
                }

                $this->data['expirend'] = $expired_tables;

                if($missing_tables === 0 && $level == 0){
                  $this->data['text_tables_complete'] = $this->language->get('text_tables_complete');
                } 

                 $this->data['missing'] = $missing_tables;

		$this->template = 'upgrade/info.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		if( !isset($_COOKIE['UpgradeMigration']) ){
						$this->redirect($this->url->link('common/login'));
		}
		$this->response->setOutput($this->render());
	}
}
?>
