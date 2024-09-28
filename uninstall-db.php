<?php
/**
 * BBC Magnet Link
 * Version: 1.0.6
 * Author: Dominik
 * 2024
 * 
 * version smf 2.0.* & 2.1.*
 */

	if (file_exists(__DIR__ . '/SSI.php') && !defined('SMF'))
		require_once __DIR__ . '/SSI.php';

	elseif (!defined('SMF'))
		die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

	db_extend('packages');

	// Usunięcie dodatkowych tabel utworzonych przez modyfikację
	$tabeli_do_usuniecia = array(
	    'mag_hashes',
	    'mag_clicks',
	    'mag_ipbanlist'
	);

	foreach ($tabeli_do_usuniecia as $tabela)
	{
	    //$smcFunc['db_query']('', 'DROP TABLE IF EXISTS {db_prefix}' . $tabela);
		$smcFunc['db_drop_table']('{db_prefix}'. $tabela);
	}
	
	// Usunięcie ustawień modyfikacji z tabeli settings
	$smcFunc['db_query']('', "DELETE FROM {db_prefix}settings WHERE variable LIKE 'Magnet_%'");

?>
