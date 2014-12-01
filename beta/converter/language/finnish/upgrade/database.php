<?php
// Heading
$_['heading_title']             = 'Päivitä tietokanta';
// Text
$_['text_error']		= 'Virhe';
$_['text_exa_store_path']       = '<br><span class="script-filename">Vihje: Tämän tiedoston polku sinun palvelimella on: <em>%s</em>.</span>';
$_['text_intro_1']		= 'Tämä ohjelma on tarkoitettu  OpenCart versioiden 1.4.7 - 2.0.0.0 päivittämiseen versioon 2.x';
$_['text_intro_2']		= 'Seuraava päivitys prosessi tehdään kolmessa vaiheessa:<ol><li>Tarkistetaan tietokannan taulut, lisätään puuttuvat taulut ja päivitetään muuttunut taulujen rakenne.</li><li>Muokkaa config.php tiedostot (etu- &amp; admin sivulla)</li><li>Muokkaa kuvatiedostojen polut</li></ol>';
$_['text_intro_3']		= 'This updater can be operated in a <b>simulation</b> mode also.<br />Enable this option and no real operations are made.';
$_['text_off']			= 'POIS PÄÄLTÄ';
$_['text_on']			= 'PÄÄLLÄ';
$_['text_simulation']	        = 'Testitila on ';
$_['text_step_1_3']		= 'Vaihe 1 / 3';
$_['text_step_2_3']		= 'Vaihe 2 / 3';
$_['text_to_my_store']   	= 'Sinun kauppaan';
$_['text_toggle_help']	        = 'Näytä/Piilota Ohje';
$_['text_update_config']        = 'Nyt voit myös päivittää  <b>config.php</b> tiedostoja, jos sitä ei ole vielä tehty';
$_['text_upgrade_info']         = 'Päivitä tietokanta';
// btn
$_['btn_config']		= 'Päivitä asetus tiedostot';
$_['button_database']           = 'Päivitä tietokanta';
$_['btn_start']			= 'Aloita päivitys';
// header
$_['header_step_2']		= 'Tietokanta on päivitetty onnistuneesti viimeisimpään versioon';
// entry
$_['entry_adminDir']	        = 'Admin tiedostojen hakemiston nimi';
$_['entry_imageDir']		= 'Kuvahakemiston nimi';
$_['entry_oldDir']		= 'Vanhan asennuksen polku';
$_['entry_perms']		= 'Sinun palvelimen hakemistojen kirjoitusoikeus';
// help
$_['help_adminDir']		= 'Määritä kanion nimi, jos se ei ole Admin';
$_['help_oldDir']		= 'Vanhan asennuksen kiinteä polku - määritellään vain, jos menetelmä II valitaan (katso ohjetta alla)';
$_['help_ops']			= 'Näytä päivityksen eteneminen <small>(näyttää kaikki tietokantaan tehdyt muutokset)</small>';
$_['help_simulate']		= 'Testi päivitys <small>(toiminnot ainoastaan testitilassa)</small>';
$_['help_usage']		= '<b>Kuinka käyttää tätä työkalua? </b><ol type="1"><li>Jollei jo ole tehty, lataa OpenCart v.2 paketti <a href="http://www.opencart.com" target="_blank">OpenCartin</a> sivulta</li><li> Pura paketin paikallisesti </li><li>Tämä skripti on  sijoitettu sinun asennetun kaupan <b>converter</b> kansioon (../converter))</li><li>Nyt sinulla on kaksi  (2) vaihtoehtoa:<ol type="I"><li> Siirtää kaikki kansiot ja tiedostot OpenCart v.2 paketista <b>asennettuun kauppaan</b></li><li><b>Luo uusi hakemisto</b> ja kopioi kaikki kansiot ja tiedostot Opencart v.2 paketista siihen.</li></ol></li><li>Jos olet valinnut menetelmän II, kopioi <b>image</b> hakemisto ja <b>2 config.php</b> tiedostoa vanhasta kaupasta</li><li><b>Älä  käytä OpenCart 2.x paketin asennusohjelmaa !</b></li><li>  Aseta vaihtoehdot yllä ja klikkaa  <b>Jatka</b></li><li>Jos olet tehnyt tämän päivityksen, älä unohda poistaa tätä skriptiä.</li></ol>';
// msg
$_['msg_cat_path']		= 'Lisätty <b>%s</b> entry/ies';
$_['msg_change_column']		= 'saraketta <b>%s</b> on muokattu onnistuneesti taulussa <b>%s</b>';
$_['msg_change_counter']	= 'MUOKATTU yhteensä <b>%s</b> sarakkeen rakennetta onnistuneesti';
$_['msg_col_counter']	        = 'Lisätty yhteensä <b>%s</b> uutta saraketta onnistuneesti';
$_['msg_column']		= 'Sarake <b>%s</b> on Lisätty onnistuneesti tauluun <b>%s</b>';
$_['msg_config']		= 'Kokoonpanoasetus <b>%s</b> on Lisätty onnistunesti tauluun <b>%s</b>';
$_['msg_config_delete']	        = 'Kokoonpanoasetus <b>%s</b> on Poistettu onnistuneesti taulusta <b>%s</b>';
$_['msg_converter_setting']     = '<b>Seuraavaksi muunnetaan vanhojen OpenCart versioiden <em>setting</em> taulu yhteensopivaksi:</b>';
$_['msg_del_column']	        = 'Poistettu yhteensä <b>%s</b> sarake(tta) onnistuneesti';
$_['msg_delete']		= 'Taulu <b>%s</b> on Poistettu onnistuneesti';
$_['msg_delete_column']		= 'Sarake <b>%s</b> on Poistettu onnistuneesti taulusta <b>%s</b>';
$_['msg_delete_setting']        = 'Poistettu <b>%s</b> asetus(ta) taulusta <b>%s%s</b> onnistuneesti';
$_['msg_delete_table']          = 'Poistettu yhteensä <b>%s</b> taulua(a) onnistuneesti';
$_['msg_end_converter_setting'] = '<b>Vanhan OpenCart version <em>setting</em> taulun muuntaminen suoritettu onnistuneesti !</b>';
$_['msg_new_data']		= 'new data added';
$_['msg_new_setting']	        = 'Lisätty yhteensä <b>%s</b> uutta asetus(ta) <b>%s%s</b> tauluun';
$_['msg_table']			= 'Taulu <b>%s</b> on Lisätty onnistuneesti tietokantaan';
$_['msg_table_count']	= 'Lisätty yhteensä <b>%s</b> taulua onnistuneesti';
$_['msg_table_engine']          = 'Taulun <b>%s</b> tietokanta-moottoriksi on vaihdettu <em>MyISAM</em>';
$_['msg_table_engine_checked']  = 'Taulun <b>%s</b> tietokanta-moottori on tarkistettu';
$_['msg_text']			= 'Taulussa <b>%s</b> - %s';
$_['msg_upgrade_to_version']	= 'Tietokannan taulut on lisätty version <b>%s</b> - %s tasolle.';
?>
