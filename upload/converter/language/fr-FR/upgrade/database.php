<?php
// Heading
$_['heading_title']             = 'Mise à niveau de la base de données';
// Text
$_['text_intro_1']		= 'Ce programme s\'adresse aux versions 1.4.7 - 2.0.0.0 d\'OpenCart pour mettre à jour vers la version 2.x';
$_['text_intro_2']		= 'Le processus de mise à jour suivant est fait en 3 étapes : <ol><li>Vérifie les tables de la base de données et ajoute les tables manquantes, converti les champs requis</li><li>Converti les fichiers des configuration existants (front- &amp; backend)</li><li>Converti les chemins d\'image</li></ol>';
$_['text_intro_3']		= 'Cette mise à jour peut aussi être effectuée dans un mode <b>simulation</b>.<br />Activer cette option et aucune opération réel n\'est faite.';
$_['text_toggle_help']     	= 'Afficher/Cacher l\'aide';
$_['text_upgrade_info']         = 'Informations de mise à jour';
$_['text_update_theme']         = 'Mettre à jour le thème maintenant';
$_['text_skip_theme']           = 'Passer le thème';
$_['text_version']          = 'Your store database structure have been identified %s.';
$_['text_update_config']        = 'Désormais vous pouvez aussi mettre à jour le fichier <b>config.php</b> si ce n\'est pas déjà fait';
// Entry
$_['entry_up_1564']		     = 'Upgrade database to version 1.5.6.4';
$_['entry_up_201_202']		= 'Mise à niveau de la base de données vers la version 2.0.1-2.0.2.0';
$_['entry_up_2030']		= 'Mettre a niveau vers la version 2.0.3.1';
$_['entry_migration_module'] = 'Move modules again (truncate first tables `layout_module` ja `module`)';
$_['entry_up_2100']		     = 'Upgrade database to version 2.1.0.0';
// help
$_['help_ops']			= 'Afficher les opérations <small>(Affiche toutes les opérations de base de données)</small>';
$_['help_simulate']		= 'Simuler la conversion <small>(Simulation d\'opérations uniquement)</small>';
$_['help_usage']		= '<b>Comment utiliser cette outil?</b><ol type="1"><li>Si ce n\'est pas déjà fait, téléchargez le package OpenCart v.2 via <a href="http://www.opencart.com" target="_blank">OpenCart</a></li><li>Décompressez le package en local</li><li>Le script doit être placé dans le sous-dossier <b>converter</b> du dossier de votre magasin (../converter)</li><li>Maintenant vous avez deux (2) options :<ol type="I"><li>Transférez tous les dossiers et fichiers du package OpenCart v.2 <b>vers le magasin installé</b></li><li><b>Créez un nouveau répertoire</b> et copiez tous les dossiers et fichier du package OpenCart v.2 dedans</li></ol></li><li>Si vous avez choisi la deuxième méthode, copiez le dossier <b>image</b> et le fichier <b>config.php</b> de votre ancien magasin</li><li><b>N\'utilisez jamais l\'installer du package OpenCart 2.x!</b></li><li>Définissez vos options ci-dessus et cliquez sur <b>Continuer</b></li><li>Si vous avez fini avec la mise à niveau, n\'oubliez pas de supprimer ce script</li></ol>';
// Msg
$_['msg_cat_path']		= 'Ajouté <b>%s</b> entrée(s)';
$_['msg_change_column']		= 'La colonne <b>%s</b> à été modifié avec succès dans la table <b>%s</b>';
$_['msg_change_counter']	= 'CHANGEMENT total de <b>%s</b> STRUCTURE DE COLONNE(S) avec succès';
$_['msg_col_counter']	        = 'AJOUT total de <b>%s</b> NOUVELLES COLONNES avec succès';
$_['msg_column']		= 'Colonne <b>%s</b> ajouté avec succès dans la table <b>%s</b>';
$_['msg_config']		= 'Paramètre de configuration <b>%s</b> ajouté avec succès dans la table <b>%s</b>';
$_['msg_config_delete']  	= 'Paramètre de configuration <b>%s</b> supprimé avec succès de la table <b>%s</b>';
$_['msg_converter_setting']     = '<b>Subsequently converted old OpenCart versions of <em>setting</em> to be compatible with a flat:</b>';
$_['msg_del_column']	        = 'Suppression d\'un total de <b>%d</b> colonne(s) réalisée avec succès';
$_['msg_delete']		= 'Table <b>%s</b> supprimée avec succès';
$_['msg_delete_column']		= 'La colonne <b>%s</b> à été supprimé avec succès de la table <b>%s</b>';
$_['msg_delete_setting']        = 'SUPPRESSION total de <b>%s</b> paramètre(s) de la table <b>%s%s</b>';
$_['msg_delete_table']	        = 'SUPPRESSION total de <b>%d</b> TABLE(S) avec succès';
$_['msg_end_converter_setting'] = '<b>Conversion de l\'ancienne version de la table <em>paramètre</em> d\'OpenCart réalisé avec succès !</b>';
$_['msg_new_data']		= 'nouvelles données ajoutées';
$_['msg_new_setting']	        = 'AJOUT total de <b>%d</b> nouveau(x) paramètre(s) à la table <b>%s</b>';
$_['msg_table']			= 'Table <b>%s</b> ajoutée avec succès à la base de données';
$_['msg_table_count']	        = 'AJOUT total de <b>%s</b> TABLES avec succès';
$_['msg_table_engine']		= 'Dans la table <b>%s</b>, le moteur de table à été changé en <em>MyISAM</em>';
$_['msg_table_engine_checked']	= 'Moteur de table dans la table <b>%s</b> est coché';
$_['msg_text']			= 'Table <b>%s</b> - %s';
$_['msg_upgrade_to_version']	= 'Les tables de bases de données sont ajoutées à la version <b>%s</b> - %s.';
?>
