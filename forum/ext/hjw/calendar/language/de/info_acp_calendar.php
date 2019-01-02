<?php
/**
*
* @package hjw calendar Extension
* @copyright (c) 2019 hjw
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Common
$lang = array_merge($lang, array(
	'ACP_BIRTHDAY_ON_CALENDAR'				=> 'Geburtstage im Kalender anzeigen?',
	'ACP_CALENDAR_ALL'						=> 'Alle',
	'ACP_CALENDAR_ALLOWED_0'				=> 'nicht erlaubt',
	'ACP_CALENDAR_ALLOWED_1'				=> 'erlaubt',
	'ACP_CALENDAR_ANNIVERSARY'				=> 'Jahrestag',
	'ACP_CALENDAR_APPOINTMENT_CREATE'		=> 'Termin anlegen',
	'ACP_CALENDAR_APPOINTMENT_DESCRIPTION'	=> 'Beschreibung',
	'ACP_CALENDAR_APPOINTMENT_LINK'			=> 'Link',
    'ACP_CALENDAR_APPOINTMENT_LIST'			=> 'Termin-Liste',
    'ACP_CALENDAR_APPOINTMENT_LIST_TEXT'	=> 'Die hier angelegten Termine werden in der angelegten Farbe im Kalender angezeigt.</p> 
												<p>Wenn das Datum mit der Jahreszahl angegeben wird, erfolgt die Anzeige nur in dem 
												betreffenden Jahr. Es sei denn, man hat ein Häkchen bei Jahrestag gemacht.  
												Dann erfolgt die Anzeige jedes Jahr mit der Anzahl der vergangenen Jahre.</p>
												<p>Bei Angabe ohne Jahreszahl wird der Termin jedes Jahr gezeigt.',	
	'ACP_CALENDAR_APPOINTMENT_NAME'			=> 'Termin-Name',
	'ACP_CALENDAR_BIG'						=> 'Hervorheben',
	'ACP_CALENDAR_CHANGE'					=> 'ändern',
	'ACP_CALENDAR_COLOR'					=> 'Farbe',
	'ACP_CALENDAR_COLOR_B'					=> 'Hintergrundfarbe',
	'ACP_CALENDAR_DATE'						=> 'Datum (T.M.)',
	'ACP_CALENDAR_DATE_FROM'				=> 'Von/Am (T.M.JJJJ)',
	'ACP_CALENDAR_DATE_TO'					=> 'Bis (T.M.JJJJ)',
	'ACP_CALENDAR_DISPLAYOPTIONS'			=> 'Anzeige-Optionen',
	'ACP_CALENDAR_EASTER_DAYS'				=> 'Die Tage bis bzw. nach Ostern',
	'ACP_CALENDAR_ENTRIES'					=> 'Kalender-Einträge',
	'ACP_CALENDAR_EVENT'					=> 'Veranstaltungstyp',
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Einstellungen',
	'ACP_CALENDAR_EVENT_CREATE'				=> 'Veranstaltungstyp anlegen',
	'ACP_CALENDAR_EVENT_SETTINGS'			=> 'Einstellungen Veranstaltungen',
	'ACP_CALENDAR_EVENT_SETTINGS_TEXT'		=> 'Die den entsprechenden Typen zugeordneten Veranstaltungen werden in den 
												entsprechenden Farben und in der hier gezeigten Reihenfolge im Kalender angezeigt.',
	'ACP_CALENDAR_EVENTS'					=> 'Veranstaltungen',
	'ACP_FOOTBALL_ON_CALENDAR'				=> 'Fußballspiele im Kalender anzeigen?',
	'ACP_CALENDAR_ON_INDEX_OFF'				=> 'Übersicht/Termine in der Kopfzeile für den aktuellen Tag abschaltbar?',
	'ACP_CALENDAR_FOR_GUESTS'				=> 'Kalender den Gästen anzeigen?',
	'ACP_CALENDAR_FORUM_SETTINGS'			=> 'Foren-Einstellungen',
	'ACP_CALENDAR_FORUM_SETTINGS_TEXT'		=> 'Nur in den grün gefärbten Foren können Termine angelegt werden.',
	'ACP_CALENDAR_SETTINGS'					=> 'Einstellungen',
	'ACP_CALENDAR_INSTRUCTIONS'				=> 'Anleitung',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT'		=> 'Wochenüberblick oder nächste Termine',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_0'		=> 'Anzeige des Wochenüberblicks bzw. Nächste Termine',
	'ACP_CALENDAR_INSTRUCTIONS_TEXT_1'		=> 'Es gibt zwei Möglichkeiten Kalendereinträge anzulegen.</p>
												<p>Wenn in dem entsprechende Forum die Termineingabe erlaubt ist und mindestens ein Veranstaltungstyp
												erstellt ist, gibt es über dem Beitragsformular eine Zeile, in die die entsprechenden Einträge gemacht
												werden können. Der Name wird im Kalender angezeigt und sollte nicht zu lang gewählt werden. Der Betreff 
												des Beitrages wird angezeigt, wenn man mit dem Cursor im Kalender auf den Namen zeigt. Da kann man dann
												zusätzliche Informationen geben. Beim Klick auf den Namen wird der Beitrag aufgerufen. Die Eingabe des 
												Bis-Datums ist optional für mehrtägige Veranstaltungen.</p>
												<p>Falls der Veranstaltungstyp mit Teilnehmerliste angelegt ist, ist diese nach Absendung des
												Beitrages in der viewtopic.php zu sehen. Teilnehmerlisten werden Gästen und Bots nicht
												gezeigt.</p>
												<p>Im Kalender werden nur Termine aus Foren angezeigt bei denen der Betrachter Leserecht hat.</p>
												<p>Die zweite Möglichkeit Termine anzulegen, ist die Terminliste hier im ACP.
												Dort lassen sich auch Termine mit Link auf andere Seiten anlegen.',
	'ACP_CALENDAR_LEGEND_DISPLAY'			=> 'Legende der Veranstaltungs-Typen unterhalb des Kalenders anzeigen?',
	'ACP_CALENDAR_NAME'						=> 'Name',
	'ACP_CALENDAR_NOTIFY'					=> 'Benutzer über neuen bzw. geänderten Kalender-Eintrag informieren?',
	'ACP_CALENDAR_NOTIFY_PARTICIPATING'		=> 'Benutzer über neuen bzw. geänderten Teilnehmer-Eintrag informieren?',
	'ACP_CALENDAR_NUMBER_OF_WEEKS'			=> 'Anzahl der Wochen bzw. Zeilen mit Terminen',
	'ACP_CALENDAR_NUMBER_PARTICIPATING'		=> 'Anzahl der Teilnehmer im Kalender anzeigen?',
	'ACP_CALENDAR_ONLY_PARTICIPANT'			=> 'Nur Teilnehmer',
	'ACP_CALENDAR_ONLY_AUTOR'				=> 'Nur an den Verfasser',
	'ACP_CALENDAR_SHOW'						=> 'Tag anzeigen?',
	'ACP_CALENDAR_SPECIAL_DAYS'				=> 'Besondere Tage, Feiertage',
	'ACP_CALENDAR_SPECIAL_DAY_CREATE'		=> 'Tag anlegen',
	'ACP_CALENDAR_SPECIAL_DAYS_TEXT'		=> 'Bewegliche Feiertage werden nach dem Ostersonntag berechnet.</p>
												<p>Die Tage bis Ostern werden negativ eingegeben.',
	'ACP_CALENDAR_PARTICIPANT'				=> 'Teilnehmerliste anlegen',
	'ACP_CALENDAR_PARTICIPANTS_NAME'		=> 'Namen der Teilnehmer beim Positionieren des Cursors auf den Termin anzeigen',
	'ACP_CALENDAR_0'						=> 'Nein',
	'ACP_CALENDAR_1'						=> 'Ja',
	'ACP_WEEKBLOCK_TEMPLATE_0'				=> 'Keine Anzeige',
	'ACP_WEEKBLOCK_TEMPLATE_1'				=> 'Vor dem Header',
	'ACP_WEEKBLOCK_TEMPLATE_2'				=> 'Vor der Navigation',
	'ACP_WEEKBLOCK_TEMPLATE_3'				=> 'Vor dem Footer',
	'ACP_WEEK_NEXT_1'						=> 'Wochenüberblick',
	'ACP_WEEK_NEXT_2'						=> 'Nächste Termine',
	'ACP_WEEK_NEXT_3'						=> 'Beides',
	'ACP_CALENDAR_EVENT_CONFIG'				=> 'Veranstaltungs-Typen',
	'ACP_CALENDAR_EVENT_LIST'				=> 'Termin-Liste',
	'ACP_CALENDAR_FORUMS_CONFIG'			=> 'Foren-Einstellungen',
	'ACP_CALENDAR_ONLY_FIRST_POST'			=> 'Eingabe nur im ersten Beitrag eines Themas',
	'ACP_CALENDAR_RESET' 					=> 'Zurücksetzen',
	'ACP_CALENDAR_SEND' 					=> 'Absenden',
	'ACP_CALENDAR_SPECIAL_DAY'				=> 'Feiertage',
	'ACP_CALENDAR_TAB_0'					=> 'Vor dem Textfeld',
	'ACP_CALENDAR_TAB_1'					=> 'Im Tab unter dem Textfeld',
	'ACP_CALENDAR_TAB_TEXT'					=> 'Anzeige der Kalender-Einstellungen',
	'ACP_CALENDAR_TITLE'					=> 'Kalender',
	'ACP_CALENDAR_TO_DISPLAY'				=> 'anzeigen',
	'ACP_CALENDAR_VERSION'					=> ' Version ',
	'ACP_CALENDAR_WEEK_DISPLAY'				=> 'Anzeige der Wochen-Nummer (Nur korrekt wenn der Wochenanfang Montag ist)',
	'ACP_CALENDAR_WEEK_START'				=> 'Erster Tag der Woche',
));