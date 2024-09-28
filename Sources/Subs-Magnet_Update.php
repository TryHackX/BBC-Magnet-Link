<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

	require 'Subs-Magnet_Scraper.php';
	
	$json = file_get_contents('php://input');
	$fetch_POST = json_decode($json, true);

	$input_magnet = $fetch_POST["magnet"];
	$temp_uniqid = $fetch_POST["temp_id"];
	
	$seedsOnly = isset($fetch_POST['SeedsOnly']) ? filter_var($fetch_POST['SeedsOnly'], FILTER_VALIDATE_BOOLEAN) : false;

	$data = '';

	if (!empty($input_magnet)) {

		if (strpos($input_magnet, 'magnet:') === 0) {

			$input_magnet = html_entity_decode($input_magnet, ENT_QUOTES, "UTF-8");
			$input_magnet = urldecode($input_magnet);

			$input_magnet = str_replace(
					array("&aacute;","&eacute;","&iacute;","&oacute;","&uacute;","&ntilde;","&Aacute;","&Eacute;","&Iacute;","&Oacute;","&Uacute;","&Ntilde;"),
						array("á","é","í","ó","ú","ń","Á","É","Í","Ó","Ú","Ń"), $input_magnet);

			preg_match('#magnet:\?xt=urn:btih:(?<hash>[^&]+)&dn=(?<filename>[^&]+)&tr=(?<trackers>.*)#', $input_magnet, $magnet_parts);

			if (!empty($magnet_parts['trackers'])) {

				$magnet_parts['trackers'] = explode('&', str_replace('tr=','', $magnet_parts['trackers']));

				require_once('../SSI.php');
				global $txt, $modSettings, $smcFunc, $db_prefix;
				
				if (!empty($modSettings['magnet_scraper_enabled'])) {
					if (!empty($modSettings['magnet_only_http']))
						$magnet_parts['trackers'] = preg_grep("(^http://|^https://)", $magnet_parts['trackers']);

					$magnet_parts['trackers'] = array_values($magnet_parts['trackers']);

					if (!empty($magnet_parts['trackers'][0])) {

						if (!empty($magnet_parts['hash']) && !empty($magnet_parts['filename'])) {

							$mag_click_result['Small'] = '';
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
									$mag_click_result['Small'] = ' (' . $txt['magnet_clicked'] . ' ' . $mag_result['click_count'] . ')';
									$mag_click_result['Normal'] = '<div class="magnet_return magnet_click">' . $txt['magnet_clicked'] . ' <span style="color:#0080ff;">' . $mag_result['click_count'] . '</span></div>';
								}
							}

							if (!empty($modSettings['magnet_scraper_enabled'])) {

								if (empty($modSettings['magnet_check_all'])) {
									$scraper = new Scrapeer\Scraper();
									$scrape_info = $scraper->scrape($magnet_parts['hash'], $magnet_parts['trackers'], !empty($modSettings['magnet_max_trackers_scrape']) ? $modSettings['magnet_max_trackers_scrape'] : null, !empty($modSettings['magnet_max_scrape_time']) ? $modSettings['magnet_max_scrape_time'] : 2, !empty($modSettings['magnet_announce']) ? true : false);

									if (!empty($scrape_info)) {
										if (!$seedsOnly) {
											$data = '<div>' . $magnet_parts['filename'] . '</div>';
										}
										$data .= '<div class="magnet_text_body"><div class="magnet_return">' . $txt['magnet_link_seeds'] . ' <span style="color:#00e600;">' . $scrape_info[$magnet_parts['hash']]['seeders'] . '</span></div><div class="magnet_return">' . $txt['magnet_link_leeches'] . ' <span style="color:#ff1a1a;">' . $scrape_info[$magnet_parts['hash']]['leechers'] . '</span></div><div class="magnet_return">' . $txt['magnet_link_downloaded'] . ' <span style="color:#0080ff;">' .  $scrape_info[$magnet_parts['hash']]['completed'] . '</span></div>' . $mag_click_result['Normal'] . '</div>';
									}
								}
								else {
									$completed_sum = 0; $seeds_sum = 0; $leechers_sum = 0; $count_successful_polls = 0; $count_successful_http_polls = 0;
									if (empty($modSettings['magnet_check_all_display_type'])) {
										foreach ($magnet_parts['trackers'] as $single_tracker) {
											$scraper = new Scrapeer\Scraper();
											$scrape_info = $scraper->scrape($magnet_parts['hash'], $single_tracker, !empty($modSettings['magnet_max_trackers_scrape']) ? $modSettings['magnet_max_trackers_scrape'] : null, !empty($modSettings['magnet_max_scrape_time']) ? $modSettings['magnet_max_scrape_time'] : 2, !empty($modSettings['magnet_announce']) ? true : false);
											if (!empty($scrape_info)) {
												$seeds_sum = $seeds_sum + $scrape_info[$magnet_parts['hash']]['seeders'];
												$leechers_sum = $leechers_sum + $scrape_info[$magnet_parts['hash']]['leechers'];
												$count_successful_polls = $count_successful_polls + 1;
												if (preg_match("(^http://|^https://)", $single_tracker)) {
													$completed_sum = $completed_sum + $scrape_info[$magnet_parts['hash']]['completed'];
													$count_successful_http_polls = $count_successful_http_polls + 1;
												}
											}
											if (!empty($modSettings['magnet_max_trackers_scrape']) && $count_successful_polls == $modSettings['magnet_max_trackers_scrape'])
												break;
										}
										if (!empty($count_successful_polls)) {
											$seeds_sum = Round($seeds_sum / $count_successful_polls, 1);
											$leechers_sum = Round($leechers_sum / $count_successful_polls, 1);
											if (empty($count_successful_http_polls))
												$count_successful_http_polls = 1;
											$completed_sum = Round($completed_sum / $count_successful_http_polls, 1);
											
											if (!$seedsOnly) {
												$data = '<div>' . $magnet_parts['filename'] . '</div>';
											}
											$data .= '<div class="magnet_text_body"><div class="magnet_return">' . $txt['magnet_link_seeds'] . ' <span style="color:#00e600;">'. $seeds_sum .'</span></div><div class="magnet_return">' . $txt['magnet_link_leeches'] . ' <span style="color:#ff1a1a;">'. $leechers_sum .'</span> ('. $txt['magnet_link_average'] .' '. $count_successful_polls .' '. ($count_successful_polls == 1 ? $txt['magnet_link_trackers_single'] : $txt['magnet_link_trackers']) .')</div><div class="magnet_return">' . $txt['magnet_link_downloaded'] . ' <span style="color:#0080ff;">' . $completed_sum . '</span> ('. $txt['magnet_link_average'] .' '. $count_successful_http_polls .' '. ($count_successful_http_polls == 1 ? $txt['magnet_link_trackers_single'] : $txt['magnet_link_trackers']) .')</div>' . $mag_click_result['Normal'] . '</div>';
										}
									}
									elseif ($modSettings['magnet_check_all_display_type'] == 1) {
										foreach ($magnet_parts['trackers'] as $single_tracker) {
											$scraper = new Scrapeer\Scraper();
											$scrape_info = $scraper->scrape($magnet_parts['hash'], $single_tracker, !empty($modSettings['magnet_max_trackers_scrape']) ? $modSettings['magnet_max_trackers_scrape'] : null, !empty($modSettings['magnet_max_scrape_time']) ? $modSettings['magnet_max_scrape_time'] : 2, !empty($modSettings['magnet_announce']) ? true : false);
											if (!empty($scrape_info)) {
												$seeds_sum = $seeds_sum + $scrape_info[$magnet_parts['hash']]['seeders'];
												$leechers_sum = $leechers_sum + $scrape_info[$magnet_parts['hash']]['leechers'];
												$count_successful_polls = $count_successful_polls + 1;
												if (preg_match("(^http://|^https://)", $single_tracker)) {
													if ($completed_sum < $scrape_info[$magnet_parts['hash']]['completed'])
														$completed_sum = $scrape_info[$magnet_parts['hash']]['completed'];
													$count_successful_http_polls = $count_successful_http_polls + 1;
												}
											}
											if (!empty($modSettings['magnet_max_trackers_scrape']) && $count_successful_polls == $modSettings['magnet_max_trackers_scrape'])
												break;
										}
										if (!empty($count_successful_polls)) {
											$seeds_sum = Round($seeds_sum / $count_successful_polls, 1);
											$leechers_sum = Round($leechers_sum / $count_successful_polls, 1);
											
											if (!$seedsOnly) {
												$data = '<div>' . $magnet_parts['filename'] . '</div>';
											}
											$data .= '<div class="magnet_text_body"><div class="magnet_return">' . $txt['magnet_link_seeds'] . ' <span style="color:#00e600;">' . $seeds_sum . '</span></div><div class="magnet_return">' . $txt['magnet_link_leeches'] . ' <span style="color:#ff1a1a;">' . $leechers_sum . '</span> ('. $txt['magnet_link_average'] .' '. $count_successful_polls .' '. ($count_successful_polls == 1 ? $txt['magnet_link_trackers_single'] : $txt['magnet_link_trackers']) .')</div><div class="magnet_return">' . $txt['magnet_link_downloaded'] . ' <span style="color:#0080ff;">' . $completed_sum . '</span> ('. $txt['magnet_link_max_value'] .' '. $count_successful_http_polls .' '. ($count_successful_http_polls == 1 ? $txt['magnet_link_trackers_single'] : $txt['magnet_link_trackers']) .')</div>' . $mag_click_result['Normal'] . '</div>';
										}
									}
									elseif ($modSettings['magnet_check_all_display_type'] == 2) {
										foreach ($magnet_parts['trackers'] as $single_tracker) {
											$scraper = new Scrapeer\Scraper();
											$scrape_info = $scraper->scrape($magnet_parts['hash'], $single_tracker, !empty($modSettings['magnet_max_trackers_scrape']) ? $modSettings['magnet_max_trackers_scrape'] : null, !empty($modSettings['magnet_max_scrape_time']) ? $modSettings['magnet_max_scrape_time'] : 2, !empty($modSettings['magnet_announce']) ? true : false);
											if (!empty($scrape_info)) {
												if ($seeds_sum < $scrape_info[$magnet_parts['hash']]['seeders'])
													$seeds_sum = $scrape_info[$magnet_parts['hash']]['seeders'];
												if ($leechers_sum < $scrape_info[$magnet_parts['hash']]['seeders'])
													$leechers_sum = $scrape_info[$magnet_parts['hash']]['leechers'];
												$count_successful_polls = $count_successful_polls + 1;
												if (preg_match("(^http://|^https://)", $single_tracker)) {
													if ($completed_sum < $scrape_info[$magnet_parts['hash']]['completed'])
														$completed_sum = $scrape_info[$magnet_parts['hash']]['completed'];
													$count_successful_http_polls = $count_successful_http_polls + 1;
												}
											}
											if (!empty($modSettings['magnet_max_trackers_scrape']) && $count_successful_polls == $modSettings['magnet_max_trackers_scrape'])
												break;
										}
										if (!empty($count_successful_polls)) {
											if (!$seedsOnly) {
												$data = '<div>' . $magnet_parts['filename'] . '</div>';
											}
											$data .= '<div class="magnet_text_body"><div class="magnet_return">' . $txt['magnet_link_seeds'] . ' <span style="color:#00e600;">'. $seeds_sum .'</span></div><div class="magnet_return">' . $txt['magnet_link_leeches'] . ' <span style="color:#ff1a1a;">'. $leechers_sum .'</span></div><div class="magnet_return">' . $txt['magnet_link_downloaded'] . ' <span style="color:#0080ff;">' . $completed_sum .'</span> ('. $txt['magnet_link_max_value'] .' '. $count_successful_polls .' '. ($count_successful_polls == 1 ? $txt['magnet_link_trackers_single'] : $txt['magnet_link_trackers']) .')</div>' . $mag_click_result['Normal'] . '</div>';
										}
									}
								}
							} else $data = '<div>' . $magnet_parts['filename'] . $mag_click_result['Small'] . '</div>';
						}
					}
				} else {
					$data = '<div>Trackers are not responding.</div>';
				}
			}
		}
	}
	if (empty($data)) {
		$data = '<div>Trackers are not responding.</div>';
	}
	echo $temp_uniqid .'	<div class="magnet_text_body">' . $data . '</div>';
?>