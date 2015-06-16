# OPTIONS FROM VERSION 1.4.7-1.4.9.x

INSERT INTO `oc_option` (`option_id`, `type`, `sort_order`)
SELECT `product_option_id`, "select", `sort_order` FROM `oc_product_option`;

INSERT INTO `oc_option_description` (`option_id`, `language_id`, `name`)
SELECT `product_option_id`, `language_id`, `name` FROM `oc_product_option_description`;

INSERT INTO `oc_option_value` (`option_value_id`, `option_id`, `sort_order`)
SELECT `product_option_value_id`, `product_option_id`, `sort_order` FROM `oc_product_option_value`;

INSERT INTO `oc_option_value_description` (`option_value_id`, `language_id`, `option_id`, `name`)
SELECT  `pov`.`product_option_value_id` ,  `language_id` ,  `pov`.`product_option_id` ,  `name` 
FROM  `oc_product_option_value_description` AS `povd` INNER JOIN `oc_product_option_value` AS `pov` ON `pov`.`product_option_value_id` =  `povd`.`product_option_value_id`;

UPDATE `oc_product_option` SET `option_id` = `product_option_id`, `required` = 1;
UPDATE `oc_product_option_value` SET `option_id` = `product_option_id`, `option_value_id` = `product_option_value_id`;

