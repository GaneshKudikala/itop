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
 * Module combodo-autoclose-ticket
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @author      Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */


/**
 * Class AutoCloseTicketExec
 */
class AutoCloseTicketExec implements iScheduledProcess
{
	const MODULE_CODE = 'combodo-autoclose-ticket';
	const MODULE_SETTING_ENABLED = 'enabled';
	const MODULE_SETTING_DEBUG = 'debug';
	const MODULE_SETTING_WEEKDAYS = 'week_days';
	const MODULE_SETTING_TIME = 'time';

	const DEFAULT_MODULE_SETTING_ENABLED = true;
	const DEFAULT_MODULE_SETTING_DEBUG = false;
	const DEFAULT_MODULE_SETTING_WEEKDAYS = 'monday, tuesday, wednesday, thursday, friday, saturday, sunday';
	const DEFAULT_MODULE_SETTING_TIME = '03:00';

	protected $bDebug;

	/**
	 * AutoCloseTicket constructor.
	 */
	function __construct()
	{
		$this->bDebug = (bool) MetaModel::GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_DEBUG, static::DEFAULT_MODULE_SETTING_DEBUG);
	}

	/**
	 * Gives the exact time at which the process must be run next time
	 *
	 * @return \DateTime
	 */
	public function GetNextOccurrence()
	{
		$bEnabled = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_ENABLED, static::DEFAULT_MODULE_SETTING_ENABLED);
		if (!$bEnabled)
		{
			$oRet = new DateTime('3000-01-01');
		}
		else
		{
			// 1st - Interpret the list of days as ordered numbers (monday = 1)
			//
			$aDays = $this->InterpretWeekDays();

			// 2nd - Find the next active week day
			//
			$sBackupTime = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_TIME, static::DEFAULT_MODULE_SETTING_TIME);
			if (!preg_match('/[0-2][0-9]:[0-5][0-9]/', $sBackupTime))
			{
				throw new Exception(static::MODULE_CODE.": wrong format for setting 'time' (found '$sBackupTime')");
			}
			$oNow = new DateTime();
			$iNextPos = false;
			for ($iDay = $oNow->format('N') ; $iDay <= 7 ; $iDay++)
			{
				$iNextPos = array_search($iDay, $aDays);
				if ($iNextPos !== false)
				{
					if (($iDay > $oNow->format('N')) || ($oNow->format('H:i') < $sBackupTime))
					{
						break;
					}
					$iNextPos = false; // necessary on sundays
				}
			}

			// 3rd - Compute the result
			//
			if ($iNextPos === false)
			{
				// Jump to the first day within the next week
				$iFirstDayOfWeek = $aDays[0];
				$iDayMove = $oNow->format('N') - $iFirstDayOfWeek;
				$oRet = clone $oNow;
				$oRet->modify('-'.$iDayMove.' days');
				$oRet->modify('+1 weeks');
			}
			else
			{
				$iNextDayOfWeek = $aDays[$iNextPos];
				$iMove = $iNextDayOfWeek - $oNow->format('N');
				$oRet = clone $oNow;
				$oRet->modify('+'.$iMove.' days');
			}
			list($sHours, $sMinutes) = explode(':', $sBackupTime);
			$oRet->setTime((int)$sHours, (int) $sMinutes);
		}
		return $oRet;
	}

	/**
	 * @inheritdoc
	 */
	public function Process($iTimeLimit)
	{
		$aReport = array(
			'reached_deadline' => 0,
			'closed' => array(),
			'not_closed' => array(),
		);

		CMDBObject::SetTrackInfo('Automatic - Background task autoclose ticket');
		CMDBObject::SetTrackOrigin('custom-extension');

		$oRulesSearch = DBObjectSearch::FromOQL('SELECT ClosingRule WHERE status = "active"');
		$oRulesSet = new DBObjectSet($oRulesSearch);

		$this->Trace('Processing '.$oRulesSet->Count().' active closing rules...');

		/** @var ClosingRule $oRule */
		$iTotalProcessedObjectsCount = 0;
		while($oRule = $oRulesSet->Fetch())
		{
			$iRuleProcessedObjectsCount = 0;
			$this->Trace('Processing rule "'.$oRule->Get('friendlyname').'" (#'.$oRule->GetKey().')...');

			try
			{
				// Retrieving rule's params
				$sClass = $oRule->Get('target_class');
				$sStimulusCode = $oRule->Get('stimulus_code');
				$sHistoryEntry = $oRule->Get('history_entry');
				$oSearch = $oRule->GetFilter();
				$this->Trace('|- Parameters:');
				$this->Trace('|  |- Class: '.$sClass);
				$this->Trace('|  |- Stimulus code: '.$sStimulusCode);
				$this->Trace('|  |- OQL scope: '.$oSearch->ToOQL(true));

				$oSet = new DBObjectSet($oSearch);
				$this->Trace('|- Objects:');
				/** @var $oToClose DBObject */
				while ((time() < $iTimeLimit) && $oToClose = $oSet->Fetch())
				{
					// Catching exceptions so the process don't get stucked on this object
					try
					{
						$aReport['reached_deadline']++;

						$sCurrentStateCode = $oToClose->GetState();
						$oToClose->ApplyStimulus($sStimulusCode);
						$sNewStateCode = $oToClose->GetState();
						$oToClose->DBUpdate();

						$iRuleProcessedObjectsCount++;
						$iTotalProcessedObjectsCount++;

						// Stimulus was applied successfully
						if ($sCurrentStateCode !== $sNewStateCode)
						{
							// Add history entry
							// - Get right explanation
							$sEntry = Dict::Format('AutoClose:HistoryEntry:Prefix', $oRule->Get('friendlyname'), $oRule->GetKey(), $oRule->Get('type'));
							if(!empty($sHistoryEntry))
							{
								$sEntry .= ' ' . Dict::S($sHistoryEntry);
							}
							// - Create history entry
							/** @var CMDBChange $oChange */
							/** @var CMDBChangeOp $oChangeOp */
							$oChangeOp = MetaModel::NewObject('CMDBChangeOpPlugin');
							$oChange = CMDBObject::GetCurrentChange();
							$oChangeOp->Set('change', $oChange->GetKey());
							$oChangeOp->Set('objclass', get_class($oToClose));
							$oChangeOp->Set('objkey', $oToClose->GetKey());
							$oChangeOp->Set('description', $sEntry);
							$oChangeOp->DBInsert();

							$aReport['closed'][] = $oToClose->Get('friendlyname');
							$this->Trace('|  |- [OK] '.$sClass.' #'.$oToClose->GetKey());
						}
						// Stimulus was NOT applied for some reasons, but we can't get the reason here
						else
						{
							$aReport['not_closed'][] = $oToClose->Get('friendlyname');
							$this->Trace('|  |- [KO] /!\\ '.$sClass.' #'.$oToClose->GetKey().' stimulus was not applied! Is "'.$sStimulusCode.'" a valid stimulus for state "'.$sCurrentStateCode.'" ?');
						}
					} // Stimulus was NOT applied because of an exception, which is NOT normal
					catch (Exception $e)
					{
						$aReport['not_closed'][] = $oToClose->Get('friendlyname');
						$this->Trace('|  |- [KO] /!\\ '.$sClass.' #'.$oToClose->GetKey().' exception raised! Error message: '.$e->getMessage());
					}

				}
				$this->Trace('|- Processed rule "'.$oRule->Get('friendlyname').'" (#'.$oRule->GetKey().') : '.$iRuleProcessedObjectsCount.' out of '.$oSet->Count().'.');

				// Info to help understand why not all objects have been processed during this batch.
				if (time() >= $iTimeLimit)
				{
					$this->Trace('Stopped because time limit exceeded!');
				}
			}
			catch(Exception $e)
			{
				$this->Trace('Skipping rule as there was an exception! ('.$e->getMessage().')');
			}
		}

		// Report
		if($aReport['reached_deadline'] === 0)
		{
			return 'No object to process';
		}
		else
		{
			$iClosedCount = count($aReport['closed']);
			$iNotClosedCount = count($aReport['not_closed']);

			$sReport = $aReport['reached_deadline'] . " objects reached autoclose deadline";
			$sReport .= " - ".$iClosedCount." were closed";
			if($iClosedCount > 0)
			{
				$sReport .= " (".implode(", ", $aReport['closed']).")";
			}
			$sReport .= " - ".$iNotClosedCount." were not closed";
			if($iNotClosedCount > 0)
			{
				$sReport .= " (".implode(", ", $aReport['not_closed']).")";
			}
			return $sReport;
		}
	}

	/**
	 * Interpret current setting for the week days
	 *
	 * Note: This comes from itop-backup scheduled task.
	 *
	 * @returns array of int (monday = 1)
	 */
	public function InterpretWeekDays()
	{
		static $aWEEKDAYTON = array('monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7);
		$aDays = array();
		$sWeekDays = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_WEEKDAYS, static::DEFAULT_MODULE_SETTING_WEEKDAYS);
		if ($sWeekDays != '')
		{
			$aWeekDaysRaw = explode(',', $sWeekDays);
			foreach ($aWeekDaysRaw as $sWeekDay)
			{
				$sWeekDay = strtolower(trim($sWeekDay));
				if (array_key_exists($sWeekDay, $aWEEKDAYTON))
				{
					$aDays[] = $aWEEKDAYTON[$sWeekDay];
				}
				else
				{
					throw new Exception(static::MODULE_CODE.": wrong format for setting 'week_days' (found '$sWeekDay')");
				}
			}
		}
		if (count($aDays) == 0)
		{
			throw new Exception(static::MODULE_CODE.": missing setting 'week_days'");
		}
		$aDays = array_unique($aDays);
		sort($aDays);
		return $aDays;
	}

	/**
	 * Prints a $sMessage in the CRON output.
	 *
	 * @param string $sMessage
	 */
	protected function Trace($sMessage)
	{
		// In the CRON output
		if ($this->bDebug)
		{
			echo $sMessage."\n";
		}
	}
}
