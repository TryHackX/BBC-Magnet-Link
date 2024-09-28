<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

$txt['magnet_mod_name'] = 'BBC Magnet Link';
$txt['magnet_mod_description'] = 'Magnet Link';

$txt['magnet_link_seeds'] = 'Seeds:';
$txt['magnet_link_leeches'] = 'Leeches:';
$txt['magnet_link_downloaded'] = 'Downloaded:';
$txt['magnet_link_error_no_information'] = 'No torrent information or active http(s) trackers';
$txt['magnet_link_error_bad_magnet_link'] = 'Probably a problem with the magnet link';
$txt['magnet_link_error_lack_trackers'] = 'No trackers found';
$txt['magnet_link_error_not_magnet_link'] = 'This is not a magnetic link';
$txt['magnet_link_error_no_input_data'] = 'No magnet link provided';
$txt['magnet_link_error_no_http_trackers'] = 'Magnet link does not contain http(s) trackers';
$txt['magnet_link_average'] = 'Average of';
$txt['magnet_link_trackers'] = 'trackers';
$txt['magnet_link_trackers_single'] = 'tracker';
$txt['magnet_link_max_value'] = 'The highest value of';
$txt['magnet_attachment'] = 'Attachment:';
$txt['magnet_attachments'] = 'Attachments:';
$txt['magnet_not_allowed'] = 'You are not allowed to view magnet links.';
$txt['magnet_not_allowed_reg'] = 'Register';
$txt['magnet_not_allowed_or'] = 'or';
$txt['magnet_not_allowed_log'] = 'Login!';

$txt['magnet_general_settings'] = 'General settings';
	$txt['magnet_guest'] = 'Visibility for guests:';
	$txt['magnet_guest_sup'] = 'Whether the visitor can see the trackers and the number of downloads of torrent attachments.';

$txt['magnet_scraper_settings'] = 'Tracker scraping settings';
	$txt['magnet_scraper_enabled'] = 'Enable tracker scraping:';
	$txt['magnet_scraper_enabled_sup'] = 'It allows you to scrape the tracker for information about seeds, leeches and downloads.';
	$txt['magnet_only_http'] = 'Enable only http(s) trackers:';
	$txt['magnet_only_http_sup'] = 'This setting will enable the use of only http(s) trackers.';
	$txt['magnet_check_all'] = 'Check all trackers:';
	$txt['magnet_check_all_sup'] = 'This setting will enable polling of all trackers, then display data in the style of the option selected below.';
	$txt['magnet_check_all_display_type'] = 'Display style:';
	$txt['magnet_check_all_display_type_sup'] = 'Select an action type for check all.';
	$txt['magnet_check_all_options'] = array(
		'Average for everything',
		'Average for seeds and leeches, highest for downloaded',
		'Highest values for everything',
	);
	$txt['magnet_announce'] = 'Polling / Scraping via announce:';
	$txt['magnet_announce_sup'] = 'Use announce to polling / scrape trackers instead of scrape.';
	$txt['magnet_max_scrape_time'] = 'Time limit per tracker:';
	$txt['magnet_max_scrape_time_sup'] = 'This setting allows you to set the maximum amount of time in seconds it takes for the tracker to respond. (Default 0 - 2 seconds)';
	$txt['magnet_max_trackers_scrape'] = 'Maximum number of trackers:';
	$txt['magnet_max_trackers_scrape_sup'] = 'Set the maximum number of trackers to be queried. (Default 0 - all, if the "Check all" function is enabled, this is the limit of correct queries)';

$txt['magnet_attachments_settings'] = 'Attachment settings';
	$txt['magnet_support_for_attachments'] = 'Enable attachment support:';
	$txt['magnet_support_for_attachments_sup'] = 'This setting will enable support for handling attachments.';
	$txt['magnet_attachments_display_type'] = 'Attachment display style:';
	$txt['magnet_attachments_display_type_sup'] = 'Choose how to present information about the number of downloads of attachments.';
	$txt['magnet_support_attachments_options'] = array(
		'Minimum and maximum number of torrent attachment downloads',
		'Total number of downloads, from all torrent attachments',
		'The number of downloads from the attachment with the highest number of downloads',
		'The number of downloads from the attachment with the lowest number of downloads',
		'Average number of downloads from torrent attachments',
	);

$txt['magnet_clicks_settings'] = 'Click count settings';
	$txt['magnet_support_for_clicks'] = 'Enable counting support:';
	$txt['magnet_support_for_clicks_sup'] = 'This setting will enable support for magnet link click counting.';
	$txt['magnet_clicks_bans_support'] = 'Enable banning support:';
	$txt['magnet_clicks_bans_support_sup'] = 'This setting allows you to enable banning of spammers.';
	$txt['magnet_ban_time'] = 'Ban time:';
	$txt['magnet_ban_time_sup'] = 'Set the ban time in minutes.';
	$txt['magnet_ban_interval'] = 'Measurement time:';
	$txt['magnet_ban_interval_sup'] = 'Time frame in minutes in which the number of clicks is counted.';
	$txt['magnet_ban_interval_count'] = 'Number of measurements:';
	$txt['magnet_ban_interval_count_sup'] = 'Determines the number of clicks which, when reached within the specified time, will be considered spam.';
	$txt['magnet_self_interval'] = 'Days to count:';
	$txt['magnet_self_interval_sup'] = 'How many days must pass for another click by a person who has already clicked to be counted again.';

	$txt['magnet_invalid_json'] = 'Invalid JSON input.';
	$txt['magnet_baned_ip'] = 'Your IP is banned. (Left:';
	$txt['magnet_recently'] = 'Your IP recently clicked on a magnet link.';
	$txt['magnet_pls_no_spam'] = 'Please dont spam magnet click.';
	$txt['magnet_link_counted'] = 'Magnet link counted successfully.';
	$txt['magnet_invalid_hash'] = 'Invalid magnet hash.';
	$txt['magnet_invalid_request'] = 'Invalid request method.';
	$txt['magnet_clicked'] = 'Clicks:';

?>