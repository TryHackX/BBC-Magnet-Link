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

$txt['magnet_link_seeds'] = 'Seedy:';
$txt['magnet_link_leeches'] = 'Pijawki:';
$txt['magnet_link_downloaded'] = 'Pobrano:';
$txt['magnet_link_error_no_information'] = 'Brak informacji o torrencie, lub aktywnych trackerów http(s)';
$txt['magnet_link_error_bad_magnet_link'] = 'Prawdopodobnie problem z magnet link';
$txt['magnet_link_error_lack_trackers'] = 'Nie znaleziono trackerów';
$txt['magnet_link_error_not_magnet_link'] = 'To nie jest magnet link';
$txt['magnet_link_error_no_input_data'] = 'Nie podano magnet link';
$txt['magnet_link_error_no_http_trackers'] = 'Magnet link nie zawiera trackerów http(s)';
$txt['magnet_link_average'] = 'Średnia z';
$txt['magnet_link_trackers'] = 'trackerów';
$txt['magnet_link_trackers_single'] = 'trackera';
$txt['magnet_link_max_value'] = 'Najwieksza wartość z';
$txt['magnet_attachment'] = 'Załącznik:';
$txt['magnet_attachments'] = 'Załaczniki:';
$txt['magnet_not_allowed'] = 'You are not allowed to view magnet links.';
$txt['magnet_not_allowed_reg'] = 'Register';
$txt['magnet_not_allowed_or'] = 'or';
$txt['magnet_not_allowed_log'] = 'Login!';

$txt['magnet_general_settings'] = 'Ustawienia generalne';
	$txt['magnet_guest'] = 'Widoczność dla gości:';
	$txt['magnet_guest_sup'] = 'Czy gość może zobaczyć trackery i ilość pobrań załaczników torrent.';

$txt['magnet_scraper_settings'] = 'Ustawienia skrobania trackerów';
	$txt['magnet_scraper_enabled'] = 'Włącz skrobanie trackera:';
	$txt['magnet_scraper_enabled_sup'] = 'Umożliwia skrobanie trackera w celu uzyskania informacji o seeds, pijawkach i pobraniach.';
	$txt['magnet_only_http'] = 'Włącz tylko http(s) trackery:';
	$txt['magnet_only_http_sup'] = 'To ustawienie włączy wykorzystywanie tylko http(s) trackery.';
	$txt['magnet_check_all'] = 'Sprawdź wszystkie:';
	$txt['magnet_check_all_sup'] = 'To ustawienie włączy odpytywanie wszystkich trackerów, następnie wyświetli dane w stylu niżej wybranej opcji.';
	$txt['magnet_check_all_display_type'] = 'Rodzaj wyświetlenia:';
	$txt['magnet_check_all_display_type_sup'] = 'Wybierz rodzaj działania dla sprawdź wszystkie.';
	$txt['magnet_check_all_options'] = array(
		'Średnia dla wszystkiego',
		'Średnia dla seedów i pijawek, najwyższy dla pobranie',
		'Najwyższe wartości dla wszystkiego',
	);
	$txt['magnet_announce'] = 'Odpytywanie / Skrobanie przez announce:';
	$txt['magnet_announce_sup'] = 'Wykorzystuj announce do odpytywania / skrobania trackerów zamiast scrape.';
	$txt['magnet_max_scrape_time'] = 'Limit czasu na tracker:';
	$txt['magnet_max_scrape_time_sup'] = 'To ustawienie pozwala ustawić maksymalną ilość czasu w sekundach potrzebną na odpowiedź trackera. (Domyślnie 0 - 2 sekundy)';
	$txt['magnet_max_trackers_scrape'] = 'Maksymalna liczba trackerów:';
	$txt['magnet_max_trackers_scrape_sup'] = 'Ustaw maksymalną liczba odpytywanych trackerów. (Domyślnie 0 - wszystkie, w przypadku właczonej funkcji "Sprawdź wszystkie" stanowi to limit poprawnych odpytań)';

$txt['magnet_attachments_settings'] = 'Ustawienia załączników';
	$txt['magnet_support_for_attachments'] = 'Włącz obsługę załączników:';
	$txt['magnet_support_for_attachments_sup'] = 'To ustawienie włączy wsparcie dla obsługi załączników.';
	$txt['magnet_attachments_display_type'] = 'Styl załącznika:';
	$txt['magnet_attachments_display_type_sup'] = 'Wybierz, w jaki sposób przedstawić informacje o liczbie pobrań załaczników.';
	$txt['magnet_support_attachments_options'] = array(
		'Minimalna i maksymalna liczba pobrań załączników torrent',
		'Całkowita liczba pobrań ze wszystkich załączników torrent',
		'Liczba pobrań z załącznika o najwyższej ilości pobrań',
		'Liczba pobrań z załącznika o najniższej ilości pobrań',
		'Średnia liczba pobrań z załączników torrent',
	);

$txt['magnet_clicks_settings'] = 'Ustawienia zliczania kliknięć';
	$txt['magnet_support_for_clicks'] = 'Włącz obsługę zliczania:';
	$txt['magnet_support_for_clicks_sup'] = 'To ustawienie włączy wsparcie dla zliczania kliknięć w magnet link.';
	$txt['magnet_clicks_bans_support'] = 'Włącz obsługę banowania:';
	$txt['magnet_clicks_bans_support_sup'] = 'To ustawienie pozwala włączyć banowanie spamerów.';
	$txt['magnet_ban_time'] = 'Czas bana:';
	$txt['magnet_ban_time_sup'] = 'Ustaw czas zbanowania w miniutach.';
	$txt['magnet_ban_interval'] = 'Czas pomiaru:';
	$txt['magnet_ban_interval_sup'] = 'Rama czasowa w miniutach w których jest liczona ilość kliknięć.';
	$txt['magnet_ban_interval_count'] = 'Ilość pomiarów:';
	$txt['magnet_ban_interval_count_sup'] = 'Określa liczbę kliknięć która gdy zostanie osiągnięta w podanym czasie, zostanie uznana za spam.';
	$txt['magnet_self_interval'] = 'Dni do podliczenia:';
	$txt['magnet_self_interval_sup'] = 'Ile dni musi upłynąć aby kolejne kliknięcie przez osobę która już klikneła, było ponownie doliczone.';

	$txt['magnet_invalid_json'] = 'Nieprawidłowe dane wejściowe JSON.';
	$txt['magnet_baned_ip'] = 'Twój adres IP został zablokowany. (Pozostało:';
	$txt['magnet_recently'] = 'Twój adres IP kliknął ostatnio link magnet.';
	$txt['magnet_pls_no_spam'] = 'Prosimy o nie spamowanie kliknięć magnet link.';
	$txt['magnet_link_counted'] = 'Magnet link zostało pomyślnie zliczone.';
	$txt['magnet_invalid_hash'] = 'Nieprawidłowy magnet hash.';
	$txt['magnet_invalid_request'] = 'Nieprawidłowa metoda żądania.';
	$txt['magnet_clicked'] = 'Kliki:';

?>