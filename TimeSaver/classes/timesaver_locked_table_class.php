<?php
// $Id: timesaver_locked_table_class.php,v 1.9 Exp $
/**
 * @file
 * Locked table class definition file.
 *
 * Extends the base table class to provide APIs to work with the lock table in Timesaver
 */
include_once('base_table_class.php');

class TABLE_timesaver_locked_timesheets extends TABLE {
  var $fulltablename;     // table name
  var $tablename;         // alias name used when accessing this class so not to use index identifiers
  var $fieldlist;         // list of fields in this table
  var $fieldvals;         // list of values for fields
  var $number_of_columns;   // holds the number of columns dynamically determined in this variable
  var $result_set;         // holds a result set
  var $rows_returned;      //holds the number of rows from the result set

  //constructor
  function TABLE_timesaver_locked_timesheets() {
    $this->tablename       = 'timesaver_locked_timesheets';  //for use in abstraction.  Shows up as the object array's key.
    $this->rows_per_page   = 10;
    $this->result_set       = NULL;
    $this->rows_returned    = 0;
    $this->fieldlist = array('uid','startdate','enddate');
    $this->number_of_columns=count($this->fieldlist);
  }

  function lock_timesheet($start_date_stamp, $end_date_stamp, $uid) {
    $this->unlock_timesheet($start_date_stamp, $end_date_stamp, $uid);
    $sql="INSERT INTO {timesaver_locked_timesheets}  (uid, startdate, enddate) values (%d,'%s','%s')";
    db_query($sql, $uid, $start_date_stamp, $end_date_stamp);
    return true;
  }

  function unlock_timesheet($start_date_stamp, $end_date_stamp, $uid) {
    $locked_ranges = array();
    $error_margin = 4 * 3600; // allow for 8 hour deviation (4 both ways), should be ok since all times should be pegged to midnight anyways
    $sql = "SELECT `startdate`, `enddate` FROM {timesaver_locked_timesheets} WHERE uid=%d";
    $result = db_query($sql, $uid);
    while ($data = DB_fetch_array($result)) {
      if ((($start_date_stamp >= $data['startdate'] - $error_margin) && ($start_date_stamp <= $data['startdate'] + $error_margin)) && (($end_date_stamp >= $data['enddate'] - $error_margin) && ($end_date_stamp <= $data['enddate'] + $error_margin))) {
        $locked_ranges[] = array('startdate'=>$data['startdate'], 'enddate'=>$data['enddate']);
      }
    }
    $args_array=array();
    if ($locked_ranges) {
      $sql = "DELETE FROM {timesaver_locked_timesheets} WHERE uid=%d AND (";
      $args_array[]=$uid;
      foreach ($locked_ranges as $range) {
        $sql .= "(startdate=%d AND enddate=%d) OR ";
        $args_array[]=$range['startdate'];
        $args_array[]=$range['enddate'];
      }
      $sql = substr($sql, 0, -4).")";  //strip off the last OR
    }
    db_query($sql, $args_array);
    return true;
  }


  function determine_if_item_is_in_lock_range($id) {
    global $_TABLES;
    $sql="SELECT datestamp FROM {timesaver_timesheet_entry} WHERE id={$id}";
    $res=db_query($sql);
    $datestamp=floatval(db_result($res));

    $sql="SELECT uid FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    $uid=intval(db_result($res));

    $sql="SELECT count(uid) FROM {timesaver_locked_timesheets} WHERE uid=%d AND startdate<=%d AND enddate>=%d ";
    $res=db_query($sql, $uid, $datestamp, $datestamp);
    $nrows=db_result($res);
    if ($nrows>0) {
      return true;
    }
    else {
      return false;
    }

  }

  function determine_if_item_is_in_lock_range_by_date_stamp($datestamp, $uid) {
    $sql="SELECT count(uid) FROM {timesaver_locked_timesheets} WHERE uid=%d AND startdate<=%d AND enddate>=%d ";
    $res=db_query($sql, $uid, $datestamp, $datestamp);
    $nrows=db_result($res);
    if ($nrows>0) {
      return true;
    }
    else {
      return false;
    }
  }

}

?>