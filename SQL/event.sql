# events to OC 3.0.3.2
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(1, 'activity_customer_add', 'catalog/model/account/customer/addCustomer/after', 'event/activity/addCustomer', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(2, 'activity_customer_edit', 'catalog/model/account/customer/editCustomer/after', 'event/activity/editCustomer', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(3, 'activity_customer_password', 'catalog/model/account/customer/editPassword/after', 'event/activity/editPassword', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(4, 'activity_customer_forgotten', 'catalog/model/account/customer/editCode/after', 'event/activity/forgotten', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(5, 'activity_transaction', 'catalog/model/account/customer/addTransaction/after', 'event/activity/addTransaction', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(6, 'activity_customer_login', 'catalog/model/account/customer/deleteLoginAttempts/after', 'event/activity/login', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(7, 'activity_address_add', 'catalog/model/account/address/addAddress/after', 'event/activity/addAddress', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(8, 'activity_address_edit', 'catalog/model/account/address/editAddress/after', 'event/activity/editAddress', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(9, 'activity_address_delete', 'catalog/model/account/address/deleteAddress/after', 'event/activity/deleteAddress', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(10, 'activity_affiliate_add', 'catalog/model/account/customer/addAffiliate/after', 'event/activity/addAffiliate', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(11, 'activity_affiliate_edit', 'catalog/model/account/customer/editAffiliate/after', 'event/activity/editAffiliate', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(12, 'activity_order_add', 'catalog/model/checkout/order/addOrderHistory/before', 'event/activity/addOrderHistory', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(13, 'activity_return_add', 'catalog/model/account/return/addReturn/after', 'event/activity/addReturn', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(14, 'mail_transaction', 'catalog/model/account/customer/addTransaction/after', 'mail/transaction', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(15, 'mail_forgotten', 'catalog/model/account/customer/editCode/after', 'mail/forgotten', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(16, 'mail_customer_add', 'catalog/model/account/customer/addCustomer/after', 'mail/register', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(17, 'mail_customer_alert', 'catalog/model/account/customer/addCustomer/after', 'mail/register/alert', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(18, 'mail_affiliate_add', 'catalog/model/account/customer/addAffiliate/after', 'mail/affiliate', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(19, 'mail_affiliate_alert', 'catalog/model/account/customer/addAffiliate/after', 'mail/affiliate/alert', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(20, 'mail_voucher', 'catalog/model/checkout/order/addOrderHistory/after', 'extension/total/voucher/send', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(21, 'mail_order_add', 'catalog/model/checkout/order/addOrderHistory/before', 'mail/order', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(22, 'mail_order_alert', 'catalog/model/checkout/order/addOrderHistory/before', 'mail/order/alert', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(23, 'statistics_review_add', 'catalog/model/catalog/review/addReview/after', 'event/statistics/addReview', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(24, 'statistics_return_add', 'catalog/model/account/return/addReturn/after', 'event/statistics/addReturn', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(25, 'statistics_order_history', 'catalog/model/checkout/order/addOrderHistory/after', 'event/statistics/addOrderHistory', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(26, 'admin_mail_affiliate_approve', 'admin/model/customer/customer_approval/approveAffiliate/after', 'mail/affiliate/approve', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(27, 'admin_mail_affiliate_deny', 'admin/model/customer/customer_approval/denyAffiliate/after', 'mail/affiliate/deny', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(28, 'admin_mail_customer_approve', 'admin/model/customer/customer_approval/approveCustomer/after', 'mail/customer/approve', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(29, 'admin_mail_customer_deny', 'admin/model/customer/customer_approval/denyCustomer/after', 'mail/customer/deny', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(30, 'admin_mail_reward', 'admin/model/customer/customer/addReward/after', 'mail/reward', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(31, 'admin_mail_transaction', 'admin/model/customer/customer/addTransaction/after', 'mail/transaction', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(32, 'admin_mail_return', 'admin/model/sale/return/addReturn/after', 'mail/return', 1);
INSERT INTO `oc_event` (`event_id`, `code`, `trigger`, `action`, `status`) VALUES
(33, 'admin_mail_forgotten', 'admin/model/user/user/editCode/after', 'mail/forgotten', 1);
