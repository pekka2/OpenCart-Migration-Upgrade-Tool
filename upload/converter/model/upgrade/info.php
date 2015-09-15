<?php
class ModelUpgradeInfo extends Model{
  public function listTables() {

       $query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

        $table_list = array();
        foreach($query->rows as $table){
                      $table_list[] = $table['Tables_in_'. DB_DATABASE];
          }
        return $table_list;
  }
  public function getVersion2Tables() {

   $tables = 123;
   
  return $tables;
  }
  public function getAdminPermissions() {

    $permission = 'a:2:{s:6:"access";a:187:{i:0;s:17:"catalog/attribute";i:1;s:23:"catalog/attribute_group";i:2;s:16:"catalog/category";i:3;s:16:"catalog/download";i:4;s:14:"catalog/filter";i:5;s:19:"catalog/information";i:6;s:20:"catalog/manufacturer";i:7;s:14:"catalog/option";i:8;s:15:"catalog/product";i:9;s:17:"catalog/recurring";i:10;s:14:"catalog/review";i:11;s:18:"common/column_left";i:12;s:18:"common/filemanager";i:13;s:11:"common/menu";i:14;s:14:"common/profile";i:15;s:12:"common/stats";i:16;s:18:"dashboard/activity";i:17;s:15:"dashboard/chart";i:18;s:18:"dashboard/customer";i:19;s:13:"dashboard/map";i:20;s:16:"dashboard/online";i:21;s:15:"dashboard/order";i:22;s:16:"dashboard/recent";i:23;s:14:"dashboard/sale";i:24;s:13:"design/banner";i:25;s:13:"design/layout";i:26;s:14:"extension/feed";i:27;s:19:"extension/installer";i:28;s:22:"extension/modification";i:29;s:16:"extension/module";i:30;s:17:"extension/openbay";i:31;s:17:"extension/payment";i:32;s:18:"extension/shipping";i:33;s:15:"extension/total";i:34;s:16:"feed/google_base";i:35;s:19:"feed/google_sitemap";i:36;s:15:"feed/openbaypro";i:37;s:20:"localisation/country";i:38;s:21:"localisation/currency";i:39;s:21:"localisation/geo_zone";i:40;s:21:"localisation/language";i:41;s:25:"localisation/length_class";i:42;s:21:"localisation/location";i:43;s:25:"localisation/order_status";i:44;s:26:"localisation/return_action";i:45;s:26:"localisation/return_reason";i:46;s:26:"localisation/return_status";i:47;s:25:"localisation/stock_status";i:48;s:22:"localisation/tax_class";i:49;s:21:"localisation/tax_rate";i:50;s:25:"localisation/weight_class";i:51;s:17:"localisation/zone";i:52;s:19:"marketing/affiliate";i:53;s:17:"marketing/contact";i:54;s:16:"marketing/coupon";i:55;s:19:"marketing/marketing";i:56;s:14:"module/account";i:57;s:16:"module/affiliate";i:58;s:20:"module/amazon_button";i:59;s:29:"module/amazon_checkout_layout";i:60;s:13:"module/banner";i:61;s:17:"module/bestseller";i:62;s:15:"module/carousel";i:63;s:15:"module/category";i:64;s:18:"module/ebaydisplay";i:65;s:15:"module/featured";i:66;s:13:"module/filter";i:67;s:22:"module/google_hangouts";i:68;s:11:"module/html";i:69;s:19:"module/html_content";i:70;s:18:"module/information";i:71;s:13:"module/latest";i:72;s:14:"module/openbay";i:73;s:16:"module/pp_button";i:74;s:16:"module/pp_layout";i:75;s:15:"module/pp_login";i:76;s:16:"module/slideshow";i:77;s:14:"module/special";i:78;s:12:"module/store";i:79;s:14:"openbay/amazon";i:80;s:22:"openbay/amazon_listing";i:81;s:22:"openbay/amazon_product";i:82;s:16:"openbay/amazonus";i:83;s:24:"openbay/amazonus_listing";i:84;s:24:"openbay/amazonus_product";i:85;s:12:"openbay/ebay";i:86;s:20:"openbay/ebay_profile";i:87;s:21:"openbay/ebay_template";i:88;s:12:"openbay/etsy";i:89;s:20:"openbay/etsy_product";i:90;s:21:"openbay/etsy_shipping";i:91;s:17:"openbay/etsy_shop";i:92;s:23:"payment/amazon_checkout";i:93;s:24:"payment/authorizenet_aim";i:94;s:24:"payment/authorizenet_sim";i:95;s:21:"payment/bank_transfer";i:96;s:22:"payment/bluepay_hosted";i:97;s:27:"payment/bluepay_hosted_form";i:98;s:24:"payment/bluepay_redirect";i:99;s:14:"payment/cheque";i:100;s:11:"payment/cod";i:101;s:17:"payment/firstdata";i:102;s:24:"payment/firstdata_remote";i:103;s:21:"payment/free_checkout";i:104;s:22:"payment/klarna_account";i:105;s:22:"payment/klarna_invoice";i:106;s:14:"payment/liqpay";i:107;s:20:"payment/moneybookers";i:108;s:14:"payment/nochex";i:109;s:15:"payment/paymate";i:110;s:16:"payment/paypoint";i:111;s:13:"payment/payza";i:112;s:26:"payment/perpetual_payments";i:113;s:18:"payment/pp_express";i:114;s:18:"payment/pp_payflow";i:115;s:25:"payment/pp_payflow_iframe";i:116;s:14:"payment/pp_pro";i:117;s:21:"payment/pp_pro_iframe";i:118;s:19:"payment/pp_standard";i:119;s:14:"payment/realex";i:120;s:21:"payment/realex_remote";i:121;s:22:"payment/sagepay_direct";i:122;s:22:"payment/sagepay_server";i:123;s:18:"payment/sagepay_us";i:124;s:24:"payment/securetrading_pp";i:125;s:24:"payment/securetrading_ws";i:126;s:14:"payment/skrill";i:127;s:19:"payment/twocheckout";i:128;s:28:"payment/web_payment_software";i:129;s:16:"payment/worldpay";i:130;s:16:"report/affiliate";i:131;s:25:"report/affiliate_activity";i:132;s:22:"report/affiliate_login";i:133;s:24:"report/customer_activity";i:134;s:22:"report/customer_credit";i:135;s:21:"report/customer_login";i:136;s:22:"report/customer_online";i:137;s:21:"report/customer_order";i:138;s:22:"report/customer_reward";i:139;s:16:"report/marketing";i:140;s:24:"report/product_purchased";i:141;s:21:"report/product_viewed";i:142;s:18:"report/sale_coupon";i:143;s:17:"report/sale_order";i:144;s:18:"report/sale_return";i:145;s:20:"report/sale_shipping";i:146;s:15:"report/sale_tax";i:147;s:17:"sale/custom_field";i:148;s:13:"sale/customer";i:149;s:20:"sale/customer_ban_ip";i:150;s:19:"sale/customer_group";i:151;s:10:"sale/order";i:152;s:14:"sale/recurring";i:153;s:11:"sale/return";i:154;s:12:"sale/voucher";i:155;s:18:"sale/voucher_theme";i:156;s:15:"setting/setting";i:157;s:13:"setting/store";i:158;s:16:"shipping/auspost";i:159;s:17:"shipping/citylink";i:160;s:14:"shipping/fedex";i:161;s:13:"shipping/flat";i:162;s:13:"shipping/free";i:163;s:13:"shipping/item";i:164;s:23:"shipping/parcelforce_48";i:165;s:15:"shipping/pickup";i:166;s:19:"shipping/royal_mail";i:167;s:12:"shipping/ups";i:168;s:13:"shipping/usps";i:169;s:15:"shipping/weight";i:170;s:11:"tool/backup";i:171;s:14:"tool/error_log";i:172;s:11:"tool/upload";i:173;s:12:"total/coupon";i:174;s:12:"total/credit";i:175;s:14:"total/handling";i:176;s:16:"total/klarna_fee";i:177;s:19:"total/low_order_fee";i:178;s:12:"total/reward";i:179;s:14:"total/shipping";i:180;s:15:"total/sub_total";i:181;s:9:"total/tax";i:182;s:11:"total/total";i:183;s:13:"total/voucher";i:184;s:8:"user/api";i:185;s:9:"user/user";i:186;s:20:"user/user_permission";}s:6:"modify";a:187:{i:0;s:17:"catalog/attribute";i:1;s:23:"catalog/attribute_group";i:2;s:16:"catalog/category";i:3;s:16:"catalog/download";i:4;s:14:"catalog/filter";i:5;s:19:"catalog/information";i:6;s:20:"catalog/manufacturer";i:7;s:14:"catalog/option";i:8;s:15:"catalog/product";i:9;s:17:"catalog/recurring";i:10;s:14:"catalog/review";i:11;s:18:"common/column_left";i:12;s:18:"common/filemanager";i:13;s:11:"common/menu";i:14;s:14:"common/profile";i:15;s:12:"common/stats";i:16;s:18:"dashboard/activity";i:17;s:15:"dashboard/chart";i:18;s:18:"dashboard/customer";i:19;s:13:"dashboard/map";i:20;s:16:"dashboard/online";i:21;s:15:"dashboard/order";i:22;s:16:"dashboard/recent";i:23;s:14:"dashboard/sale";i:24;s:13:"design/banner";i:25;s:13:"design/layout";i:26;s:14:"extension/feed";i:27;s:19:"extension/installer";i:28;s:22:"extension/modification";i:29;s:16:"extension/module";i:30;s:17:"extension/openbay";i:31;s:17:"extension/payment";i:32;s:18:"extension/shipping";i:33;s:15:"extension/total";i:34;s:16:"feed/google_base";i:35;s:19:"feed/google_sitemap";i:36;s:15:"feed/openbaypro";i:37;s:20:"localisation/country";i:38;s:21:"localisation/currency";i:39;s:21:"localisation/geo_zone";i:40;s:21:"localisation/language";i:41;s:25:"localisation/length_class";i:42;s:21:"localisation/location";i:43;s:25:"localisation/order_status";i:44;s:26:"localisation/return_action";i:45;s:26:"localisation/return_reason";i:46;s:26:"localisation/return_status";i:47;s:25:"localisation/stock_status";i:48;s:22:"localisation/tax_class";i:49;s:21:"localisation/tax_rate";i:50;s:25:"localisation/weight_class";i:51;s:17:"localisation/zone";i:52;s:19:"marketing/affiliate";i:53;s:17:"marketing/contact";i:54;s:16:"marketing/coupon";i:55;s:19:"marketing/marketing";i:56;s:14:"module/account";i:57;s:16:"module/affiliate";i:58;s:20:"module/amazon_button";i:59;s:29:"module/amazon_checkout_layout";i:60;s:13:"module/banner";i:61;s:17:"module/bestseller";i:62;s:15:"module/carousel";i:63;s:15:"module/category";i:64;s:18:"module/ebaydisplay";i:65;s:15:"module/featured";i:66;s:13:"module/filter";i:67;s:22:"module/google_hangouts";i:68;s:11:"module/html";i:69;s:19:"module/html_content";i:70;s:18:"module/information";i:71;s:13:"module/latest";i:72;s:14:"module/openbay";i:73;s:16:"module/pp_button";i:74;s:16:"module/pp_layout";i:75;s:15:"module/pp_login";i:76;s:16:"module/slideshow";i:77;s:14:"module/special";i:78;s:12:"module/store";i:79;s:14:"openbay/amazon";i:80;s:22:"openbay/amazon_listing";i:81;s:22:"openbay/amazon_product";i:82;s:16:"openbay/amazonus";i:83;s:24:"openbay/amazonus_listing";i:84;s:24:"openbay/amazonus_product";i:85;s:12:"openbay/ebay";i:86;s:20:"openbay/ebay_profile";i:87;s:21:"openbay/ebay_template";i:88;s:12:"openbay/etsy";i:89;s:20:"openbay/etsy_product";i:90;s:21:"openbay/etsy_shipping";i:91;s:17:"openbay/etsy_shop";i:92;s:23:"payment/amazon_checkout";i:93;s:24:"payment/authorizenet_aim";i:94;s:24:"payment/authorizenet_sim";i:95;s:21:"payment/bank_transfer";i:96;s:22:"payment/bluepay_hosted";i:97;s:27:"payment/bluepay_hosted_form";i:98;s:24:"payment/bluepay_redirect";i:99;s:14:"payment/cheque";i:100;s:11:"payment/cod";i:101;s:17:"payment/firstdata";i:102;s:24:"payment/firstdata_remote";i:103;s:21:"payment/free_checkout";i:104;s:22:"payment/klarna_account";i:105;s:22:"payment/klarna_invoice";i:106;s:14:"payment/liqpay";i:107;s:20:"payment/moneybookers";i:108;s:14:"payment/nochex";i:109;s:15:"payment/paymate";i:110;s:16:"payment/paypoint";i:111;s:13:"payment/payza";i:112;s:26:"payment/perpetual_payments";i:113;s:18:"payment/pp_express";i:114;s:18:"payment/pp_payflow";i:115;s:25:"payment/pp_payflow_iframe";i:116;s:14:"payment/pp_pro";i:117;s:21:"payment/pp_pro_iframe";i:118;s:19:"payment/pp_standard";i:119;s:14:"payment/realex";i:120;s:21:"payment/realex_remote";i:121;s:22:"payment/sagepay_direct";i:122;s:22:"payment/sagepay_server";i:123;s:18:"payment/sagepay_us";i:124;s:24:"payment/securetrading_pp";i:125;s:24:"payment/securetrading_ws";i:126;s:14:"payment/skrill";i:127;s:19:"payment/twocheckout";i:128;s:28:"payment/web_payment_software";i:129;s:16:"payment/worldpay";i:130;s:16:"report/affiliate";i:131;s:25:"report/affiliate_activity";i:132;s:22:"report/affiliate_login";i:133;s:24:"report/customer_activity";i:134;s:22:"report/customer_credit";i:135;s:21:"report/customer_login";i:136;s:22:"report/customer_online";i:137;s:21:"report/customer_order";i:138;s:22:"report/customer_reward";i:139;s:16:"report/marketing";i:140;s:24:"report/product_purchased";i:141;s:21:"report/product_viewed";i:142;s:18:"report/sale_coupon";i:143;s:17:"report/sale_order";i:144;s:18:"report/sale_return";i:145;s:20:"report/sale_shipping";i:146;s:15:"report/sale_tax";i:147;s:17:"sale/custom_field";i:148;s:13:"sale/customer";i:149;s:20:"sale/customer_ban_ip";i:150;s:19:"sale/customer_group";i:151;s:10:"sale/order";i:152;s:14:"sale/recurring";i:153;s:11:"sale/return";i:154;s:12:"sale/voucher";i:155;s:18:"sale/voucher_theme";i:156;s:15:"setting/setting";i:157;s:13:"setting/store";i:158;s:16:"shipping/auspost";i:159;s:17:"shipping/citylink";i:160;s:14:"shipping/fedex";i:161;s:13:"shipping/flat";i:162;s:13:"shipping/free";i:163;s:13:"shipping/item";i:164;s:23:"shipping/parcelforce_48";i:165;s:15:"shipping/pickup";i:166;s:19:"shipping/royal_mail";i:167;s:12:"shipping/ups";i:168;s:13:"shipping/usps";i:169;s:15:"shipping/weight";i:170;s:11:"tool/backup";i:171;s:14:"tool/error_log";i:172;s:11:"tool/upload";i:173;s:12:"total/coupon";i:174;s:12:"total/credit";i:175;s:14:"total/handling";i:176;s:16:"total/klarna_fee";i:177;s:19:"total/low_order_fee";i:178;s:12:"total/reward";i:179;s:14:"total/shipping";i:180;s:15:"total/sub_total";i:181;s:9:"total/tax";i:182;s:11:"total/total";i:183;s:13:"total/voucher";i:184;s:8:"user/api";i:185;s:9:"user/user";i:186;s:20:"user/user_permission";}}';
 
  return unserialize($permission);

  }
  public function addPermissions( $simulate = 1 ){

   if( $this->cache->get('user_group_id' ) ) {

   $user_group = $this->cache->get('user_group_id' );
   $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_group WHERE user_group_id= '" . $user_group['user_group'] ."'");
   $permission = unserialize( $query->row['permission'] );
   $array = $this->getAdminPermissions();
   for($i = 0;$i<count($array['access']);$i++ ){
     if( !array_search($array['access'][$i],$permission['access'])){
      $permission['access'][] = $array['access'][$i];
     }
   }
   for($i = 0;$i<count($array['modify']);$i++ ){
     if( !array_search($array['modify'][$i],$permission['modify'])){
      $permission['modify'][] = $array['modify'][$i];
     }
   }
    $perm = serialize($permission);
     if( !$simulate ){
      $this->db->query("UPDATE " . DB_PREFIX . "user_group SET permission = '" . $perm . "' WHERE user_group_id= '" . $user_group['user_group'] ."'");
     }
   }
  }
  public function addInfo(){
        // Upgrade Memory Log
          $memory = DIR_DATA . '/upgrade_cache.log';
          $cache = array();
          $info = $this->structure->getVersion();
          $level = $info['level'];
          $version = $info['vdata'];
          $tables = $info['tables'];
          $oc_tables = $info['oc_tables'];
          if($level > 4){
            $steps = 10;
          } else {
            $steps = 9;
          }
       
              if( !file_exists($memory) ){
                    $cache = array('version' => $version,
                                   'tables' => $tables,
                                   'oc_tables' => $oc_tables,
                                   'steps' => $steps);
              } else {
                   $string = file_get_contents($memory);
                  if(!empty($string)){
                    $cache = unserialize($string);
                    $cache['version'] = $version;
                    $cache['tables'] = $tables;
                    $cache['oc_tables'] = $oc_tables;
                    $cache['steps'] = $steps;
                  }
              }
              if($cache){
                  $str = serialize($cache);
                  $fw = fopen($memory,'wb');
                  fwrite($fw,$str);
                  fclose($fw);
              }
    }
  public function getInfo(){
        // Upgrade Memory Log
          $cache = array();
          $memory = DIR_DATA . '/upgrade_cache.log';
           if( file_exists($memory) ){
                   $string = file_get_contents($memory);
                  if(!empty($string)){
                    $cache = unserialize($string);
                  }
              }
          return $cache;
    }
  public function getThemes(){
    $path = DIR_DOCUMENT_ROOT . 'catalog/view/theme/';
    $themes = array();
    $open = opendir($path);   
         while(( $file = readdir($open) ) !=false) {
               if( is_dir($path. $file) && $file !='.' && $file !='..'){
                  $themes[]['name'] = $file;
              }
        }
     closedir($open); 
     return $themes;
  }
}
