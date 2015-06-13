UPDATE `oc_banner_image` SET `image` =   REPLACE ( image , 'data/', 'catalog/' );
UPDATE `oc_category` SET `image` =   REPLACE ( image , 'data/', 'catalog/' );
UPDATE `oc_manufacturer` SET `image` =   REPLACE ( image , 'data/', 'catalog/' );
UPDATE `oc_product` SET `image` =   REPLACE ( image , 'data/', 'catalog/' );
UPDATE `oc_product_image` SET `image` =   REPLACE ( image , 'data/', 'catalog' );
UPDATE `oc_setting` SET `value` =  REPLACE ( value , 'data/', 'catalog/' ) WHERE `key` = 'config_logo';
UPDATE `oc_setting` SET `value` =  REPLACE ( value , 'data/', 'catalog/' ) WHERE `key` = 'config_icon';
