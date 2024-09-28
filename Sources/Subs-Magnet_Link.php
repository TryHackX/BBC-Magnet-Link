<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

if (!defined('SMF'))
	die('Hacking attempt...');

function Magnet_admin_areas(&$admin_areas)
{
	global $txt;
	
	$admin_areas['config']['areas']['modsettings']['subsections']['magnet'] = array($txt['magnet_mod_name']);
}

function Magnet_modify_settings(&$subActions)
{
    $subActions['magnet'] = 'Magnet_ModifySettings';
}

function Magnet_ModifySettings()
{
    global $context, $txt, $scripturl, $modSettings;

    // Załaduj język, jeśli potrzebne
    loadLanguage('bbc_magnet_link');

    // Załaduj konfigurację dla formularza
	$config_vars = array(
		// Ustawienia ogólne
		$txt['magnet_general_settings'],
		array('check', 'magnet_guest', 'subtext' => $txt['magnet_guest_sup'], 'value' => isset($modSettings['magnet_guest']) ? $modSettings['magnet_guest'] : 0),

		// Oddzielenie sekcji
		'<br><br>',

		// Ustawienia scraper'a
		$txt['magnet_scraper_settings'],
		array('check', 'magnet_scraper_enabled', 'subtext' => $txt['magnet_scraper_enabled_sup'], 'value' => isset($modSettings['magnet_scraper_enabled']) ? $modSettings['magnet_scraper_enabled'] : 1),
		array('check', 'magnet_only_http', 'subtext' => $txt['magnet_only_http_sup'], 'value' => isset($modSettings['magnet_only_http']) ? $modSettings['magnet_only_http'] : 0),
		array('check', 'magnet_check_all', 'subtext' => $txt['magnet_check_all_sup'], 'value' => isset($modSettings['magnet_check_all']) ? $modSettings['magnet_check_all'] : 0),
		array('select', 'magnet_check_all_display_type', $txt['magnet_check_all_options'], 'subtext' => $txt['magnet_check_all_display_type_sup'], 'value' => isset($modSettings['magnet_check_all_display_type']) ? $modSettings['magnet_check_all_display_type'] : 0),
		array('check', 'magnet_announce', 'subtext' => $txt['magnet_announce_sup'], 'value' => isset($modSettings['magnet_announce']) ? $modSettings['magnet_announce'] : 0),
		array('int', 'magnet_max_scrape_time', 'subtext' => $txt['magnet_max_scrape_time_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_max_scrape_time']) ? $modSettings['magnet_max_scrape_time'] : 0),
		array('int', 'magnet_max_trackers_scrape', 'subtext' => $txt['magnet_max_trackers_scrape_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_max_trackers_scrape']) ? $modSettings['magnet_max_trackers_scrape'] : 0),

		// Oddzielenie sekcji
		'<br><br>',

		// Ustawienia załączników
		$txt['magnet_attachments_settings'],
		array('check', 'magnet_support_for_attachments', 'subtext' => $txt['magnet_support_for_attachments_sup'], 'value' => isset($modSettings['magnet_support_for_attachments']) ? $modSettings['magnet_support_for_attachments'] : 1),
		array('select', 'magnet_attachments_display_type', $txt['magnet_support_attachments_options'], 'subtext' => $txt['magnet_attachments_display_type_sup'], 'value' => isset($modSettings['magnet_attachments_display_type']) ? $modSettings['magnet_attachments_display_type'] : 0),
		
		// Oddzielenie sekcji
		'<br><br>',

		// Ustawienia kliknięć
		$txt['magnet_clicks_settings'],
		array('check', 'magnet_support_for_clicks', 'subtext' => $txt['magnet_support_for_clicks_sup'], 'value' => isset($modSettings['magnet_support_for_clicks']) ? $modSettings['magnet_support_for_clicks'] : 1),
		array('check', 'magnet_clicks_bans_support', 'subtext' => $txt['magnet_clicks_bans_support_sup'], 'value' => isset($modSettings['magnet_clicks_bans_support']) ? $modSettings['magnet_clicks_bans_support'] : 1),
		array('int', 'magnet_ban_time', 'subtext' => $txt['magnet_ban_time_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_ban_time']) ? $modSettings['magnet_ban_time'] : 20),
		array('int', 'magnet_ban_interval', 'subtext' => $txt['magnet_ban_interval_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_ban_interval']) ? $modSettings['magnet_ban_interval'] : 10),
		array('int', 'magnet_ban_interval_count', 'subtext' => $txt['magnet_ban_interval_count_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_ban_interval_count']) ? $modSettings['magnet_ban_interval_count'] : 100),
		array('int', 'magnet_self_interval', 'subtext' => $txt['magnet_self_interval_sup'], 'size' => 4, 'value' => isset($modSettings['magnet_self_interval']) ? $modSettings['magnet_self_interval'] : 1),
	);

    // Obsłuż zapis ustawień, jeśli formularz został wysłany
    if (isset($_GET['save']))
    {
        checkSession();
        saveDBSettings($config_vars);
		clean_cache();
        redirectexit('action=admin;area=modsettings;sa=magnet');
    }

    // Przygotuj dane dla szablonu
	$context['page_title'] = $txt['magnet_mod_name'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;sa=magnet;save';
    $context['settings_title'] = $txt['magnet_mod_name'];
	$context[$context['admin_menu_name']]['tab_data']['tabs']['magnet'] = array('description' => $txt['magnet_mod_name']);
   
    prepareDBSettingContext($config_vars);
}

function Magnet_bbc_theme()
{
	global $context, $txt, $settings, $modSettings;
	
	loadLanguage('bbc_magnet_link');
	
	$context['html_headers'] .= '
	<link rel="stylesheet" type="text/css" href="'. $settings['default_theme_url'] .'/css/bbc_magnet_link.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="'. $settings['default_theme_url'] .'/scripts/bbc_magnet_link.js"></script>';
}

function Magnet_bbc_button(&$buttons)
{
	global $txt;

	loadLanguage('bbc_magnet_link');

	$buttons[count($buttons) - 1][] = array(
		'image' => 'Magnet_Icon',
		'code' => 'magnet',
		'before' => '[magnet]',
		'after' => '[/magnet]',
		'description' => $txt['magnet_mod_description'],
	);
}

function Magnet_bbc_codes(&$codes)
{
	foreach ($codes as $tag => $dump)
		if ($dump['tag'] == 'magnet')
			unset($codes[$tag]);

	$codes[] = array(
		'tag' => 'magnet',
		'type' => 'unparsed_equals_content',
		'validate' => function (&$tag, &$data, $disabled)
		{
			if (!is_numeric($data[1]))
				$tag['content'] = bbc_magnet_template($data[0], 'preview');
			else $tag['content'] = bbc_magnet_template($data[0], $data[1]);
		},
		'disabled_content' => '($1)',
	);
}

function Magnet_Link_Preparse($message, $cache_msg_id)
{
    if (empty($message))
		return $message;

	$message = preg_replace_callback(
		'~(\[magnet\])([^\[]*)\[\/magnet\]~i',
		function ($matches) use ($cache_msg_id) {
			if (!is_numeric($cache_msg_id))
				$cache_msg_id = 'preview';
			return '[magnet='. $cache_msg_id .']'. $matches[2] .'[/magnet]';
		},
		$message
	);
	$message = preg_replace_callback(
		'~\[magnet=(https?\:\/\/(www\.)?|www\.).*\.msg(\d*)(#|;).*\]([^\[]*)\[\/magnet\]~i',
		function ($matches) {
			return '[magnet='. $matches[3] .']'. $matches[5] .'[/magnet]';
		},
		$message
	);

	return $message;
}

function Add_Attachment_Downloaded_Count($cache_msg_id)
{
	global $txt, $modSettings, $smcFunc;

	if ($cache_msg_id != 'preview')
	{
		if (!empty($modSettings['magnet_support_for_attachments']))
		{
			if (allowedTo('view_attachments') || !empty($modSettings['magnet_guest']))
			{
				$request = $smcFunc['db_query']('', '
					SELECT
						a.id_attach, a.id_msg, a.fileext, a.downloads
					FROM {db_prefix}attachments AS a
					WHERE a.id_msg = {int:message_id}
						AND a.fileext = {string:torrent_ext}
					ORDER BY a.downloads',
					array(
						'message_id' => $cache_msg_id,
						'torrent_ext' => 'torrent',
					)
				);
				$attach_temp_data['count'] = 0;	$attach_temp_data['sum'] = 0;	$attach_temp_data['max_downloads'] = 0;
				while ($row = $smcFunc['db_fetch_assoc']($request))
				{
					++$attach_temp_data['count'];
					$attach_temp_data['sum'] = $attach_temp_data['sum'] + $row['downloads'];
					if ($row['downloads'] > $attach_temp_data['max_downloads'])
						$attach_temp_data['max_downloads']  = $row['downloads'];
					if (!isset($attach_temp_data['min_downloads']))
						$attach_temp_data['min_downloads'] = $attach_temp_data['max_downloads'];
					if ($row['downloads'] < $attach_temp_data['min_downloads'])
						$attach_temp_data['min_downloads']  = $row['downloads'];
				}
				$smcFunc['db_free_result']($request);
				
				if (isset($attach_temp_data['min_downloads']))
				{
					if ($attach_temp_data['max_downloads'] > 0)
					{
						if ($attach_temp_data['count'] > 1)
							$temp_attach_txt = $txt['magnet_attachments'];
						else $temp_attach_txt = $txt['magnet_attachment'];
							
						if (empty($modSettings['magnet_attachments_display_type']))
						{
							if ($attach_temp_data['count'] == 1)
								return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. $attach_temp_data['max_downloads'] .'</span>)</div>';
							elseif ($attach_temp_data['count'] > 1)
								return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. $attach_temp_data['min_downloads'] .' - '. $attach_temp_data['max_downloads'] .'</span>)</div>';
						}
						elseif ($modSettings['magnet_attachments_display_type'] == 1)
							return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. $attach_temp_data['sum'] .'</span>)</div>';
						elseif ($modSettings['magnet_attachments_display_type'] == 2)
							return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. $attach_temp_data['max_downloads'] .'</span>)</div>';
						elseif ($modSettings['magnet_attachments_display_type'] == 3)
							return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. $attach_temp_data['min_downloads'] .'</span>)</div>';
						elseif ($modSettings['magnet_attachments_display_type'] == 4)
							return '<div class="magnet_return">('. $temp_attach_txt .' <span style="color:#0080ff;">'. Round($attach_temp_data['sum']/$attach_temp_data['count'], 2) .'</span>)</div>';
					}
				}
			}
		}
	}
}

function bbc_magnet_template($input_magnet, $cache_msg_id)
{
    global $txt, $modSettings, $scripturl, $user_info, $smcFunc, $db_prefix;
    
    loadLanguage('bbc_magnet_link');
    
    if (!empty($input_magnet)) {
        if (strpos($input_magnet, 'magnet:') === 0) {

            $input_magnet = html_entity_decode($input_magnet, ENT_QUOTES, "UTF-8");
            $input_magnet = urldecode($input_magnet);

            $input_magnet = str_replace(
                array("&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&ntilde;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;", "&Ntilde;"),
                array("á", "é", "í", "ó", "ú", "ñ", "Á", "É", "Í", "Ó", "Ú", "Ñ"),
                $input_magnet
            );

            preg_match('#magnet:\?xt=urn:btih:(?<hash>[^&]+)&dn=(?<filename>[^&]+)&tr=(?<trackers>.*)#', $input_magnet, $magnet_parts);

            if (!empty($magnet_parts['trackers'])) {

                $magnet_parts['trackers'] = explode('&', str_replace('tr=', '', $magnet_parts['trackers']));

                if (!empty($modSettings['magnet_only_http'])) {
                    $magnet_parts['trackers'] = preg_grep("(^http://|^https://)", $magnet_parts['trackers']);
                }

                $magnet_parts['trackers'] = array_values($magnet_parts['trackers']);

                if (!empty($magnet_parts['trackers'][0])) {

                    if (!empty($magnet_parts['hash']) && !empty($magnet_parts['filename'])) {
                        $magnet_temp_link = ''; $magnet_button = '';

						$mag_click_result['Normal'] = '';
						
						if (!empty($modSettings['magnet_support_for_clicks'])) {
							$sql = "SELECT click_count
									FROM {db_prefix}Mag_Hashes 
									WHERE link_hash = {string:magnet_hash} 
									LIMIT 1";
							$request = $smcFunc['db_query']('', $sql, 
								array(
									'magnet_hash' => $magnet_parts['hash'],
								)
							);
							$mag_result = $smcFunc['db_fetch_assoc']($request);
							$smcFunc['db_free_result']($request);

							if (isset($mag_result) && $mag_result['click_count'] > 0) { 
								$mag_click_result['Normal'] = '<div class="magnet_return magnet_click">' . $txt['magnet_clicked'] . ' <span style="color:#0080ff;">' . $mag_result['click_count'] . '</span></div>';
							}
						}

                        if ($user_info['is_guest'] && empty($modSettings['magnet_guest'])) {
                            $magnet_temp_link = $txt['magnet_not_allowed'] . '&nbsp;<a href="' . $scripturl . '?action=register">' . $txt['magnet_not_allowed_reg'] . '</a>&nbsp;' . $txt['magnet_not_allowed_or'] . '&nbsp;<a href="' . $scripturl . '?action=login">' . $txt['magnet_not_allowed_log'] . '</a>';
                        } elseif (!empty($modSettings['magnet_scraper_enabled'])) {
                            $tmp_uniqid = uniqid();
                            $magnet_temp_link = '<div class="magnet_text_body"><a href="' . $input_magnet . '" title="' . $magnet_parts['filename'] . '" target="_self" id="magnet_hook" data-temp_id="' . $tmp_uniqid . '" onclick="Magnet_Link_Count(`'. $magnet_parts['hash'] .'`, `' . $cache_msg_id . '`)">' . $magnet_parts['filename'] . '</a>' . Add_Attachment_Downloaded_Count($cache_msg_id) . '</div>';
                            $magnet_button = '<button class="magnet-button-glow" id="magnet_hook" type="button" data-temp_id="' . $tmp_uniqid . '">Update</button>';
                        } else {
                            $magnet_temp_link = '<div class="magnet_text_body"><a href="' . $input_magnet . '" title="' . $magnet_parts['filename'] . '" target="_self" onclick="Magnet_Link_Count(`'. $magnet_parts['hash'] .'`, `' . $cache_msg_id . '`)">' . $magnet_parts['filename'] . '</a>' . $mag_click_result['Normal'] . Add_Attachment_Downloaded_Count($cache_msg_id) . '</div>';
                        }

                        $data = $magnet_parts['filename'] . '"><strong class="magnet_pos">' . $magnet_temp_link . '</strong>' . $magnet_button;
                    } else {
                        $data = $txt['magnet_link_error_bad_magnet_link'] . '"><strong>' . $txt['magnet_link_error_bad_magnet_link'] . '.</strong>';
                    }
                } else {
                    $data = $txt['magnet_link_error_no_http_trackers'] . '"><strong>' . $txt['magnet_link_error_no_http_trackers'] . '.</strong>';
                }
            } else {
                $data = $txt['magnet_link_error_lack_trackers'] . '"><strong>' . $txt['magnet_link_error_lack_trackers'] . '.</strong>';
            }
        } else {
            $data = $txt['magnet_link_error_not_magnet_link'] . '"><strong>' . $txt['magnet_link_error_not_magnet_link'] . '.</strong>';
        }
    } else {
        $data = $txt['magnet_link_error_no_input_data'] . '"><strong>' . $txt['magnet_link_error_no_input_data'] . '.</strong>';
    }

    return '<div class="magnet_body"><img class="magnet_link" title="' . $data . '</div>';
}

?>