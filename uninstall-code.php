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

	//Integrate the hooks
	$hooks = array(
		'integrate_pre_include' => '$sourcedir/Subs-Magnet_Link.php',
		'integrate_bbc_buttons' => 'Magnet_bbc_button',
		'integrate_bbc_codes' => 'Magnet_bbc_codes',
		'integrate_load_theme' => 'Magnet_bbc_theme',
		'integrate_admin_areas' => 'Magnet_admin_areas',
		'integrate_modify_modifications' => 'Magnet_modify_settings'
	);

	foreach($hooks AS $hook => $call)
		remove_integration_function($hook,$call);

	if($direct_install)
		echo 'Done... BBC Magnet Link has been successfully uninstalled. Congratulations!';

?>