<?php   
class ControllerCommonHome extends Controller {   
        private $error = array();
	public function index() {

		$this->language->load('common/home');
		$this->language->load('/readme');
                $this->lmodel->set('common_home',$this->language->load('common/home'));

		$this->document->setTitle(sprintf($this->language->get('heading_title'),VERSION,''));

		$this->data['heading_title'] = sprintf($this->language->get('heading_title'),VERSION,'');
                /* Readme Texts */
		$this->data['text_title_help'] = $this->language->get('text_title_help');
		$this->data['text_top_help'] = $this->language->get('text_top_help');
		$this->data['text_top_help_2'] = $this->language->get('text_top_help_2');
		$this->data['text_license'] = $this->language->get('text_license');
		$this->data['text_author'] = $this->language->get('text_author');
		$this->data['sub_title_1'] = $this->language->get('sub_title_1');
		$this->data['sub_title_2'] = $this->language->get('sub_title_2');
		$this->data['sub_title_3'] = $this->language->get('sub_title_3');
		$this->data['sub_title_4'] = $this->language->get('sub_title_4');
		$this->data['sub_title_5'] = $this->language->get('sub_title_5');
		$this->data['sub_title_6'] = $this->language->get('sub_title_6');
		$this->data['sub_title_7'] = $this->language->get('sub_title_7');
		$this->data['sub_title_8'] = $this->language->get('sub_title_8');
		$this->data['sub_title_9'] = $this->language->get('sub_title_9');
		$this->data['sub_title_10'] = $this->language->get('sub_title_10');
		$this->data['sub_title_11'] = $this->language->get('sub_title_11');
		$this->data['sub_title_12'] = $this->language->get('sub_title_12');
		$this->data['sub_title_13'] = $this->language->get('sub_title_13');
		$this->data['sub_title_14'] = $this->language->get('sub_title_14');
		$this->data['sub_title_15'] = $this->language->get('sub_title_15');
		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_features_1'] = $this->language->get('text_features_1');
		$this->data['text_features_2'] = $this->language->get('text_features_2');
		$this->data['text_features_3'] = $this->language->get('text_features_3');
		$this->data['text_features_4'] = $this->language->get('text_features_4');
		$this->data['text_features_5'] = $this->language->get('text_features_5');
		$this->data['text_features_6'] = $this->language->get('text_features_6');
		$this->data['text_features_7'] = $this->language->get('text_features_7');
		$this->data['text_features_8'] = $this->language->get('text_features_8');
		$this->data['text_features_9'] = $this->language->get('text_features_9');
		$this->data['text_features_10'] = $this->language->get('text_features_10');
		$this->data['text_features_11'] = $this->language->get('text_features_11');
		$this->data['text_install'] = $this->language->get('text_install');
		$this->data['text_error_token'] = $this->language->get('text_error_token');
		$this->data['text_functions'] = $this->language->get('text_functions');
		$this->data['text_permissions'] = $this->language->get('text_permissions');
		$this->data['text_steps'] = $this->language->get('text_steps');
		$this->data['text_step_1_1'] = $this->language->get('text_step_1_1');
		$this->data['text_step_1_2'] = $this->language->get('text_step_1_2');
		$this->data['text_step_1_3'] = $this->language->get('text_step_1_3');
		$this->data['text_step_1_4'] = $this->language->get('text_step_1_4');
		$this->data['text_step_1_5'] = $this->language->get('text_step_1_5');
		$this->data['text_step_1_6'] = $this->language->get('text_step_1_6');
		$this->data['text_step_1_7'] = $this->language->get('text_step_1_7');
		$this->data['text_step_1_8'] = $this->language->get('text_step_1_8');
		$this->data['text_step_1_9'] = $this->language->get('text_step_1_9');
		$this->data['text_step_1_10'] = $this->language->get('text_step_1_10');
		$this->data['text_step_2_1'] = $this->language->get('text_step_2_1');
		$this->data['text_step_2_2'] = $this->language->get('text_step_2_2');
		$this->data['text_step_2_3'] = $this->language->get('text_step_2_3');
		$this->data['text_step_3_1'] = $this->language->get('text_step_3_1');
		$this->data['text_step_3_2'] = $this->language->get('text_step_3_2');
		$this->data['text_plan_1_1'] = $this->language->get('text_plan_1_1');
		$this->data['text_plan_1_2'] = $this->language->get('text_plan_1_2');
		$this->data['text_plan_1_3'] = $this->language->get('text_plan_1_3');
		$this->data['text_plan_1_4'] = $this->language->get('text_plan_1_4');
		$this->data['text_plan_1_5'] = $this->language->get('text_plan_1_5');
		$this->data['text_plan_2_1'] = $this->language->get('text_plan_2_1');
		$this->data['text_plan_2_2'] = $this->language->get('text_plan_2_2');
		$this->data['text_plan_2_3'] = $this->language->get('text_plan_2_3');
		$this->data['text_plan_2_4'] = $this->language->get('text_plan_2_4');
		$this->data['text_plan_2_5'] = $this->language->get('text_plan_2_5');
		$this->data['text_plan_2_6'] = $this->language->get('text_plan_2_6');
		$this->data['text_plan_2_7'] = $this->language->get('text_plan_2_7');
		$this->data['text_plan_2_8'] = $this->language->get('text_plan_2_8');
		$this->data['text_plan_3_1'] = $this->language->get('text_plan_3_1');
		$this->data['text_repeat_1_1'] = $this->language->get('text_repeat_1_1');
		$this->data['text_repeat_2_1'] = $this->language->get('text_repeat_2_1');
		$this->data['text_repeat_2_2'] = $this->language->get('text_repeat_2_2');
		$this->data['text_repeat_2_3'] = $this->language->get('text_repeat_2_3');
		$this->data['text_repeat_2_4'] = $this->language->get('text_repeat_2_4');
		$this->data['text_repeat_2_5'] = $this->language->get('text_repeat_2_5');
		$this->data['text_repeat_2_6'] = $this->language->get('text_repeat_2_6');
		$this->data['text_repeat_3_1'] = $this->language->get('text_repeat_3_1');
		$this->data['text_repeat_3_2'] = $this->language->get('text_repeat_3_2');
		$this->data['text_repeat_3_3'] = $this->language->get('text_repeat_3_3');
		$this->data['text_repeat_4_1'] = $this->language->get('text_repeat_4_1');
		$this->data['text_repeat_4_2'] = $this->language->get('text_repeat_4_2');
		$this->data['text_repeat_4_3'] = $this->language->get('text_repeat_4_3');
		$this->data['text_repeat_4_4'] = $this->language->get('text_repeat_4_4');
		$this->data['text_repeat_4_5'] = $this->language->get('text_repeat_4_5');
		$this->data['text_repeat_4_6'] = $this->language->get('text_repeat_4_6');
		$this->data['text_repeat_4_7'] = $this->language->get('text_repeat_4_7');
		$this->data['text_repeat_4_8'] = $this->language->get('text_repeat_4_8');
		$this->data['text_repeat_4_9'] = $this->language->get('text_repeat_4_9');
		$this->data['text_repeat_4_10'] = $this->language->get('text_repeat_4_10');
		$this->data['text_repeat_4_11'] = $this->language->get('text_repeat_4_11');
		$this->data['text_repeat_4_12'] = $this->language->get('text_repeat_4_12');
		$this->data['text_repeat_4_13'] = $this->language->get('text_repeat_4_13');
		$this->data['text_repeat_4_14'] = $this->language->get('text_repeat_4_14');
		$this->data['text_repeat_4_15'] = $this->language->get('text_repeat_4_15');
		$this->data['text_repeat_4_16'] = $this->language->get('text_repeat_4_16');
		$this->data['text_repeat_4_17'] = $this->language->get('text_repeat_4_17');
		$this->data['text_forbidden_1'] = $this->language->get('text_forbidden_1');
		$this->data['text_forbidden_2'] = $this->language->get('text_forbidden_2');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_warrantly'] = $this->language->get('text_warrantly');

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$this->data['error_install'] = $this->language->get('error_install');
		} else {
			$this->data['error_install'] = '';
		}

		$this->load->model('tool/database');

	        $this->data['start_status'] = $this->model_tool_database->hasSetting('simulation_status');

		$this->data['button_permission'] = $this->language->get('button_permission');
		$this->data['button_upgrade'] = $this->language->get('button_upgrade');

                $this->data['permission'] = $this->url->link('common/home/user', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['upgrade_info'] = $this->url->link('upgrade/info', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'common/home.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}
	public function login() {
		$route = '';

		if (isset($this->request->get['route'])) {
            
			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}
		}

		$ignore = array(
			'common/login',
			'common/forgotten',
			'common/reset'
		);	
 
		if (!$this->user->isLogged() && !in_array($route, $ignore)) {
  
			return $this->forward('common/login');
		}

		if (isset($this->request->get['route'])) {
    
			$ignore = array(
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
                                'common/home/user',
				'error/not_found',
				'error/permission'
			);

			$config_ignore = array();

			if ($this->config->get('config_token_ignore')) {
				$config_ignore = unserialize($this->config->get('config_token_ignore'));
			}

			$ignore = array_merge($ignore, $config_ignore);

			if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
				return $this->forward('common/login');
			}
		} else {
			if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
				return $this->forward('common/login');
			}
		}
	}

	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';

		$part = explode('/', $this->request->get['route']);

		$this->data['button_permission'] = $this->language->get('button_permission');
                $this->data['permission'] = $this->url->link('common/home/user', 'token=' . $this->session->data['token'], 'SSL');
			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'		
			);			

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return $this->forward('error/permission');
			}
		}
	}	
	public function user() {
		
		$this->language->load('common/user');
		$this->load->model('user/user_group');

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_permissions'),
			'href'      => $this->url->link('common/home/user', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['text_new_permissions'] = $this->language->get('text_new_permissions');
		$this->data['button_upgrade'] = $this->language->get('button_upgrade');
                $this->data['action'] = $this->url->link('common/home/user', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['upgrade_info'] = $this->url->link('upgrade/info', 'token=' . $this->session->data['token'], 'SSL');

                $user_group_info = $this->model_user_user_group->getUserGroups();
                $this->data['user_group_info'] = $user_group_info;

                $user_groups = array();
                $user_group_name = array();
                foreach($user_group_info as $user_group){
                       $user_group_name[$user_group['user_group_id']] = $user_group['name'];
                    if($user_group['permission'] !=''){
                       $user_groups[$user_group['user_group_id']] = unserialize($user_group['permission']);
                     } else {
                       $user_groups[$user_group['user_group_id']] = array();
                   }                                         
                 }
                 $upgrade_access = array(
                                         'upgrade/info',
                                         'upgrade/database',
                                         'upgrade/configuration',
                                         'upgrade/images'
                  );
               
                $ok = 0;

       if( isset($this->request->post['user_group']) && $this->validate() ){
            $user_group = $this->model_user_user_group->getUserGroup($this->request->post['user_group']);

            foreach($upgrade_access as $perm){
                    if( !array_search($perm, $user_group['modify']) ){
                       $user_group['access'][] = $perm;
                       $user_group['modify'][] = $perm;
                       ++$ok;
                    }
            }
    

           if($ok === 0){  

              $this->data['upgrade_access'] = $upgrade_access;  
           } 
           if($ok > 0){
                  $this->model_user_user_group->editUserGroup($this->request->post['user_group'], $user_group );
                  $this->data['upgrade_access'] = $upgrade_access;
            }
        }
   
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

               
		$this->template = 'common/user.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
   }
   protected function validate() {
		if (!$this->user->hasPermission('modify', 'user/user')) {
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
