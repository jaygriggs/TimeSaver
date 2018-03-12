<?php
// $Id: view_timesaver_data_class.php,v 1.9 Exp $
/**
 * @file
 * View table class definition file.
 *
 * Extends the base table class to provide APIs to work as a View in Timesaver
 */
/**
 * @file
 * Timesaver View class to facilitate ORM like requests across multiple tables.
 *
 * The view class extends the base table class and provides views of data
 * that are not single table specific
 */

include_once('base_table_class.php');

class view_timesaver_data extends TABLE{
  var $fulltablename;     // table name
  var $tablename;         // alias name used when accessing this class so not to use index identifiers
  var $fieldlist;         // list of fields in this table
  var $fieldvals;         // list of values for fields
  var $number_of_columns;   // holds the number of columns dynamically determined in this variable
  var $result_set;         // holds a result set
  var $rows_returned;      //holds the number of rows from the result set

  //constructor
  function view_timesaver_data() {
    global $_TABLES;
    $this->fulltablename   = '';
    $this->tablename       = 'VIEW_timesaver_data';
    $this->rows_per_page   = 10;
    $this->result_set       = NULL;
    $this->rows_returned    = 0;

    $this->fieldlist = array('id','uid','timesaver_activity_id','project_id','regHourSelect','regMinSelect','regAMSelect',
	'regMilitary','regular_time','statHourSelect','statMinSelect','statAMSelect','statMilitary','stat_time','BeforeLunchHours',
        'standby','vacationHourSelect','vacationMinSelect','vacationAMSelect','vacationMilitary','vacation_time_used',
		'sickHourSelect','sickMinSelect','sickAMSelect','sickMilitary','sick_time','evening_hours','AfterLunchHours',
        'other','comment','datestamp','locked','approved','rejected',
        'rejected_comment','modified_by_uid','rejected_by_uid',
        'tech_number','region','fullname','maxuserdt', 'minuserdt',
        'locked_by_supervisor');

    $this->number_of_columns=count($this->fieldlist);
  }

  //based on id, datestamp and uid, we can determine if this is all rows, 1 specific row, or many rows for 1 date for 1 user
  function get_timesheet_approval_rows($idlist='', $show_approved=false, $show_only_approved=false, $show_only_rejected=false) {
    //get a row whose user ids are in this list
    $approved_clause='';
    if ($show_approved) {
      $approved_clause=' AND (a.approved=1 or a.approved=0) ';
    }
    if ($show_only_approved) {
      $approved_clause=' AND (a.approved=1) ';
    }
    if ($show_only_rejected) {
      $approved_clause=' AND (a.rejected=1) ';
    }
    $sql  ="SELECT ";

     $sql .="a.id,a.uid,a.timesaver_activity_id,a.project_id,a.regHourSelect,a.regMinSelect,a.regAMSelect,a.regMilitary,a.regular_time,";
    $sql .="a.time_1_5,a.time_2_0,a.evening_hours,a.standby,a.vacation_time_used,a.sick_time,";
    $sql .="a.bereavement,a.jury_duty,a.unpaid_hrs,a.other,a.comment,a.datestamp,a.locked,a.approved,a.rejected,";
    $sql .="b.region, b.tech_number,  ";
    $sql .="b.emp_name as fullname, max(d.datestamp) as maxuserdt, min(d.datestamp) as minuserdt, ";
    $sql .="case when e.startdate is null then 'FALSE' else 'TRUE' end as locked_by_supervisor ";
    $sql .="FROM {timesaver_timesheet_entry} a ";
    $sql .="LEFT OUTER JOIN {timesaver_extra_user_data} b ON a.uid = b.uid ";
    $sql .="INNER JOIN {timesaver_timesheet_entry} d on a.uid=d.uid ";
    $sql .="LEFT OUTER JOIN {timesaver_locked_timesheets} e on (a.uid=e.uid and a.datestamp>=e.startdate and a.datestamp<=e.enddate) ";
    $sql .="WHERE a.uid ";
    $sql .="IN ({$idlist})  {$approved_clause} ";
    $sql .="GROUP BY ";
    $sql .="a.id,a.uid,a.timesaver_activity_id,a.project_id,a.regHourSelect,a.regMinSelect,a.regAMSelect,a.regMilitary,a.regular_time,";
    $sql .="a.time_1_5,a.time_2_0,a.evening_hours,a.standby,a.vacation_time_used,a.sick_time,";
    $sql .="a.bereavement,a.jury_duty,a.unpaid_hrs,a.other,a.comment,a.datestamp,a.locked,a.approved,a.rejected,";
    $sql .="b.region, b.tech_number, c.fullname ";
    $sql .="ORDER BY a.UID ASC , a.datestamp ASC  ";
    $this->result_set=db_query($sql);
    $this->generate_table_header();
  }

  //returns an array denoting number of items in [0] and then number of approved items in [1] and number of rejected items in [2]
  function check_for_all_approved_in_range($uid,$start,$end) {
    $start=$start-3600;
    $end=$end+3600;

    $sql  ="SELECT count(id) as cnt from {timesaver_timesheet_entry} a ";
    $sql .="WHERE uid=%d and datestamp>=%d and datestamp<=%d";
    $res=db_query($sql,$uid,$start,$end);
    $A=db_fetch_array($res);
    $count=$A['cnt'];

    $sql  ="SELECT count(id) as cnt from {timesaver_timesheet_entry} a ";
    $sql .="WHERE uid=%d and datestamp>=%d and datestamp<=%d and approved=1";
    $res=db_query($sql,$uid,$start,$end);
    $A=db_fetch_array($res);
    $appcount=$A['cnt'];

    $sql  ="SELECT count(id) as cnt from {timesaver_timesheet_entry} a ";
    $sql .="WHERE uid=%d and datestamp>=%d and datestamp<=%d and rejected=1";
    $res=db_query($sql,$uid,$start,$end);
    $A=db_fetch_array($res);
    $rejcount=$A['cnt'];

    $sql  ="SELECT sum(regular_time+time_1_5+time_2_0+evening_hours+adjustment+vacation_time_used+stat_time+sick_time+bereavement+jury_duty+unpaid_hrs) as cnt from {timesaver_timesheet_entry} ";
    $sql .="WHERE uid=%d and datestamp>=%d and datestamp<=%d and approved=1";
    $res=db_query($sql,$uid,$start,$end);
    $A=db_fetch_array($res);
    $hoursapproved=floatval($A['cnt']);

    $arr= array(0=>"$count", 1=>"$appcount", 2=>"$rejcount", 3=>"$hoursapproved");
    return $arr;
  }

  function get_employee_number($uid) {
    $emp=timesaver_get_user_data($uid);
    return $emp['emp_number'];
  }

  //this method returns only the OPTION tags and the supervisors within that group
  //excludes the ROOT users explicitly
  function get_supervisors_drop_down_list($skipAdmin=false) {
    $sql  ="SELECT a.uid ";
    $sql .="FROM {users_roles} a ";
    $sql .="INNER JOIN {permission} b ON a.rid=b.rid ";
    $sql .="WHERE b.perm='Timesaver supervisor'";
    $res=db_query($sql);
    $output='';
    if (!$skipAdmin) {
      $nxdata=timesaver_get_user_data(1);
      $output .="<option value='1'>{$nxdata['emp_name']}</option>";
    }
    while ($A=db_fetch_array($res)) {
      $nxdata=timesaver_get_user_data($A['uid']);
      $output .="<option value='{$A['uid']}'>{$nxdata['emp_name']}</option>";
    }
    return $output;
  }

  function get_supervisors_uid_list($skipAdmin=false) {
    $sql  ="SELECT a.uid ";
    $sql .="FROM {users_roles} a ";
    $sql .="INNER JOIN {permission} b ON a.rid=b.rid ";
    $sql .="WHERE b.perm='Timesaver supervisor'";
    $res=db_query($sql);
    $output='';
    if (!$skipAdmin) {
      $output .="1";
    }
    while ($A=db_fetch_array($res)) {
      if ($output!='') $output .=",";
      $output .=$A['uid'];
    }
    return $output;
  }

}

