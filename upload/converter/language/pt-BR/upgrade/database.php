<?php
// Heading
$_['heading_title']             = 'Atualização do banco de dados';
// Text
$_['text_exa_store_path']       = '<br><span class="script-filename">Ajuda: O caminho para o arquivo no seu servidor é: <em>%s</em>.</span>';
$_['text_intro_1']		= 'Este programa destina-se para OpenCart versões 1.4.7-2.0.0.0 para atualizar para a versão 2.x';
$_['text_intro_2']		= 'O processo de atualização a seguir é feito em 3 etapas: <ol><li>Conferir as tabelas do banco de dados e adicionar as tabelas que faltam, converter os campos obrigatórios</li><li>Converter os arquivos de configuração existentes (front-end &amp; backend)</li><li>Converter caminhos das imagens</li></ol>';
$_['text_intro_3']		= 'Esta atualização pode ser executada em um modo de <b>simulação</b> também.<br />Habilite esta opção e nenhuma alteração será efetuada.';
$_['text_toggle_help']     	= 'Exibir/Esconder Ajuda';
$_['text_upgrade_info']         = 'Info de atualização';
$_['text_update_theme']         = 'Atualizar tema agora';
$_['text_skip_theme']            = 'Ignorar tema';
$_['text_module_info']      = 'Move modules to the new tables';
$_['text_setting_info']     = 'Add new settings to database';
$_['text_collate_info']     = 'Update to the next database collation';
$_['text_table_info']       = 'Start to add new tables';
$_['text_version']          = 'Your store database structure have been identified %s.';
$_['text_update_config']        = 'Agora você pode atualizar os arquivos <b>config.php</b> se ainda não tiverem sido feitos';

// Entry
$_['entry_up_1564']		     = 'Upgrade database to version 1.5.6.4';
$_['entry_up_201_202']		= 'Atualizar o banco de dados para versão 2.0.1-2.0.2.0';
$_['entry_up_2030']		= 'Atualizar o banco de dados para a versão 2.0.3.1';
$_['entry_migration_module'] = 'Move modules again (truncate first tables `layout_module` ja `module`)';
$_['entry_up_2100']		     = 'Upgrade database to version 2.1.0.0';
// help
$_['help_ops']			= 'Exibir as operações <small>(Exibir todas as operações do banco de dados)</small>';
$_['help_simulate']		= 'Simular a conversão <small>(simulando operações apenas)</small>';
$_['help_usage']		= '<b>Como usar este módulo?</b><ol type="1"><li>Se ainda não tiver feito, baixe o OpenCart v.2 de <a href="http://www.opencart.com" target="_blank">OpenCart</a></li><li>Descompacte o pacote localmente</li><li>Este script precisa ser colocado numa subpasta chamada <b>converter</b> dentro da raíz da loja (../converter)</li><li>Agora você tem 2 opções:<ol type="I">Transferir todas as pastas e arquivos do OpenCart v.2 <b> para dentro da loja já instalada</b></li><li><b>Crie uma nova pasta</b> e copie todas as pastas e arquivos do OpenCart v.2 para dentro</li></ol></li><li>Se você tiver escolhido o método II copie a pasta <b>image</b> e os <b>2 config.php</b> da antiga loja</li><li><b>Nunca use o instalador do OpenCart 2.x!</b></li><li>Defina as opções acima e clique em <b>Continuar</b></li><li>Se você já tiver finalizado esta atualização, não esqueça de deletar este script</li></ol>';
// Msg
$_['msg_cat_path']		= 'added <b>%s</b> entry/ies';
$_['msg_change_column']		= 'Coluna <b>%s</b> foi alterada com êxito na tabela <b>%s</b>';
$_['msg_change_counter']	= 'Total de <b>%s</b> ESTRUTURA DE COLUNAS ALTERADAS COM ÊXITO';
$_['msg_col_counter']	        = 'Total de <b>%s</b> NOVAS COLUNAS ADICIONADAS COM ÊXITO';
$_['msg_column']		= 'Coluna <b>%s</b> adicionada com êxito na tabela <b>%s</b>';
$_['msg_config']		= 'Config setting <b>%s</b> successfully added to table <b>%s</b>';
$_['msg_config_delete']  	= 'Config setting <b>%s</b> successfully deleted from table <b>%s</b>';
$_['msg_converter_setting']     = '<b>Subsequently converted old OpenCart versions of <em>setting</em> to be compatible with a flat:</b>';
$_['msg_del_column']	        = 'Delete total <b>%d</b> column(s) successfully';
$_['msg_delete']		= 'Table <b>%s</b> successfully deleted';
$_['msg_delete_column']		= 'Column <b>%s</b> is successfully deleted to table <b>%s</b>';
$_['msg_delete_setting']        = 'DELETED total <b>%s</b> setting(s) from <b>%s%s</b> table';
$_['msg_delete_table']	        = 'DELETED total <b>%d</b> TABLE(S) SUCCESSFULLY';
$_['msg_end_converter_setting'] = '<b>Old OpenCart version of the <em>setting</em> table conversion completed successfully !</b>';
$_['msg_new_data']		= 'new data added';
$_['msg_new_setting']	        = 'ADDED total <b>%d</b> new setting(s) to <b>%s</b> table';
$_['msg_table']			= 'Table <b>%s</b> successfully added to database';
$_['msg_table_count']	        = 'ADDED total <b>%s</b> TABLES SUCCESSFULLY';
$_['msg_table_engine']		= 'In Table <b>%s</b> is table engine changed <em>MyISAM</em>';
$_['msg_table_engine_checked']	= 'Table Engine in table <b>%s</b> is checked';
$_['msg_text']			= 'Table <b>%s</b> - %s';
$_['msg_upgrade_to_version']	= 'Database Tables is added to version <b>%s</b> - %s level.';
?>
