<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

	$direct_install = false;
	if(file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')){
		require_once(dirname(__FILE__) . '/SSI.php');
		$direct_install = true;
	}
	elseif (!defined('SMF'))
		die('BBC Magnet Link cannot connect with smf.');

	global $smcFunc, $db_prefix;
	
	if (!array_key_exists('db_add_column', $smcFunc))
		db_extend('packages');

	$columns = array(
		array(
			'name' => 'id',
			'type' => 'int',
			'size' => 10,
			'unsigned' => true,
			'auto' => true,
		),
		array(
			'name' => 'link_hash',
			'type' => 'varchar',
			'size' => 255,
			'null' => false,
		),
		array(
			'name' => 'click_count',
			'type' => 'int',
			'size' => 10,
			'default' => 0,
			'null' => false,
		),
	);

	$indexes = array(
		array(
			'type' => 'primary',
			'columns' => array('id')
		),
		array(
			'name' => 'link_hash',
			'type' => 'unique',
			'columns' => array('link_hash')
		),
	);

	$smcFunc['db_create_table']($db_prefix . 'Mag_Hashes', $columns, $indexes, array(), 'ignore');

	$columns = array(
		array(
			'name' => 'id',
			'type' => 'int',
			'size' => 10,
			'unsigned' => true,
			'auto' => true,
			'null' => false,
		),
		array(
			'name' => 'link_id',
			'type' => 'int',
			'size' => 10,
			'unsigned' => true,
			'null' => false,
		),
		array(
			'name' => 'ip_address',
			'type' => 'varchar',
			'size' => 45,
			'null' => false,
		),
		array(
			'name' => 'click_time',
			'type' => 'timestamp',
			'null' => false,
		),
		array(
			'name' => 'msg_id',
			'type' => 'int',
			'null' => true,
		),
	);

	$indexes = array(
		array(
			'type' => 'primary',
			'columns' => array('id')
		),
		array(
			'name' => 'unique_link_ip',
			'type' => 'unique',
			'columns' => array('link_id', 'ip_address', 'msg_id')
		),
	);

	$smcFunc['db_create_table']($db_prefix . 'Mag_Clicks', $columns, $indexes, array(), 'ignore');
	$smcFunc['db_query']('', '
		ALTER TABLE {db_prefix}Mag_Clicks 
		MODIFY COLUMN click_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL'
	);

	$columns = array(
		array(
			'name' => 'id',
			'type' => 'int',
			'size' => 10,
			'unsigned' => true,
			'auto' => true,
			'null' => false,
		),
		array(
			'name' => 'ip_address',
			'type' => 'varchar',
			'size' => 45,
			'null' => false,
		),
		array(
			'name' => 'ban_time',
			'type' => 'timestamp',
			'null' => false,
		),
	);

	$indexes = array(
		array(
			'type' => 'primary',
			'columns' => array('id')
		),
		array(
			'name' => 'ip_address',
			'type' => 'unique',
			'columns' => array('ip_address')
		),
	);

	$smcFunc['db_create_table']($db_prefix . 'Mag_IPBanList', $columns, $indexes, array(), 'ignore');
	$smcFunc['db_query']('', '
		ALTER TABLE {db_prefix}Mag_IPBanList 
		MODIFY COLUMN ban_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL'
	);

	//Integrate the hooks
	$hooks = array(
		'integrate_pre_include' => '$sourcedir/Subs-Magnet_Link.php',
		'integrate_bbc_codes' => 'Magnet_bbc_codes',
		'integrate_bbc_buttons' => 'Magnet_bbc_button',
		'integrate_load_theme' => 'Magnet_bbc_theme',
		'integrate_admin_areas' => 'Magnet_admin_areas',
		'integrate_modify_modifications' => 'Magnet_modify_settings'
	);

	foreach($hooks AS $hook => $call)
		add_integration_function($hook,$call);

	if($direct_install)
		echo 'Done... BBC Magnet Link has been successfully installed. Congratulations!';

?>