<?php
// Heading
$_['heading_title']             = 'Upgrade van Database';
// Text
$_['text_exa_store_path']       = '<br><span class="script-filename">Help: The path to the file to your server is: <em>%s</em>.</span>';
$_['text_intro_1']		= 'Dit programma is bedoeld voor OpenCart versies 1.4.7-2.0.0.0 voor het bijwerken naar versie 2.x';
$_['text_intro_2']		= 'The following update process is made in 3 steps:<ol><li>Check database tables and add missing tables, convert required fields</li><li>Convert existing configuration files (front- &amp; backend)</li><li>Convert image paths</li></ol>';
$_['text_intro_3']		= 'Deze updater kan ook bediend worden in een <b>simulatie</b> mode .<br /> Schakel deze optie inschakelen in en er zullen geen echte bewerkingen worden gemaakt.';
$_['text_toggle_help']     	= 'Toon/Verberg Help';
$_['text_upgrade_info']         = 'Upgrade Informatie';
$_['text_update_theme']         = 'Update thema nu';
$_['text_version']          = 'Your store database structure have been identified %s.';
$_['text_skip_theme']            = 'Overslaan thema';
$_['text_update_config']        = 'Nu kunt u ook de <b>config. php</b> bestanden bijwerken indien dat nog niet is gedaan';
// Entry
$_['entry_up_1564']		     = 'Upgrade database to version 1.5.6.4';
$_['entry_up_201_202']		= 'De database een upgrade uitvoert naar versie 2.0.1-2.0.2.0';
$_['entry_up_2030']		= 'Upgrade van de database naar versie 2.0.3.1';
$_['entry_migration_module'] = 'Move modules again (truncate first tables `layout_module` ja `module`)';
$_['entry_up_2100']		     = 'Upgrade database to version 2.1.0.0';
// help
$_['help_ops']			= 'Weergeven operaties <small>(weergeven alle databasebewerkingen)</small>';
$_['help_simulate']		= 'Simuleren omzetten <small>(simuleren operaties alleen)</small>';
$_['help_usage']		= '<b>How to use this tool?</b><ol type="1"><li>If not already done, download the OpenCart v.2 package from <a href="http://www.opencart.com" target="_blank">OpenCart</a></li><li>Unzip that package locally</li><li>This script has to be placed in the subfolder <b>converter</b> folder of your shopinstallation (../converter))</li><li>Now you have two (2) options:<ol type="I"><li>Transfer all folders and files from the OpenCart v.2 package <b>into the installed shop</b></li><li><b>Create a new directory</b> and copy all folders and files from the OpenCart v.2 package into</li></ol></li><li>If you have chosen method II copy the folder <b>image</b> and the <b>2 config.php</b> from the old shop</li><li><b>Never use the installer from the OpenCart 2.x package!</b></li><li>Set your options above and click on <b>Continue</b></li><li>If you are finished with this upgrade, do not to forget to delete this script</li></ol>';
// Msg
$_['msg_cat_path']		= 'toegevoegde <b>%s</b> post/ies';
$_['msg_change_column']		= 'Kolom <b>%s</b> is is gewijzigd in tabel <b>%s</b>';
$_['msg_change_counter']	= 'Totale <b>%s</b> kolom(en) in de structuur met succes wijzigen';
$_['msg_col_counter']	        = 'Totale <b>%s</b> de nieuwe kolommen succesvol toegevoegd';
$_['msg_column']		= 'Column <b>%s</b> successfully added to table <b>%s</b>';
$_['msg_config']		= 'Config setting <b>%s</b> successfully added to table <b>%s</b>';
$_['msg_config_delete']  	= 'Config setting <b>%s</b> successfully deleted from table <b>%s</b>';
$_['msg_converter_setting']     = '<b>Subsequently converted old OpenCart versions of <em>setting</em> to be compatible with a flat:</b>';
$_['msg_del_column']	        = 'Totale <b>%d</b> kolom (s) met succes verwijderen';
$_['msg_delete']		= 'Tabel <b>%s</b> succesvol verwijderd';
$_['msg_delete_column']		= 'Column <b>%s</b> is successfully deleted to table <b>%s</b>';
$_['msg_delete_setting']        = 'DELETED total <b>%s</b> setting(s) from <b>%s%s</b> table';
$_['msg_delete_table']	        = 'DELETED total <b>%d</b> TABLE(S) SUCCESSFULLY';
$_['msg_end_converter_setting'] = '<b>Oude versie van de <em>instelling</em> OpenCart tabelconversie voltooid!</b>';
$_['msg_new_data']		= 'nieuwe gegevens toegevoegd';
$_['msg_new_setting']	        = 'TOEGEVOEGDE totale <b>%d</b> de nieuwe instelling (s) aan <b>%s</b> tabel';
$_['msg_table']			= 'Table <b>%s</b> successfully added to database';
$_['msg_table_count']	        = 'Totale <b>%s</b> tabellen met succes toegevoegd';
$_['msg_table_engine']		= 'In tabel <b>tabel <em>%s</b> is</em> engine MyISAM gewijzigd';
$_['msg_table_engine_checked']	= 'Tabel Engine in tabel <b>%s</b> wordt gecontroleerd';
$_['msg_text']			= 'Tabel van <b>%s</b> - %s';
$_['msg_upgrade_to_version']	= 'Databasetabellen wordt toegevoegd aan versie <b>%s</b>-%s-niveau.';
?>
