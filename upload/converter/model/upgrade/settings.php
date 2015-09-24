<?php
class ModelUpgradeSettings extends Model{

  private $settincounter = 0;
  private $lang;
  private $simulate;
  private $upgrade;
  private $showOps;
  private $settings = array();

  public function addSetting( $data ){
        $this->simulate = ( !empty( $data['simulate'] ) ? true : false );
        $this->showOps  = ( !empty( $data['showOps'] ) ? true : false );
        if( !isset($data['theme'] ) ) $data['theme'] = '';
        $this->theme  = ( !empty( $data['theme'] ) ? $data['theme'] : false );
        $this->lang = $this->lmodel->get('upgrade_database');
        $this->upgrade = $data['upgrade'];

        $text = '';
     /* new config settings */
        if($this->upgrade !=1564){
           $text .= $this->getConfigMail();
        }
     $text .= $this->newSettings();

     return $text;
  }

   private function getConfigMail(){
     $text = '';
    if( $this->config->get('config_mail_protocol') && !$this->structure->hasSetting('config_mail') ){
      $config_mail = array();

       $configs = $this->config->get('config_mail_protocol');
       $configs2 = $this->config->get('config_smtp_hostname');
       $configs3 = $this->config->get('config_smtp_username');
       $configs4 = $this->config->get('config_smtp_password');
       $configs5 = $this->config->get('config_smtp_port');
       $configs6 = $this->config->get('config_smtp_timeout');

       $config_mail['protocol'] = ( !empty( $configs ) ) ? $configs : '';
       $config_mail['parameter'] = $this->config->get('config_mail_parameter');
       $config_mail['smtp_hostname'] = ( !empty( $configs2 ) ) ? $configs2 : '';
       $config_mail['smtp_username'] = ( !empty( $configs3 ) ) ? $configs3 : '';
       $config_mail['smtp_password'] = ( !empty( $configs4 ) ) ? $configs4 : '';
       $config_mail['smtp_port'] = ( !empty( $configs5 ) ) ? $configs5 : '';
       $config_mail['smtp_timeout'] = ( !empty( $configs6 ) ) ? $configs6 : '';
 

      $sql = "
             INSERT INTO 
                        " . DB_PREFIX . "setting
             SET
                          `code`= 'config',
                          `key` = 'config_mail',
                          `value` = '" . serialize($config_mail) ."',
                          `serialized` = '1'";
          
                if( !$this->simulate ) {
		      $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }
		++$this->settingcounter;
       $text .= $this->msg( sprintf( $this->lang['msg_config'], 'config_mail', '' ) );


   }
       return $text;
   }

   private function newSettings(){
        $this->lang = $this->lmodel->get('upgrade_database');
           $text = '';
           if( $this->upgrade > 1564 ) {
            $update = array(
                            array('from' => 'title',
                                  'to' => 'meta_title'),
                            array('from' => 'catalog_limit',
                                  'to' => 'product_limit'),
                            array('from' => 'admin_limit',
                                  'to' => 'limit_admin'),
                            array('from' => 'file_extension_allowed',
                                  'to' => 'file_ext_allowed')
                         );
               foreach($update as $up){
                    $sql = "UPDATE `" . DB_PREFIX . "setting` SET `key` = 'config_" . $up['to'] . "' WHERE `key` = 'config_" . $up['from'] . "'";
                if( !$this->simulate ) {
                    $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }

                   $text .= $this->msg( sprintf( $this->lang['msg_config'],  'config_' . $up['to'], DB_PREFIX . 'setting' ) );
              }
             
           }
            $settings = $this->structure->settingData($this->upgrade);
            foreach($settings as $setting){
               if(!$this->structure->hasSetting( trim($setting['key'])) ){
                  $sql = "INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES
                  ('0', 'config','" . $this->db->escape($setting['key']) . "','" . $this->db->escape($setting['value']) . "','" . $setting['serialized'] . "')";
          
                if( !$this->simulate ) {
                    $this->db->query( $sql );
                }
                if( $this->showOps ) {
                      $text .= '<p><pre>' . $sql .'</pre></p>';
                }

    $text .= $this->msg( sprintf( $this->lang['msg_config'], $setting['key'], DB_PREFIX . 'setting' ) );
              }
            }
            return $text;
  }
  public function msg( $data ){
       return str_replace( $data, '<div class="msg round">' . $data .'</div>', $data);
  }
}
