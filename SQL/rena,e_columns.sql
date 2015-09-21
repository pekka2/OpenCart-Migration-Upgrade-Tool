# RENAME NEW COLUMS
ALTER TABLE `oc_product_option` CHANGE `option_value` `value` text NOT NULL;
ALTER TABLE `oc_setting` CHANGE `group` `code` varchar(32) NOT NULL;
ALTER TABLE `oc_order` CHANGE `invoice_id` `invoice_no` int(11) NOT NULL;
ALTER TABLE `oc_order` CHANGE `currency` `currency_code` varchar(3) NOT NULL;
ALTER TABLE `oc_order_recurring` CHANGE `profile_reference` `reference` varchar(255) NOT NULL;
ALTER TABLE `oc_order_recurring` CHANGE `profile_name` `recurring_name` varchar(255) NOT NULL;
ALTER TABLE `oc_order_recurring` CHANGE `profile_description` `recurring_description` varchar(255) NOT NULL;
ALTER TABLE `oc_order_recurring` CHANGE `profile_id` `recurring_id` int(11) NOT NULL;
ALTER TABLE `oc_order_recurring` CHANGE `created` `date_added` datetime NOT NULL;
ALTER TABLE `oc_order_recurring_transaction` CHANGE `created` `date_added` datetime NOT NULL;
