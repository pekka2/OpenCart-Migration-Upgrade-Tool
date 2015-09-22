DELETE FROM `oc_setting` WHERE `key` like '%module%';
DELETE FROM `oc_setting` WHERE `key` like '%openbay%';
DELETE FROM `oc_setting` WHERE `group` like '%bank_transfer%';
DELETE FROM `oc_extension` WHERE `type` = 'payment' AND `code` = 'bank_transfer';
