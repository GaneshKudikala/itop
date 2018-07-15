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
Dict::Add('EN US', 'English', 'English', array(
	// Class
	'Class:ClosingRule/Name' => '%1$s',
	'Class:ClosingRule' => 'Closing rule',
	'Class:ClosingRule+' => '',
	'Class:ClosingRule/Attribute:name' => 'Name',
	'Class:ClosingRule/Attribute:name+' => '',
	'Class:ClosingRule/Attribute:target_class' => 'Class',
	'Class:ClosingRule/Attribute:target_class+' => '',
	'Class:ClosingRule/Attribute:stimulus_code' => 'Stimulus code',
	'Class:ClosingRule/Attribute:stimulus_code+' => 'Code of the stimulus to apply',
	'Class:ClosingRule/Attribute:history_entry' => 'History entry',
	'Class:ClosingRule/Attribute:history_entry+' => 'Text or dictionary entry to add in the object history when auto-closing it',
	'Class:ClosingRule/Attribute:status' => 'Status',
	'Class:ClosingRule/Attribute:status+' => '',
	'Class:ClosingRule/Attribute:status/Value:active' => 'Active',
	'Class:ClosingRule/Attribute:status/Value:inactive' => 'Inactive',
	'Class:ClosingRule/Attribute:type' => 'Applied option',
	'Class:ClosingRule/Attribute:type+' => 'Which option will be used regarding the filled fields. If both are filled, advanced option is applied',
	'Class:ClosingRule/Attribute:type/Value:simple' => 'Simple',
	'Class:ClosingRule/Attribute:type/Value:advanced' => 'Advanced',
	'Class:ClosingRule/Attribute:pre_closing_state_code' => 'Pre-closing state code',
	'Class:ClosingRule/Attribute:pre_closing_state_code+' => 'State in which objects of the chosen class must be for the rule to apply',
	'Class:ClosingRule/Attribute:date_to_check_att' => 'Date to check',
	'Class:ClosingRule/Attribute:date_to_check_att+' => 'Attribute code of the date to check',
	'Class:ClosingRule/Attribute:autoclose_delay' => 'Autoclose delay',
	'Class:ClosingRule/Attribute:autoclose_delay+' => 'Delay in days before applying the stimulus on the objects (regarding the date to check)',
	'Class:ClosingRule/Attribute:oql_scope' => 'OQL scope',
	'Class:ClosingRule/Attribute:oql_scope+' => 'OQL query to define which objects are concerned by this rule (stimulus to apply). Note that the OQL will automatically be restricted to the states in which the stimulus is available.',

	// Integrity errors
	'Class:ClosingRule/Error:ClassNotValid' => 'Class must be a valid class from datamodel, "%1$s" given',
	'Class:ClosingRule/Error:AttributeNotValid' => '"%2$s" is not a valid attribute for class "%1$s"',
	'Class:ClosingRule/Error:AttributeMustBeDate' => '"%2$s" must be a date attribute of class "%1$s"',
	'Class:ClosingRule/Error:StateNotValid' => '"%2$s" is not a valid state for class "%1$s"',
	'Class:ClosingRule/Error:StimulusNotValid' => '"%2$s" is not a valid stimulus for class "%1$s"',
	'Class:ClosingRule/Error:ExistingRuleForClass' => 'There is already a closing rule for class "%1$s"',
	'Class:ClosingRule/Error:NoOptionFilled' => 'Either option 1 or option 2 must be filled',
	'Class:ClosingRule/Error:OptionOneMissingField' => 'All fields of option 1 must be filled',

	// Presentation
	'ClosingRule:general' => 'General informations',
	'ClosingRule:simple' => 'Fill either option 1 (simple) ...',
	'ClosingRule:advanced' => '... or option 2 (advanced)',

	// Menus
	'Menu:ClosingRule' => 'Closing rules',
	'Menu:ClosingRule+' => 'Closing rules',

	// Tabs
	'UI:AutocloseTicket:Preview' => 'Preview',
	'UI:AutocloseTicket:Title' => '%1$s to be closed as of now',
	'UI:AutocloseTicket:AdvancedTypeStatesNotice' => 'As this rule is based on an OQL, it was restricted to retrieve only objects in states where "%1$s" can be applied (%2$s).',

	// Explanation texts
	'AutoClose:HistoryEntry:Prefix' => 'Object closed automatically by closing rule "%1$s" (#%2$s) using option "%3$s".',
));
