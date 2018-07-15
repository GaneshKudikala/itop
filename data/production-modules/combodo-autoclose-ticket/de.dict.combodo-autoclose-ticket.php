<?php
// Copyright (C) 2012-2018 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Localized data
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @author      Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

// ClosingRule
Dict::Add('DE DE', 'German', 'Deutsch', array(
	// Class
	'Class:ClosingRule/Name' => '%1$s',
	'Class:ClosingRule' => 'Schließregel',
	'Class:ClosingRule+' => '',
	'Class:ClosingRule/Attribute:name' => 'Name',
	'Class:ClosingRule/Attribute:name+' => '',
	'Class:ClosingRule/Attribute:target_class' => 'Klasse',
	'Class:ClosingRule/Attribute:target_class+' => '',
	'Class:ClosingRule/Attribute:stimulus_code' => 'Stimulus-Code',
	'Class:ClosingRule/Attribute:stimulus_code+' => 'Code (Name) des anzuwendenden Stimulus',
	'Class:ClosingRule/Attribute:history_entry' => 'Verlaufseintrag',
	'Class:ClosingRule/Attribute:history_entry+' => 'Text oder Wörterbucheintrag, der dem Verlauf des Objekts beim autmatischen schließen hinzugefügt wird.',
	'Class:ClosingRule/Attribute:status' => 'Status',
	'Class:ClosingRule/Attribute:status+' => '',
	'Class:ClosingRule/Attribute:status/Value:active' => 'Aktiv',
	'Class:ClosingRule/Attribute:status/Value:inactive' => 'Inaktiv',
	'Class:ClosingRule/Attribute:type' => 'Anzuwendende Option',
	'Class:ClosingRule/Attribute:type+' => 'Welche Option soll angewendet werden bzgl. der gefüllten Felder. Wenn beide gefüllt soind, wird die fortgeschrittene option verwendet',
	'Class:ClosingRule/Attribute:type/Value:simple' => 'Einfach',
	'Class:ClosingRule/Attribute:type/Value:advanced' => 'Fortgeschritten',
	'Class:ClosingRule/Attribute:pre_closing_state_code' => 'Status für automatisches Schließen',
	'Class:ClosingRule/Attribute:pre_closing_state_code+' => 'Status in dem Objekte der gewählten Klasse sein müssen, damit die Regel angewandt wird.',
	'Class:ClosingRule/Attribute:date_to_check_att' => 'Zu überprüfendes Datumsfeld',
	'Class:ClosingRule/Attribute:date_to_check_att+' => 'Attributs-Code des zu überprüfenden Datumsfelds',
	'Class:ClosingRule/Attribute:autoclose_delay' => 'Wartezeit für automatisches Schließen',
	'Class:ClosingRule/Attribute:autoclose_delay+' => 'Wartezeit in Tagen, nach derer (ggü. dem zu überprüfenden Datumsfeld) der Stimulus bei betroffenen Objekten angewandt werden soll.',
	'Class:ClosingRule/Attribute:oql_scope' => 'OQL Scope',
	'Class:ClosingRule/Attribute:oql_scope+' => 'OQL Query der definiert, welche Objekte von dieser Regel betroffen sind (anzuwendender Stimulus). Beachten Sie, dass das OQL automatisch auf die Status beschränkt wird, in denen der Stimulus verfügbar ist',
	

	// Integrity errors
	'Class:ClosingRule/Error:ClassNotValid' => 'Die Klasse muss eine gültige Klasse des Datenmodells sein, es wurde aber "%1$s" angegeben.',
	'Class:ClosingRule/Error:AttributeNotValid' => '"%2$s" ist kein gültiges Attribut für die Klasse "%1$s"',
	'Class:ClosingRule/Error:AttributeMustBeDate' => '"%2$s" muss ein Attribut der Klasse "%1$s" sein',
	'Class:ClosingRule/Error:StateNotValid' => '"%2$s" ist kein gültiger Status für Klasse "%1$s"',
	'Class:ClosingRule/Error:StimulusNotValid' => '"%2$s" ist kein gültiger Stimulus für Klasse "%1$s"',
	'Class:ClosingRule/Error:ExistingRuleForClass' => 'Es gibt bereits einer Schließregel für Klasse "%1$s"',
	'Class:ClosingRule/Error:NoOptionFilled' => 'Entweder Option 1 oder Option 2 müssen ausgefüllt sein. ',
	'Class:ClosingRule/Error:OptionOneMissingField' => 'Alle Felder von Option 1 müssen ausgefüllt sein. ',

	// Presentation
	'ClosingRule:general' => 'Allgemeine Informationen',
	'ClosingRule:simple' => 'Füllen Sie entweder Option 1 aus (Einfach)...',
	'ClosingRule:advanced' => '... oder Option 2 (Fortgeschritten)',

	// Menus
	'Menu:ClosingRule' => 'Schließregeln',
	'Menu:ClosingRule+' => 'Schließregeln',

	// Tabs
	'UI:AutocloseTicket:Preview' => 'Vorschau',
	'UI:AutocloseTicket:Title' => '%1$s die Stand jetzt geschlossen werden sollen',
	'UI:AutocloseTicket:AdvancedTypeStatesNotice' => 'Da diese Regel auf einenm OQL basiert wurde sie auf Objekte in Status in denen "%1$s" als Stimulus angewendet werden kann beschränkt (%2$s).',

	// Explanation texts
	'AutoClose:HistoryEntry:Prefix' => 'Objekt automatisch von Schließregel "%1$s" (#%2$s) geschlossen, mittels Option "%3$s"',
));
