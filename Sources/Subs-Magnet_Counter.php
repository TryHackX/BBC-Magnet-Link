<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data JSON
    $input = json_decode(file_get_contents('php://input'), true);

	require_once('../SSI.php');
	global $txt, $modSettings, $smcFunc, $db_prefix;

	if (empty($modSettings['magnet_support_for_clicks'])) {
		echo json_encode(['status' => 'error', 'message' => 'Features disabled.']);
		exit;
	}

    if (!is_array($input)) {
        echo json_encode(['status' => 'error', 'message' => $txt['magnet_invalid_json']]);
        exit;
    }

    // Extract magnet link hash
    $magnetHash = $input['Magnet_Hash'] ?? '';
    $msgID = (int)$input['msg_ID'] ?? '';

	//Client ip
	$clientIP = $_SERVER['REMOTE_ADDR'];

    if ($magnetHash) {
		if (!empty($modSettings['magnet_clicks_bans_support'])) {
			//Check banned / unban - 1200 = 20 min (20 * 60s)
			$banTimeInMinutes = (int)$modSettings['magnet_ban_time'];
			$banTimeInSeconds = $banTimeInMinutes * 60;

			$sql = "SELECT id, ban_time, 
					CASE 
						WHEN TIMESTAMPDIFF(MINUTE, ban_time, NOW()) >= {int:ban_time_min} THEN true 
						ELSE false 
					END AS need_unban,
					GREATEST(0, {int:ban_time_seconds} - TIMESTAMPDIFF(SECOND, ban_time, NOW())) AS time_left
					FROM {db_prefix}Mag_IPBanList 
					WHERE ip_address = {string:user_ip}
					LIMIT 1";

			$request = $smcFunc['db_query']('', $sql, 
				array(
					'user_ip' => $clientIP,
					'ban_time_min' => $banTimeInMinutes,
					'ban_time_seconds' => $banTimeInSeconds,
				)
			);

			$row = $smcFunc['db_fetch_assoc']($request);
			$smcFunc['db_free_result']($request);

			if ($row) {
				if (empty($row['need_unban'])) {
					echo json_encode(['status' => 'error', 'message' => $txt['magnet_baned_ip'] . ' ' . $row['time_left'] . 's)']);
					exit;
				} else {
					$sql = "DELETE FROM {db_prefix}Mag_IPBanList 
							WHERE ip_address = {string:user_ip}";
					$smcFunc['db_query']('', $sql, array('user_ip' => $clientIP));
				}
			}
		}

		//Check link_hash is it in the database
		$sql = "SELECT id FROM {db_prefix}Mag_Hashes WHERE link_hash = {string:link_hash} LIMIT 1";
		$request = $smcFunc['db_query']('', $sql, 
			array(
				'link_hash' => $magnetHash,
			)
		);
		$row = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);

		//Find if the magnet link was clicked recently (1 day) by user ip
		if (!empty($row['id'])) {
			$sql = "SELECT COUNT(*) as result 
					FROM {db_prefix}Mag_Clicks 
					WHERE link_id = {int:link_id} 
					AND ip_address = {string:user_ip} 
					AND click_time >= NOW() - INTERVAL {int:user_interval_day} DAY
					LIMIT 1";
			$request = $smcFunc['db_query']('', $sql, 
				array(
					'link_id' => $row['id'],
					'user_ip' => $clientIP,
					'user_interval_day' => (int)$modSettings['magnet_self_interval'],
				)
			);
			$row = $smcFunc['db_fetch_assoc']($request);
			$smcFunc['db_free_result']($request);
			
			if ($row['result'] > 0) {
				echo json_encode(['status' => 'error', 'message' => $txt['magnet_recently']]);
				exit;
			}
		}

		//Count the number of clicks in the last 10 minutes for ip to detect spam
		$sql = "SELECT COUNT(*) as result 
				FROM {db_prefix}Mag_Clicks 
				WHERE ip_address = {string:user_ip} 
				AND click_time >= NOW() - INTERVAL {int:ban_c_interval} MINUTE
				LIMIT {int:interval_count}";
		$request = $smcFunc['db_query']('', $sql, 
			array(
				'user_ip' => $clientIP,
				'link_hash' => $magnetHash,
				'ban_c_interval' => (int)$modSettings['magnet_ban_interval'],
				'interval_count' => (int)$modSettings['magnet_ban_interval_count'],
			)
		);
		$row = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);
		
		if (!empty($modSettings['magnet_clicks_bans_support'])) {
			//Add to banlist if exceeds the limit
			if ($row['result'] >= (int)$modSettings['magnet_ban_interval_count']) {
				$smcFunc['db_insert']('insert',
					'{db_prefix}Mag_IPBanList',
					array(
						'ip_address' => 'string'
					),
					array(
						$clientIP
					),
					array('ip_address'),
				);
				echo json_encode(['status' => 'error', 'message' => $txt['magnet_pls_no_spam']]);
				exit;
			}
		}

		//Try find magnetHash in Mag_Hashes
		$request = $smcFunc['db_query']('', '
			SELECT id, click_count
			FROM {db_prefix}Mag_Hashes
			WHERE link_hash = {string:link_hash}
			LIMIT 1',
			array(
				'link_hash' => $magnetHash,
			)
		);
		
		$hash_data = $smcFunc['db_fetch_assoc']($request);
		$smcFunc['db_free_result']($request);
		
		//Support: Mag_Hashes (id, link_hash, click_count), Get data / Add New
		if (!empty($hash_data)) {
			$link_id = $hash_data['id'];
			$click_count = $hash_data['click_count'];
		} else {
			//The record does not exist, add a new one in Mag_Hashes
			$smcFunc['db_insert']('insert',
				'{db_prefix}Mag_Hashes',
				array(
					'link_hash' => 'string',
					'click_count' => 'int'
				),
				array(
					$magnetHash,
					0
				),
				array('link_hash')
			);
			//Get the ID of the newly added record
			$link_id = $smcFunc['db_insert_id']('{db_prefix}Mag_Hashes', 'id');

			$click_count = 0;
		}

		//Support: Mag_Clicks (id, link_id, ip_address, click_time, msg_id), Add New / Change TIMESTAMP
		$params = array(
			'link_id' => $link_id,
			'ip_address' => $clientIP,
		);
		if (is_numeric($msgID) && $msgID > 0) {
			$query = 'INSERT INTO {db_prefix}Mag_Clicks (link_id, ip_address, click_time, msg_id)
					VALUES ({int:link_id}, {string:ip_address}, CURRENT_TIMESTAMP, {int:msgID})
					ON DUPLICATE KEY UPDATE click_time = CURRENT_TIMESTAMP';
			$params['msgID'] = $msgID;
		} else {
			$query = 'INSERT INTO {db_prefix}Mag_Clicks (link_id, ip_address, click_time)
					VALUES ({int:link_id}, {string:ip_address}, CURRENT_TIMESTAMP)
					ON DUPLICATE KEY UPDATE click_time = CURRENT_TIMESTAMP';
		}
		$smcFunc['db_query']('', $query, $params);

		//Update click count in Mag_Hashes
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}Mag_Hashes
			SET click_count = click_count + 1
			WHERE id = {int:link_id}',
			array(
				'link_id' => $link_id,
			)
		);
        
		echo json_encode(['status' => 'success', 'message' => $txt['magnet_link_counted']]);
	} else {
		echo json_encode(['status' => 'error', 'message' => $txt['magnet_invalid_hash']]);
	}
} else {
	echo json_encode(['status' => 'error', 'message' => $txt['magnet_invalid_request']]);
}

?>