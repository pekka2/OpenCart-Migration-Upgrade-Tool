<?php
// Heading
$_['heading_title']             = 'Opgradering af Database';
// Text
$_['text_intro_1']		= 'Dette program er beregnet til OpenCart versioner 1.4.7-2.0.0.0 for at opdatere til version 2.x';
$_['text_intro_2']		= 'The following update process is made in 3 steps:<ol><li>Check database tables and add missing tables, convert required fields</li><li>Convert existing configuration files (front- &amp; backend)</li><li>Convert image paths</li></ol>';
$_['text_intro_3']		= 'This updater can be operated in a <b>simulation</b> mode also.<br />Enable this option and no real operations are made.';
$_['text_toggle_help']     	= 'Vise/skjule hjælp';
$_['text_upgrade_info']         = 'Opgradere Info';
$_['text_update_theme']         = 'Opdater tema nu';
$_['text_skip_theme']           = 'Spring tema';
$_['text_update_config']        = 'Now you can also update the <b>config.php</b> files if not already done';
$_['text_version']          = 'Your store database structure have been identified %s.';
// Entry
$_['entry_up_1564']		     = 'Upgrade database to version 1.5.6.4';
$_['entry_up_201_202']		= 'Upgrade database to version 2.0.1 - 2.0.2.0';
$_['entry_up_2030']		= 'Upgrade database to  version 2.0.3.1';
$_['entry_migration_module'] = 'Move modules again (truncate first tables `layout_module` ja `module`)';
$_['entry_up_2100']		     = 'Upgrade database to version 2.1.0.0';
// help
$_['help_ops']			= 'Display Operations <small>(display all database operations)</small>';
$_['help_simulate']		= 'Simulate Converting <small>(simulating operations only)</small>';
$_['help_usage']		= '<b>How to use this tool?</b><ol type="1"><li>If not already done, download the OpenCart v.2 package from <a href="http://www.opencart.com" target="_blank">OpenCart</a></li><li>Unzip that package locally</li><li>This script has to be placed in the subfolder <b>converter</b> folder of your shopinstallation (../converter))</li><li>Now you have two (2) options:<ol type="I"><li>Transfer all folders and files from the OpenCart v.2 package <b>into the installed shop</b></li><li><b>Create a new directory</b> and copy all folders and files from the OpenCart v.2 package into</li></ol></li><li>If you have chosen method II copy the folder <b>image</b> and the <b>2 config.php</b> from the old shop</li><li><b>Never use the installer from the OpenCart 2.x package!</b></li><li>Set your options above and click on <b>Continue</b></li><li>If you are finished with this upgrade, do not to forget to delete this script</li></ol>';
// Msg
$_['msg_cat_path']		= 'added <b>%s</b> entry/ies';
$_['msg_change_column']		= 'Column <b>%s</b> is successfully changed to table <b>%s</b>';
$_['msg_change_counter']	= 'CHANGE total <b>%s</b> COLUMN(S) STRUCTURE SUCCESSFULLY';
$_['msg_col_counter']	        = 'ADDED total <b>%s</b> NEW COLUMNS SUCCESSFULLY';
$_['msg_column']		= 'Column <b>%s</b> successfully added to table <b>%s</b>';
$_['msg_config']		= 'Config setting <b>%s</b> successfully added to table <b>%s</b>';
$_['msg_config_delete']  	= 'Config setting <b>%s</b> successfully deleted from table <b>%s</b>';
$_['msg_converter_setting']     = '<b>Subsequently converted old OpenCart versions of <em>setting</em> to be compatible with a flat:</b>';
$_['msg_del_column']	        = 'Delete total <b>%d</b> column(s) successfully';
$_['msg_delete']		= 'Table <b>%s</b> successfully deleted';
$_['msg_delete_column']		= 'Column <b>%s</b> is successfully deleted to table <b>%s</b>';
$_['msg_delete_setting']        = 'DELETED total <b>%s</b> setting(s) from <b>%s%s</b> table';
$_['msg_delete_table']	        = 'DELETED total <b>%d</b> TABLE(S) SUCCESSFULLY';
$_['msg_end_converter_setting'] = '<b>Old OpenCart version of the <em>setting</em> table conversion completed successfully !</b>';
$_['msg_new_data']		= 'nye data tilføjet';
$_['msg_new_setting']	        = 'ADDED total <b>%d</b> new setting(s) to <b>%s</b> table';
$_['msg_table']			= 'Table <b>%s</b> successfully added to database';
$_['msg_table_count']	        = 'ADDED total <b>%s</b> TABLES SUCCESSFULLY';
$_['msg_table_engine']		= 'In Table <b>%s</b> is table engine changed <em>MyISAM</em>';
$_['msg_table_engine_checked']	= 'Table Engine in table <b>%s</b> is checked';
$_['msg_text']			= 'Tabel <b>%s</b>-%s';
$_['msg_upgrade_to_version']	= 'Database Tables is added to version <b>%s</b> - %s level.';
?>
