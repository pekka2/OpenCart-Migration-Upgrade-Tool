<?php
// Heading
$_['heading_title']         = 'Päivitä tietokanta';

// Text
$_['text_intro_1']          = 'Tämä ohjelma on tarkoitettu ainostaan OpenCart versioiden 1.4.7 - 2.0.3.1 päivittämiseen versioon 2.x';
$_['text_intro_2']          = 'Seuraava päivitys prosessi tehdään kolmessa vaiheessa:<ol><li>Tarkistetaan tietokannan taulut, lisätään puuttuvat taulut ja päivitetään muuttunut taulujen rakenne.</li><li>Muokkaa config.php tiedostot (etu- &amp; admin sivulla)</li><li>Muokkaa kuvatiedostojen polut</li></ol>';
$_['text_intro_3']          = 'Tässä muuntajassa voit käyttää myös <b>simulointi</b> eli päivityksen esikatselutilaa.';
$_['text_upgrade_info']     = 'Tietokanta info';
$_['text_update_theme']     = 'Päivitä teema nyt';
$_['text_skip_theme']       = 'Ohita teema';
$_['text_column']           = 'Lisää sarakkeet';
$_['text_column_info']      = 'Lisää uudet sarakkeet';
$_['text_data_info']        = 'Lisää data tietokannan uusiin sarakkeisiin';
$_['text_collate']          = 'Vaihda tietokannan aakkosjärjestys';
$_['text_module_info']      = 'Siirrä moduulit uusiin tauluihin';
$_['text_setting_info']     = 'Lisää uudet asetukset tietokantaan';
$_['text_collate_info']     = 'Vaihda seuraavaksi tietokannan aakkosjärjestys';
$_['text_version']          = 'Sinun kaupan tietokantaversioksi on tunnistettu %s.';
$_['text_table_info']       = 'Aloita lisäämällä uudet taulut';
$_['text_update_config']    = 'Nyt voit myös päivittää  <b>config.php</b> tiedostoja, jos sitä ei ole vielä tehty';

// Entry
$_['entry_up_1564']          = 'Päivitä tietokanta versioon 1.5.6.4';
$_['entry_up_201_202']       = 'Päivitä tietokanta versioon 2.0.1 - 2.0.2.x';
$_['entry_up_2030']          = 'Päivitä tietokanta  versioon 2.0.3.1';
$_['entry_migration_module'] = 'Siirrä moduulit uudeastaan (tyhjentää ensin taulut `layout_module` ja `module`)';
$_['entry_up_2100']          = 'Päivitä tietokanta  versioon 2.1.0.0';

// Header
$_['header_step_1']          = 'Tietokantaan on lisätty uudet taulut onnistuneesti';
$_['header_step_column']     = 'Tietokantaan on lisätty uudet sarakkeet onnistuneesti';
$_['header_step_collate']    = 'Tietokanta aakkosjärjestys päivitetty onnistuneesti';
$_['header_step_module']     = 'Moduulit on siirretty uusiin tauluihin onnistuneesti';
// help
$_['help_ops']               = 'Näytä päivityksen eteneminen <small>(näyttää kaikki tietokantaan tehdyt muutokset)</small>';
$_['help_simulate']          = 'Testi päivitys <small>(toiminnot ainoastaan testitilassa)</small>';
$_['help_usage']             = '<b>Kuinka käyttää tätä työkalua? </b><ol type="1"><li>Jollei jo ole tehty, lataa OpenCart v.2 paketti <a href="http://www.opencart.com" target="_blank">OpenCartin</a> sivulta</li><li> Pura paketin paikallisesti </li><li>Tämä laajennus on  sijoitettu sinun asennetun kaupan <b>converter</b> kansioon (../converter))</li><li>Nyt sinulla on kaksi  (2) vaihtoehtoa:<ol type="I"><li> Siirtää kaikki kansiot ja tiedostot OpenCart v.2 paketista <b>asennettuun kauppaan</b></li><li><b>Luo uusi hakemisto</b> ja kopioi kaikki kansiot ja tiedostot Opencart v.2 paketista siihen.</li></ol></li><li>Jos olet valinnut menetelmän II, kopioi <b>image</b> hakemisto ja <b>2 config.php</b> tiedostoa vanhasta kaupasta</li><li><b>Älä  käytä OpenCart 2.x paketin asennusohjelmaa !</b></li><li>  Aseta vaihtoehdot yllä ja klikkaa  <b>Jatka</b></li><li>Jos olet tehnyt tämän päivityksen, älä unohda poistaa tätä skriptiä.</li></ol>';

// Msg
$_['msg_address_json']          = 'Muokattu <b>address</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_affiliate_json']        = 'Muokattu <b>affiliate_activity</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_cat_path']              = 'Lisätty <b>%s</b> entry/ies';
$_['msg_change_column']         = 'saraketta <b>%s</b> on muokattu onnistuneesti taulussa <b>%s</b>';
$_['msg_change_counter']        = 'MUOKATTU yhteensä <b>%s</b> sarakkeen rakennetta onnistuneesti';
$_['msg_col_counter']           = 'Lisätty yhteensä <b>%s</b> uutta saraketta onnistuneesti';
$_['msg_column']                = 'Sarake <b>%s</b> on Lisätty onnistuneesti tauluun <b>%s</b>';
$_['msg_config']                = 'Kokoonpanoasetus <b>%s</b> on Lisätty onnistunesti tauluun <b>%s</b>';
$_['msg_config_delete']         = 'Kokoonpanoasetus <b>%s</b> on Poistettu onnistuneesti taulusta <b>%s</b>';
$_['msg_converter_setting']     = '<b>Seuraavaksi muunnetaan vanhojen OpenCart versioiden <em>setting</em> taulu yhteensopivaksi:</b>';
$_['msg_customer_json']          = 'Muokattu <b>customer</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_customer_activity_json'] = 'Muokattu <b>customer_activity</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_del_column']            = 'Poistettu yhteensä <b>%s</b> sarake(tta) onnistuneesti';
$_['msg_delete']                = 'Taulu <b>%s</b> on Poistettu onnistuneesti';
$_['msg_delete_column']         = 'Sarake <b>%s</b> on Poistettu onnistuneesti taulusta <b>%s</b>';
$_['msg_delete_setting']        = 'Poistettu <b>%s</b> asetus(ta) taulusta <b>%s%s</b> onnistuneesti';
$_['msg_delete_table']          = 'Poistettu yhteensä <b>%s</b> taulu(a) onnistuneesti';
$_['msg_end_converter_setting'] = '<b>Vanhan OpenCart version <em>setting</em> taulun muuntaminen suoritettu onnistuneesti!</b>';
$_['msg_json_data']             = 'Tietokannan data on muutettu json-formaattin onnistuneesti';
$_['msg_module_json']           = 'Muokattu <b>module</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_new_data']              = 'uusi data lisätty';
$_['msg_new_setting']           = 'Lisätty yhteensä <b>%s</b> uutta asetus(ta) <b>%s%s</b> tauluun';
$_['msg_order_json']            = 'Muokattu <b>order</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_setting_json']          = 'Muokattu <b>setting</b>-taulussa yhteensä <b>%s</b> rivin json-data onnistuneesti';
$_['msg_table']                 = 'Taulu <b>%s</b> on Lisätty onnistuneesti tietokantaan';
$_['msg_table_count']           = 'Lisätty yhteensä <b>%s</b> taulua onnistuneesti';
$_['msg_collate_count']         = 'Muutettu yhteensä <b>%s</b> taulun aakosjärjestystä onnistuneesti';
$_['msg_column_collate_count']  = 'Päivitetty yhteensä <b>%s</b> sarakkeen aakosjärjestystä onnistuneesti';
$_['msg_text']                  = 'Taulussa <b>%s</b> - %s';
$_['msg_truncate']              = 'Taulu <b>%s</b> on tyhjennetty!';
$_['msg_upgrade_to_version']    = 'Tietokannan taulut on lisätty version <b>%s</b> -%s tasolle.';
$_['msg_user_group_json']       = 'Muokattu <b>user_group</b>-taulun permission-sarake onnistuneesti';
?>
