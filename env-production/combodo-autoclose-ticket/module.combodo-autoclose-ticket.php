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

SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-autoclose-ticket/2.0.1',
	array(
		// Identification
		//
		'label' => 'Auto closure of Tickets',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(

		),
		'mandatory' => false,
		'visible' => true,
		'installer' => 'AutoCloseTicketInstaller',

		// Components
		//
		'datamodel' => array(
			'model.combodo-autoclose-ticket.php',
			'main.combodo-autoclose-ticket.php',
		),
		'webservice' => array(
			
		),
		'data.struct' => array(
			// add your 'structure' definition XML files here,
		),
		'data.sample' => array(
			// add your sample data XML files here,
		),
		
		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any 

		// Default settings
		//
		'settings' => array(
			// Module specific settings go here, if any
			'week_days' => 'monday, tuesday, wednesday, thursday, friday, saturday, sunday',
			'time' => '03:00',
			'enabled' => true,
			'debug' => false
		),
	)
);

if (!class_exists('AutoCloseTicketInstaller'))
{
	// Module installation handler
	//
	class AutoCloseTicketInstaller extends ModuleInstallerAPI
	{
		public static function BeforeWritingConfig(Config $oConfiguration)
		{
			// Replacing old conf parameters value to indicate that it is obsolete
			$aParamsToRemove = array('userrequest_autoclose_delay', 'incident_autoclose_delay');
			foreach($aParamsToRemove as $sParamToRemove)
			{
				$sParamCurrentValue = $oConfiguration->GetModuleSetting('combodo-autoclose-ticket', $sParamToRemove, null);
				if(!empty($sParamCurrentValue))
				{
					$oConfiguration->SetModuleSetting('combodo-autoclose-ticket', $sParamToRemove, 'No longer used, you can remove this parameter.');
				}
			}

			return $oConfiguration;
		}

		/**
		 * Handler called after the creation/update of the database schema
		 * @param $oConfiguration Config The new configuration of the application
		 * @param $sPreviousVersion string PRevious version number of the module (empty string in case of first install)
		 * @param $sCurrentVersion string Current version number of the module
		 */
		public static function AfterDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if (version_compare($sPreviousVersion, '2.0.0', '<'))
			{
				SetupPage::log_info("|- Upgrading combodo-autoclose-ticket from '$sPreviousVersion' to '$sCurrentVersion'. Starting with 2.0.0, the extension uses ClosingRule objects instead of the configuration file parameters, to ensure continuity corresponding objects will created into the DB...");

				if(class_exists('UserRequest'))
				{
					// Mandatory check to avoid duplicates as CheckToWrite is disabled during setup
					$oSearch = DBObjectSearch::FromOQL('SELECT ClosingRule WHERE target_class = "UserRequest"');
					$oSet = new DBObjectSet($oSearch);
					if($oSet->Count() === 0)
					{
						try
						{
							$sDelay = MetaModel::GetConfig()->GetModuleSetting('combodo-autoclose-ticket', 'userrequest_autoclose_delay', '7');
							/** @var ClosingRule $oRule */
							$oRule = MetaModel::NewObject('ClosingRule');
							$oRule->Set('name', 'Close UserRequest after '.$sDelay.' days');
							$oRule->Set('target_class', 'UserRequest');
							$oRule->Set('stimulus_code', 'ev_close');
							$oRule->Set('pre_closing_state_code', 'resolved');
							$oRule->Set('date_to_check_att', 'resolution_date');
							$oRule->Set('autoclose_delay', $sDelay);
							$oRule->DBWrite();
							SetupPage::log_info("|  |- Closing rule for UserRequest created.");
						}
						catch(Exception $e)
						{
							SetupPage::log_info("|  |- Could not create a default ClosingRule for UserRequest objects. Is your lifecycle different from the standard? (Error: ".$e->getMessage().")");
						}
					}
				}

				if(class_exists('Incident'))
				{
					// Mandatory check to avoid duplicates as CheckToWrite is disabled during setup
					$oSearch = DBObjectSearch::FromOQL('SELECT ClosingRule WHERE target_class = "Incident"');
					$oSet = new DBObjectSet($oSearch);
					if($oSet->Count() === 0)
					{
						try
						{
							$sDelay = MetaModel::GetConfig()->GetModuleSetting('combodo-autoclose-ticket',
								'incident_autoclose_delay', '7');
							/** @var ClosingRule $oRule */
							$oRule = MetaModel::NewObject('ClosingRule');
							$oRule->Set('name', 'Close Incident after '.$sDelay.' days');
							$oRule->Set('target_class', 'Incident');
							$oRule->Set('stimulus_code', 'ev_close');
							$oRule->Set('pre_closing_state_code', 'resolved');
							$oRule->Set('date_to_check_att', 'resolution_date');
							$oRule->Set('autoclose_delay', $sDelay);
							$oRule->DBWrite();
							SetupPage::log_info("|  |- Closing rule for Incident created.");
						} catch (Exception $e)
						{
							SetupPage::log_info("|  |- Could not create a default ClosingRule for Incident objects. Is your lifecycle different from the standard?");
						}
					}
				}

				SetupPage::log_info("|- Removing previously scheduled autoclose tasks.");
				try
				{
					$iDeletedTasks = 0;
					$oSearch = DBObjectSearch::FromOQL("SELECT BackgroundTask WHERE class_name = 'AutoCloseTicket'");
					$oSet = new DBObjectSet($oSearch);
					while($oTask = $oSet->Fetch())
					{
						$oTask->DBDelete();
						$iDeletedTasks++;
					}

					SetupPage::log_info("|  |- Deleted $iDeletedTasks tasks.");
				}
				catch(Exception $e)
				{
					SetupPage::log_info("|  |- Could not delete tasks: ".$e->getMessage());
				}
			}
		}
	}
}
