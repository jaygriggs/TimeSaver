<?php
// $Id: timesaver_config.php,v 1.2 2010/06/09 14:10:07 Exp $

/**
 * @file
 * Timesaver configuration items file.
 *
 * These are simple configuration items used by Timesaver and/or the Timesaver report generator.
 * It is not advisable to change anything in this file other than the $_timesaver_LANG_* items
 */
global $_TIMESAVER_CONF, $_TIMESAVER_LANG_REPORT_COLUMNS, $_TIMESAVER_LANG_REPORT_FREE_FORM_COLUMNS;
$_TIMESAVER_CONF=array();

$_TIMESAVER_CONF['table_class_array']=array(
    'table_timesheet_entry',
    'view_timesaver_data',
    'table_timesaver_locked_timesheets'
);

$_TIMESAVER_CONF['day_offsets']=array(
    '1' => t('Monday'),
    '2' => t('Tuesday'),
    '3' => t('Wednesday'),
    '4' => t('Thursday'),
    '5' => t('Friday'),
    '6' => t('Saturday'),
	'0' => t('Sunday'),
);

$_TIMESAVER_CONF['report_columns']=array( 'SPACER',
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
    'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
    'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ'
);




 
$_TIMESAVER_LANG_REPORT_COLUMNS=array(
    'task_number'           =>  t('Task #'),
    'timesaver_activity_id' =>  t('Activity'),
    'project_number'        =>  t('Project #'),
	'regMilitary'           =>  t(''),
	'regHourSelect'         =>  t('TimeIn'),
	'regMinSelect'          =>  t(''),
	'regAMSelect'           =>  t(''),
    'statHourSelect'        =>  t('TimeOut'),
	'statMinSelect'         =>  t(''),
	'statAMSelect'          =>  t(''),
    'stat_time'             =>  t('Hours BL'),
	'vacationHourSelect'    =>  t('TimeIn'),
	'vacationMinSelect'     =>  t(''),
	'vacationAMSelect'      =>  t(''),
	'sickHourSelect'        =>  t('TimeOut'),
	'sickMinSelect'         =>  t(''),
	'sickAMSelect'          =>  t(''),
    'sick_time'             =>  t('Hours AL'),
    'total_reg_hours'       =>  t('Total Hours'),
	'evening_hours'         =>  t('Vacation Time'),
    'other'                 =>  t('Paid Sick Time'),
    'comment'               =>  t('Comment')
	
);



$_TIMESAVER_LANG_REPORT_FREE_FORM_COLUMNS=array(
    'thedate'               =>  t('Date'),
    'fullname'              =>  t('Full Name'),
    'timesaver_activity_id' =>  t('Activity'),
    'project_number'        =>  t('Project #'),
    'project_id'            =>  t('Project'),
    'regHourSelect'         =>  t('TimeInHour BL'),
	'regMinSelect'          =>  t('TimeInMin BL'),
	'regAMSelect'           =>  t('TimeInAM BL'),
    'statHourSelect'        =>  t('TimeOutHour BL'),
	'statMinSelect'         =>  t('TimeOutMin BL'),
	'statAMSelect'          =>  t('TimeOutAM BL'),
    'stat_time'             =>  t('Hours BL'),
    'sick_time'             =>  t('Hours AL'),
    'evening_hours'         =>  t('Vacation Time'),
    'other'                 =>  t('Paid Sick Time'),
    'total_reg_hours'       =>  t('Total HRS.'),
    'comment'               =>  t('Comment'),
);
