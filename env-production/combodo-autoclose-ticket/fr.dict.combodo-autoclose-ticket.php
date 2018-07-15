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
Dict::Add('FR FR', 'French', 'Français', array(
	// Class
	'Class:ClosingRule/Name' => '%1$s',
	'Class:ClosingRule' => 'Règle de clôture',
	'Class:ClosingRule+' => '',
	'Class:ClosingRule/Attribute:name' => 'Nom',
	'Class:ClosingRule/Attribute:name+' => '',
	'Class:ClosingRule/Attribute:target_class' => 'Classe',
	'Class:ClosingRule/Attribute:target_class+' => '',
	'Class:ClosingRule/Attribute:stimulus_code' => 'Code du stimulus',
	'Class:ClosingRule/Attribute:stimulus_code+' => 'Code du stimulus à appliquer',
	'Class:ClosingRule/Attribute:history_entry' => 'Entrée d\'historique',
	'Class:ClosingRule/Attribute:history_entry+' => 'Texte ou entrée de dictionnaire à rajouter dans l\'historique de l\'objet lors de la clôture automatique',
	'Class:ClosingRule/Attribute:status' => 'Statut',
	'Class:ClosingRule/Attribute:status+' => '',
	'Class:ClosingRule/Attribute:status/Value:active' => 'Active',
	'Class:ClosingRule/Attribute:status/Value:inactive' => 'Inactive',
	'Class:ClosingRule/Attribute:type' => 'Option appliquée',
	'Class:ClosingRule/Attribute:type+' => 'Quelle option sera utilisée au regard des champs remplis. Si les 2 options sont remplies, l\'option avancée sera appliquée',
	'Class:ClosingRule/Attribute:type/Value:simple' => 'Simple',
	'Class:ClosingRule/Attribute:type/Value:advanced' => 'Avancée',
	'Class:ClosingRule/Attribute:pre_closing_state_code' => 'Etat avant clôture',
	'Class:ClosingRule/Attribute:pre_closing_state_code+' => 'Code de l\'état dans lequel les objets de la classe choisie doivent être pour que la règle leur soit appliquée',
	'Class:ClosingRule/Attribute:date_to_check_att' => 'Date à contrôler',
	'Class:ClosingRule/Attribute:date_to_check_att+' => 'Code atribut de la date à contrôler',
	'Class:ClosingRule/Attribute:autoclose_delay' => 'Délai de clôture',
	'Class:ClosingRule/Attribute:autoclose_delay+' => 'Délai en jours avant l\'application du stimulus sur les objets (par rapport à la date de contrôle)',
	'Class:ClosingRule/Attribute:oql_scope' => 'Périmêtre OQL',
	'Class:ClosingRule/Attribute:oql_scope+' => 'Requête OQL définissant les objets concernés par cete règle (stimulus à appliquer). Note : L\'OQL sera automatiquement restreint aux états depuis lesquels le stimulus est applicable.',

	// Integrity errors
	'Class:ClosingRule/Error:ClassNotValid' => 'La classe doit faire partie du modèle de données, "%1$s" donnée',
	'Class:ClosingRule/Error:AttributeNotValid' => '"%2$s" n\'est pas un attribut valide pour la classe "%1$s"',
	'Class:ClosingRule/Error:AttributeMustBeDate' => '"%2$s" doit être un attribut de type date pour la classe "%1$s"',
	'Class:ClosingRule/Error:StateNotValid' => '"%2$s" n\'est pas un état valide pour la classe "%1$s"',
	'Class:ClosingRule/Error:StimulusNotValid' => '"%2$s" n\'est pas un stimulus valide pour la classe "%1$s"',
	'Class:ClosingRule/Error:ExistingRuleForClass' => 'Il existe déjà une règle de clôture pour la classe "%1$s"',
	'Class:ClosingRule/Error:NoOptionFilled' => 'Une des 2 options doit être remplie',
	'Class:ClosingRule/Error:OptionOneMissingField' => 'Tous les champs de l\'option 1 doivent être remplis',

	// Presentation
	'ClosingRule:general' => 'Informations générales',
	'ClosingRule:simple' => 'Remplir l\'option (simple) ...',
	'ClosingRule:advanced' => '... ou l\'option 2 (avancée)',

	// Menus
	'Menu:ClosingRule' => 'Règles de clôture',
	'Menu:ClosingRule+' => 'Règles de clôture',

	// Tabs
	'UI:AutocloseTicket:Preview' => 'Aperçu',
	'UI:AutocloseTicket:Title' => '%1$s à clôre à cet instant',
	'UI:AutocloseTicket:AdvancedTypeStatesNotice' => 'La règle étant basée sur un OQL, elle a été restreinte pour ne retourner que les objets dans les états où "%1$s" peut être appliqué (%2$s).',

	// Explanation texts
	'AutoClose:HistoryEntry:Prefix' => 'Objet clos automatiquement par la règle de clôture "%1$s" (#%2$s) en utilisant l\'option "%3$s".',
));
