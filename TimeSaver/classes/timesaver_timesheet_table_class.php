<?php
/**
 * @file
 * Timesheet table class definition file.
 *
 * Extends the base table class to provide APIs to work with the timesheet table in Timesaver
 */
include_once('base_table_class.php');

class TABLE_timesheet_entry extends TABLE {
  var $fulltablename;     // table name
  var $tablename;         // alias name used when accessing this class so not to use index identifiers
  var $fieldlist;         // list of fields in this table
  var $fieldvals;         // list of values for fields
  var $number_of_columns;   // holds the number of columns dynamically determined in this variable
  var $result_set;         // holds a result set
  var $rows_returned;      //holds the number of rows from the result set

  //constructor
  function TABLE_timesheet_entry() {
    $this->tablename       = 'timesaver_timesheet_entry'; //for use in abstraction.  Shows up as the object array's key.
    $this->rows_per_page   = 10;
    $this->result_set       = NULL;
    $this->rows_returned    = 0;

    $this->fieldlist = array('id','uid','timesaver_activity_id','task_id','project_id','regular_time',
        'time_1_5','time_2_0','evening_hours','standby','adjustment','stat_time','vacation_time_used','floater','sick_time',
        'bereavement','jury_duty','unpaid_hrs','other','comment','datestamp','locked','approved','rejected','rejected_comment','modified_by_uid','rejected_by_uid','ack_modified',
		'regHourSelect','regMinSelect','regAMSelect','regMilitary','statHourSelect','statMinSelect','statAMSelect','statMilitary','vacationHourSelect',
        'vacationMinSelect','vacationAMSelect','vacationMilitary','sickHourSelect','sickMinSelect','sickAMSelect','sickMilitary');

    $this->number_of_columns=count($this->fieldlist);
  }

  //based on id, datestamp and uid, we can determine if this is all rows, 1 specific row, or many rows for 1 date for 1 user
  function get_timesheet_rows($datestamp=NULL, $uid=NULL, $id=0, $idlist='', $show_approved=FALSE, $show_only_approved=FALSE, $show_only_rejected=FALSE) {
    global $_TABLES;

    $lowerextent=$datestamp-14400;
    $upperextent=$datestamp+14400;

    if ($id!=0 || $id!=NULL) {//specific row we're after
      $sql="SELECT * FROM {timesaver_timesheet_entry} WHERE id=%d";
      $countsql  ="SELECT count(a.id) FROM {timesaver_timesheet_entry} a  ";
      $this->result_set=db_query($sql, $id);
      $countres=db_query($countsql);

    }
    elseif ( ($datestamp!=NULL || $datestamp!=0) && ($uid!=NULL || $uid!=0)) {//after a user and specific day (may return multiple rows
      $sql="SELECT * FROM {timesaver_timesheet_entry} WHERE datestamp<=%d AND  datestamp>=%d AND uid=%d  ";
      $sql .=" ORDER BY ID ASC ";

      $countsql="SELECT count(id) FROM {timesaver_timesheet_entry} WHERE datestamp<=%d AND  datestamp>=%d AND uid=%d  ";
      $this->result_set=db_query($sql, $upperextent, $lowerextent, $uid);
      $countres=db_query($countsql, $upperextent, $lowerextent, $uid);

    }
    elseif (($datestamp==NULL || $datestamp==0) && ($uid!=NULL || $uid!=0)) {//after all the records for a user
      $sql="SELECT * FROM {timesaver_timesheet_entry} WHERE uid=%d ";
      $sql .=" ORDER BY ID ASC ";

      $countsql="SELECT count(id) FROM {timesaver_timesheet_entry} WHERE uid=%d";
      $this->result_set=db_query($sql, $uid);
      $countres=db_query($countsql, $uid);

    }
    elseif ($idlist!='') {//get a row whose user ids are in this list
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
      $sql  ="SELECT a.*,b.region,b.tech_number FROM {timesaver_timesheet_entry} a  ";
      $sql .="LEFT OUTER JOIN {timesaver_extra_user_data} b ON a.uid=b.uid ";
      $sql .="WHERE a.uid in ($idlist) $approved_clause ORDER BY a.UID ASC ";

      $countsql  ="SELECT count(a.id) FROM {timesaver_timesheet_entry} a  ";
      $countsql .="LEFT OUTER JOIN {timesaver_extra_user_data} b ON a.uid=b.uid ";
      $countsql .="WHERE a.uid in ($idlist) {$approved_clause} ORDER BY a.UID ASC ";
      $this->result_set=db_query($sql);
      $countres=db_query($countsql);
    }//end elseif

    $this->rows_returned=db_result($countres);
    $this->generate_table_header();
  }

  function commit_data($approval_uid, $for_which_uid) {
    global $_TABLES, $user;
    $allZeroFlag=TRUE;
    if ($approval_uid!=$for_which_uid && $approval_uid!=0) {//if the approving person is NOT the person this entry is for
      $this->fieldvals['uid']=$for_which_uid;
      $this->fieldvals['modified_by_uid']=$approval_uid;
      $is_approval=1;
    }
    else {
      $this->fieldvals['uid']=$user->uid;
      $this->fieldvals['modified_by_uid']=$user->uid;
      $is_approval=0;
    }

    $fieldlist=implode(",", $this->fieldlist);
    $fieldlist="," . $fieldlist;
    $fieldlist=str_replace(",id,", "", $fieldlist);
    $arrFields=explode(",", $fieldlist);
    if ($this->fieldvals['id']==NULL || $this->fieldvals['id']==0 ) {//insert
      $sql  ="INSERT INTO {timesaver_timesheet_entry} ({$fieldlist}) VALUES (";
      $insertFields='';
      foreach ($arrFields as $field) {
        $field_value=check_plain($this->fieldvals[$field]);

        //@TODO: is this required?  the addslashes that is
        $field_value=addslashes($field_value);
        if ($field_value=='' && $field!='comment') {
          $field_value=0;
        }
        if ( ($field_value!='' && $field!='uid' && $field!='datestamp' && $field!='modified_by_uid') && ( ($field_value!='0' ) && ($field!='timesaver_activity_id' || $field!='task_id' || $field!='project_id') ) ) $allZeroFlag=FALSE;
        if ($insertFields!='') $insertFields.=',';
        if ($field=='comment') {
          if ($field_value[0] == '@' || $field_value[0] == '=') {
            $field_value = ' ' . $field_value;
          }
        }
        $insertFields .="'{$field_value}'";
      }
      $sql.=$insertFields;
      $sql .=")";
    }
    else {//update
      //lets see if its locked first.  if it is, ignore it!

      $sql="SELECT locked,approved FROM {timesaver_timesheet_entry} WHERE id=%d ";
      $res=db_query($sql, $this->fieldvals['id']);
      list($islocked, $isapproved)=db_fetch_array($res);

      if (intval($islocked)==0 && intval($isapproved)==0) {
        $sql  ="UPDATE {timesaver_timesheet_entry} SET ";
        $update_fields='';
        foreach ($arrFields as $field) {
          $field_value=$this->fieldvals[$field];
          $field_value=addslashes($field_value);
          if ($field_value=='' && $field!='comment' && $field!='rejected_comment') {
            $field_value=0;
          }
          if ($field!='rejected_comment') {
            if ($field_value!='' && $field!='uid' && $field!='datestamp') $allZeroFlag=FALSE;
            if ($update_fields!='') $update_fields.=',';
            if ($field=='comment') {
              if ($field_value[0] == '@' || $field_value[0] == '=') {
                $field_value = ' ' . $field_value;
              }
            }
            $update_fields .="{$field}='{$field_value}'";
          }
        }
        $sql.=$update_fields;
        $sql .=" WHERE id={$this->fieldvals['id']}";
      }
      else {
        $sql="SELECT id FROM {timesaver_timesheet_entry} WHERE id={$this->fieldvals['id']}";
      }
    }
    if (!$allZeroFlag) {
      db_query($sql);
    }
    return TRUE;
  }//end commit_data

  function delete_entry($id) {
    $id=intval($id);
    $sql="SELECT approved, rejected FROM {timesaver_timesheet_entry} WHERE id=%d ";
    $res=db_query($sql, $id);
    list($isApproved, $isRejected)=db_fetch_array($res);
    //        $isApproved=DB_getItem(timesaver_timesheet_entry,"approved","id={$id}");
    //        $isRejected=DB_getItem(timesaver_timesheet_entry,"rejected","id={$id}");
    if ($isApproved!=1 && $isRejected!=1) {
      $sql  ="DELETE FROM  {timesaver_timesheet_entry} WHERE id=%d ";
      db_query($sql, $id);
      return TRUE;
    }
    else {
      return FALSE;
    }

  }

  function lock_timesheet_entries($datestamp, $uid=0) {
    global $user;
    if ($uid==0) $uid=$user->uid;
    $datestamp=intval($datestamp);

    $sql="UPDATE {timesaver_timesheet_entry} SET locked=1 WHERE datestamp=%d AND uid=%d";
    db_query($sql, $datestamp, $uid);
  }

  function unlock_timesheet_entries($datestamp, $uid=0) {
    global $user;
    if ($uid==0) $uid=$user->uid;
    $datestamp=intval($datestamp);

    $sql="SELECT approved FROM {timesaver_timesheet_entry} WHERE datestamp=%d AND uid=%d ORDER BY id ASC LIMIT 1";
    $res=db_query($sql, $datestamp, $uid);
    list($isapproved)=DB_fetch_array($res);
    if (intval($isapproved)==0) {
      $sql="UPDATE {timesaver_timesheet_entry} SET locked=0 WHERE datestamp=%d AND uid=%d";
      db_query($sql, $datestamp, $uid);
    }
  }

  function approve_single_item($id) {
    $id=intval($id);
    $sql="UPDATE {timesaver_timesheet_entry} set approved=1, rejected=0 where id=%d";
    $res=db_query($sql, $id);
    return TRUE;
  }

  function unapprove_single_item($id) {
    $id=intval($id);
    $sql="UPDATE {timesaver_timesheet_entry} set approved=0, rejected=0 where id=%d";
    $res=db_query($sql, $id);
    return TRUE;
  }

  function approve_range($uid, $start, $end) {
    if ($start!=0 && $start!='' && $end!=0 && $end!='') {
      $sql="UPDATE {timesaver_timesheet_entry} set approved=1, rejected=0 where uid=%d and datestamp>=%d and datestamp<=%d";
      $res=db_query($sql, $uid, $start, $end);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  function unapprove_range($uid, $start, $end) {
    if ($start!=0 && $start!='' && $end!=0 && $end!='') {
      $sql="UPDATE {timesaver_timesheet_entry} set approved=0, rejected=0 where uid=%d and datestamp>=%d and datestamp<=%d";
      $res=db_query($sql, $uid, $start, $end);
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  function reject_single_item($id, $comment) {
    $sql="UPDATE {timesaver_timesheet_entry} set rejected=1, approved=0, rejected_comment='%s' where id=%d";
    $res=db_query($sql, $comment, $id);
    return TRUE;
  }

  function unreject_single_item($id) {
    $sql="UPDATE {timesaver_timesheet_entry} set rejected=0 where id=%d";
    $res=db_query($sql, $id);
    return TRUE;
  }

  function get_date_stamp_from_id($id) {
    $sql="SELECT datestamp FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    list($datestamp)=db_fetch_array($res);
    return $datestamp;
  }

  function get_uid_from_id($id) {
    $sql="SELECT uid FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    list($uid)=db_fetch_array($res);
    return $uid;

  }

  function get_rejection_reason_by_id($id) {
    $sql="SELECT rejected_comment FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    list($reason)=db_fetch_array($res);
    return $reason;

  }

  function get_total_hrs_from_id($id) {
    $id=intval($id);
    $sql  ="SELECT (regular_time+time_1_5+time_2_0+vacation_time_used+stat_time+floater+sick_time+bereavement+jury_duty+adjustment) as tot ";    //removed +evening_hours+unpaid_hrs+other from calculation july 31/08 ED
    $sql .=" FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    $A=db_fetch_array($res);
    $tot=$A['tot'];
    return $tot;
  }

  function get_ot_hrs_from_id($id) {
    $id=intval($id);
    $sql  ="SELECT (time_1_5+time_2_0) as tot ";
    $sql .=" FROM {timesaver_timesheet_entry} WHERE id=%d";
    $res=db_query($sql, $id);
    $A=db_fetch_array($res);
    $tot=$A['tot'];
    return $tot;
  }

  function get_total_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    $start_date_stamp=$start_date_stamp-3600;
    $end_date_stamp=$end_date_stamp+3600;

    $sql  ="SELECT sum(regular_time+time_1_5+time_2_0+evening_hours+stat_time+vacation_time_used+floater+sick_time+bereavement+jury_duty+unpaid_hrs+other+adjustment) as tot ";
    $sql .=" FROM {timesaver_timesheet_entry} WHERE uid=%d AND datestamp>=%d AND datestamp<=%d ";
    $res=db_query($sql, $uid, $start_date_stamp, $end_date_stamp);
    $A=db_fetch_array($res);
    $tot=$A['tot'];
    return $tot;
  }

  function get_regular_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    $sql  ="SELECT sum(regular_time+evening_hours+stat_time+vacation_time_used+floater+sick_time+bereavement+jury_duty+unpaid_hrs+other+adjustment) as tot ";
    $sql .=" FROM {timesaver_timesheet_entry} WHERE uid=%d AND datestamp>=%d AND datestamp<=%d";
    $res=db_query($sql, $uid, $start_date_stamp, $end_date_stamp);
    $A=db_fetch_array($res);
    $tot=$A['tot'];
    return $tot;
  }

  function get_ot_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    $sql  ="SELECT sum(time_1_5+time_2_0) as tot ";
    $sql .=" FROM {timesaver_timesheet_entry} WHERE uid=%d AND datestamp>=%d AND datestamp<=%d";
    $res=db_query($sql, $uid, $start_date_stamp, $end_date_stamp);
    $A=db_fetch_array($res);
    $tot=$A['tot'];
    return $tot;
  }


  function select_by_project_hours_by_user($uid, $start_stamp, $end_stamp) {
    $sql  ="SELECT project_id,sum(regular_time+time_1_5+time_2_0+evening_hours+stat_time+vacation_time_used+floater+sick_time+bereavement+jury_duty+unpaid_hrs+other+adjustment) as tot  ";
    $sql .=" FROM {timesaver_timesheet_entry} WHERE uid=%d AND datestamp>=%d AND datestamp<=%d ";
    $sql .="GROUP BY project_id ";
    $res=db_query($sql, $uid, $start_stamp, $end_stamp);
    $retarray=array();
    while ($A=db_fetch_array($res)) {
      $projnumber=listkeeper_value(variable_get('timesaver_list_id_projects', 0), $A['project_id'],1);
      if ($projnumber=='') $projnumber='Misc. ';
      //if we have an identical key.. don't overwrite it!  tally it up
      if (array_key_exists($projnumber, $retarray)) {
        $retarray["$projnumber"]+= $A['tot']  ;
      }else {
        $retarray["$projnumber"]= $A['tot']  ;
      }
      //com_errorlog("$projnumber {$A['tot']}");
    }
    return $retarray;
  }

  function get_rejected_items($uid) {
    $sql ="SELECT id FROM {timesaver_timesheet_entry} WHERE uid=%d AND rejected=1";
    $res=db_query($sql, $uid);
    $retarray=array();
    for ($cntr=0; $cntr<DB_numRows($res); $cntr++) {
      $A=db_fetch_array($res);
      $retarray[$cntr]=$A['id'];
    }
    return $retarray;
  }

  function get_items_submitted_by_someone_else($uid) {
    global $CONF_Timesaver;

    $sql ="SELECT id FROM {timesaver_timesheet_entry} WHERE modified_by_uid<>%d AND uid=%d AND modified_by_uid<>0 AND ack_modified=0";
    $res=db_query($sql, $uid, $uid);
    $retarray=array();
    for ($cntr=0; $cntr<DB_numRows($res); $cntr++) {
      $A=db_fetch_array($res);
      $retarray[$cntr]=$A['id'];
    }
    return $retarray;
  }

  //set the ack_modified flag to 1 to signify that the end user knows that their
  //entry has been altered by someone
  function set_acknowledged_modified($start_stamp, $end_stamp, $uid) {
    $sql="UPDATE {timesaver_timesheet_entry} SET ack_modified=1 WHERE uid=%d AND (datestamp>=%d OR datestamp>=(%d-3600)) AND (datestamp<=%d OR datestamp<=(%d+3600) )";
    db_query($sql, $uid, $start_stamp, $start_stamp, $end_stamp, $end_stamp);
    return TRUE;
  }

  // updates a single field in a single record with specified value
  // written for use with the adjustments
  function set_specific_column_value($id, $field, $value) {
    $sql = "UPDATE {timesaver_timesheet_entry} SET `%s`='%s' WHERE `id`='%d'";
    db_query($sql, $field, $value, $id);
  }

  
}
