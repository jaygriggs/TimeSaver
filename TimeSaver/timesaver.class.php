<?php
// $Id: timesaver.class.php,v 1.4 2010/08/14 15:36:06 randy Exp $
/**
 * @file
 * Timesaver class file.
 *
 * This base class provides all of the base business logic for the Timesaver module
 */

include_once("classes/timesaver_timesheet_table_class.php");
include_once("classes/view_timesaver_data_class.php");
include_once("classes/timesaver_locked_table_class.php");

class Timesaver {

  var $_tables;  //this is the nifty dynamic table array
  var $_number_of_tables=0;

  //constructor
  function Timesaver() {
    global $_TIMESAVER_CONF;

    //set up our base tables API
    $this->_tables=array();
    for ($cntr=0; $cntr < count($_TIMESAVER_CONF['table_class_array']); $cntr++) {
      $this->_tables[$cntr]=new $_TIMESAVER_CONF['table_class_array'][$cntr]();
      $this->_tables[$cntr]->clear_column_values(NULL);
      $this->_tables["{$this->_tables[$cntr]->tablename}"]=&$this->_tables[$cntr]; //pointer to the table obj
    }
    $this->_number_of_tables=count($this->_tables);
    //done setting up tables.
  }//end constructor

  //generates timesheet rows based on the starting day timestamp (integer) and the number of days to show.
  //example, if you give a timestamp, and then $number_of_days_to_show=5, you will generate 5 rows of a timesheet
  //however the output will be bounded by a sunday to saturday set.  Thus you may get the display chopped into multiple segments.
  function generate_timesheet_rows($which_user, $starting_day_timestamp, $number_of_days_to_show, $TEMPLATE=NULL, $rowcounter=0, $disable=FALSE, $is_approval=FALSE, &$total_hours=0) {
    global $user;
    if ($number_of_days_to_show>30 && $TEMPLATE==NULL) {
      //$output ="<div class='cutOffAt30Message'>Cut off the number of days at 30</div>";
      $number_of_days_to_show=30;
    }
    else {
      $output='';
    }

    $which_user=intval($which_user);
    if ($which_user==0) {
      $which_user=$user->uid;
    }

    $timesheet_is_locked = $this->determine_if_item_is_in_lock_range_by_date_stamp($starting_day_timestamp, $which_user);
    $number_day_of_week=strftime("%w", $starting_day_timestamp);
    $header_display_flag=0;
    $this_day=$starting_day_timestamp;
    $day_offset=24*60*60;
    $fields_to_sum = array(
	            'placeholder' => '',
			    'placeholder' => '',
                'total' => 0,
    );
    if ($timesheet_is_locked) {
      $fields_to_sum['adjustment'] = 0;
    }
    $totals = array('week1' => $fields_to_sum, 'week2' => $fields_to_sum, 'grand' => $fields_to_sum);
    $current_week = 'week1';
    for ($cntr=0;$cntr<$number_of_days_to_show;$cntr++) {
      //first, lets convert the timestamp to a date,
      //then we re-convert to an int.  if the int is off (most likely by 3600 seconds)
      //we have a time change here.....
      $testdate=strftime("%Y/%m/%d 00:00:00", $this_day);
      $testintdate=strtotime($testdate);
      if ($testintdate==$this_day-3600) {
        $this_day=$testintdate;
      }
      elseif ($testintdate==$this_day) {
        //purposely left blank
      }
      else {
        $this_day+=3600;
      }
      if ($number_day_of_week==1 and $cntr>0) { //echo out the footer and then the header
        $totals[$current_week]['total'] = $total_hours;
        
        $totals['grand']['total'] += $total_hours;
        
        if ($timesheet_is_locked) {
          $output .=$this->generate_table_footer(TRUE, $total_hours, $ot_hours, $totals[$current_week], array(), $timesheet_is_locked);
          $output .=$this->generate_table_header(TRUE, $is_approval, $timesheet_is_locked);
        }
        else {
          $output .=$this->generate_table_footer(FALSE, $total_hours, $ot_hours, $totals[$current_week], array(), $timesheet_is_locked);
          $output .=$this->generate_table_header(FALSE, $is_approval, $timesheet_is_locked);
        }
        $current_week = 'week2';
        $total_hours=0;
      }//all common display items
      $today=strftime("%w", $this_day);
      if ($TEMPLATE==NULL) { //set up the main row to show
        $this->_tables['timesaver_timesheet_entry']->get_timesheet_rows($this_day, $which_user);
        $output .=theme('timesaver_timesheet_entry_row', $TEMPLATE,
        $this,
        $rowcounter,
        $today,
        $this_day,
        $timesheet_is_locked,
        $disable,
        $is_approval);
      }
      else {//set up the secondary row to show
        //$T->set_file (array ('row'         =>  'timesheet_entry_row_multi_tasks.thtml'  ));
        $this->_tables['timesaver_timesheet_entry']->generate_table_header();
        $output .=theme('timesaver_timesheet_entry_row_multi_tasks', $TEMPLATE,
        $this, $rowcounter,
        $today,
        $this_day,
        $timesheet_is_locked,
        $disable,
        $is_approval);
      }

      $total_hours+=$this->get_total_hrs_from_id($this->_tables['timesaver_timesheet_entry']->fieldvals['id']);
      $ot_hours+= $this->get_ot_hrs_from_id($this->_tables['timesaver_timesheet_entry']->fieldvals['id']);
      // add current row values to the sums
      $currentRow = $this->_tables['timesaver_timesheet_entry']->fieldvals;
      foreach (array_diff(array_keys($fields_to_sum), array('placeholder', 'total')) as $field) {
        $totals[$current_week][$field] += $currentRow[$field];
        $totals['grand'][$field] += $currentRow[$field];
      }
      if ($TEMPLATE==NULL) { //we're in the main body to show specific main row vs. secondary row data
        if ($this->_tables['timesaver_timesheet_entry']->rows_returned==1) {
          $rowcounter+=1;
        }
        elseif ($this->_tables['timesaver_timesheet_entry']->rows_returned>1) {//more than one task for this day here.....
          $rowcounter+=1;
          //we loop to rows_returned-1 because we've already peeled off the first row for the top display row
          for ($looprows=0;$looprows<($this->_tables['timesaver_timesheet_entry']->rows_returned-1);$looprows++) {
            $retval=$this->generate_timesheet_rows($which_user, $this_day, 1, TRUE, $rowcounter, $disabled, $is_approval, $total_hours);//timesheet_entry_row_multi_tasks.thtml
            $ot_hours += $retval[3];
            foreach (array_diff(array_keys($fields_to_sum), array('total', 'placeholder')) as $field) {
              $totals[$current_week][$field] += $retval[5][$field];
              $totals['grand'][$field] += $retval[5][$field];
            }
            $rowcounter=$retval[0];
            $output .=$retval[1];
          }
        }
        else {//nothing to display.. just carry on
          $rowcounter+=1;
        }
      }
      else {  //for all intents and purposes, we're here because we have a secondary template to show
        $rowcounter+=1;
      }
      $this_day+=$day_offset;
      $number_day_of_week++;
      if ($number_day_of_week>6) $number_day_of_week=0;
    }

    $totals[$current_week]['total'] = $total_hours;
   
    $totals['grand']['total'] += $total_hours;
   
    $retarray=array(0 => $rowcounter, 1 => "$output", 2 => "$total_hours",  4 => $totals[$current_week], 5 => $totals['grand']);
    return $retarray;
  }

  // added the isLocked param, it's the result of running determine_if_item_is_in_lock_range_by_date_stamp(), used for the adjustment field
  function generate_table_header($disable=FALSE, $is_approval=FALSE, $isLocked = FALSE) {
    $tpl=theme('timesaver_timesheet_entry_header', $disable, $is_approval, $isLocked);
    return $tpl;
  }

  // added the isLocked param, it's the result of running determine_if_item_is_in_lock_range_by_date_stamp(), used for the adjustment field
  function generate_table_footer($disable=FALSE, $total_hours=0, $ot_hours=0, $weeklytotals = array(), $grandtotals = array(), $isLocked = FALSE) {
    $tpl=theme('timesaver_timesheet_entry_footer', $disable, $isLocked, $weeklytotals, $grandtotals, $total_hours);
    return $tpl;
  }

  function generate_total_row_count($rowcount) {
    $ret='<input type="hidden" name="max_row_number" id="max_row_number" value="' . $rowcount . '">';
    $ret .='<input type="hidden" name="changes_flag" id="changes_flag" value="0">';
    return $ret;
  }

  function get_table_columnns($table) {
    return  $this->_tables['timesaver_timesheet_entry']->return_columns();
  }

  //simply pass in the current row you're looking for, and this function will set the table's values ready
  //for a commit
  function set_data_from_post($rownumber) {
    $this->_tables['timesaver_timesheet_entry']->clear_column_values(NULL);
    foreach ($this->_tables['timesaver_timesheet_entry']->fieldlist as $col) {
      $this->_tables['timesaver_timesheet_entry']->set_column_value($col, check_plain($_POST[$col . $rownumber]));
    }
  }

  function save_adjustment($id, $adjustment) {
    $this->_tables['timesaver_timesheet_entry']->set_specific_column_value($id, 'adjustment', $adjustment);
  }

  //we're assuming that the data in the row is sound.. commit that data to the table handler
  function commit_data($approval_uid, $for_which_uid) {
    $ret=$this->_tables['timesaver_timesheet_entry']->commit_data($approval_uid, $for_which_uid);
    return $ret;
  }

  //using $list, we will delete these IDs
  function delete_entries($list) {
    $arr=explode(",", $list);
    $retval=TRUE;
    foreach ($arr as $id) {
      $ret=$this->_tables['timesaver_timesheet_entry']->delete_entry($id);
      $retval=$retval&$ret;
    }
    return $retval;
  }

  function lock_timesheet_entries($datestamp, $uid=0) {
    $uid=intval($uid);
    $this->_tables['timesaver_timesheet_entry']->lock_timesheet_entries($datestamp, $uid);
  }

  function unlock_timesheet_entries($datestamp, $uid=0) {
    $uid=intval($uid);
    $this->_tables['timesaver_timesheet_entry']->unlock_timesheet_entries($datestamp, $uid);
  }

  //based on the passed in UID, we need to determine which users this person can approve timesheets for
  //this will generate an option list without the SELECT tags
  //Timesaver admins see all users
  function get_option_list_of_assigned_employees($supervisor_uid, $suppress_view_all=FALSE) {
    $suppress_view_all=TRUE;
    $thisuid=intval($supervisor_uid);
    $output="";
    if (user_access('administer Timesaver')) {   //show all users for admins
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 1, '');
    }
    else {
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 1, '', '1:' . $thisuid);
    }
    if (!$suppress_view_all) {
      $output .='<option value=""></option><option value="0">View All</option>';
    }
    else {
      $output .='<option value="">' . t('Select User') . '</option>';
    }
    @asort($list);
    if (is_array($list)) {
      foreach ($list as $x => $val) {
        $thisuid=listkeeper_value(variable_get('timesaver_list_id_supervisor_to_employee', 0), $x, 1);
        $userdata=timesaver_get_user_data($thisuid);
        $username=$userdata['emp_name'];
        $output .='<option value="'. $thisuid . '">' . $username . '</option>';
      }
    }
    return $output;
  }

  //based on the passed in UID, we need to determine which users this person can approve timesheets for
  //this will generate an option list without the SELECT tags
  //Timesaver admins see all users
  function get_option_list_of_delegated_employees($supervisor_uid, $suppress_view_all=FALSE) {
    global $_TIMESAVER_CONF;
    $thisuid=intval($supervisor_uid);
    $output="";

    if (user_access('administer Timesaver')) {   //show all users for admins
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 0, '');
    }
    else {
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_delegates', 0), 0, '', '1:' . $thisuid);
    }
    if (!$suppress_view_all) {
      $output .='<option value=""></option><option value="0">View All</option>';
    }
    else {
      $output .='<option value="">'  . t('Select User') . '</option>';
    }
    $listToUse = (user_access('administer Timesaver')) ? variable_get('timesaver_list_id_supervisor_to_employee', 0) : variable_get('timesaver_list_id_delegates', 0);
    @asort($list);
    if (is_array($list)) {
      foreach ($list as $x  =>  $val) {
        $thisuid=listkeeper_value($listToUse, $x, 1);
        $userdata=timesaver_get_user_data($thisuid);
        $username=$userdata['emp_name'];
        $output .='<option value="'. $thisuid . '">' . $username . '</option>';
      }
    }
    return $output;
  }

  //based on the passed in UID, we will determine the hierarchy of this user.
  //we are assuming tht the UID is a manager who has subordinates who in turn have employees
  //this method returns ONLY those employees which are associated with this manager's supervisors....
  function get_option_list_of_hierarchy_employees($manager_uid) {
    global $_TIMESAVER_CONF;
    $thisuid=intval($manager_uid);
    $output="";
    $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_manager_to_supervisor', 0), 0, '', '0:' . $thisuid);
    //now, this list potentially contains the supervisors list.
    //we now take THIS list, cycle through it and generate the option list of assigned employees.
    $output .='<option value="">' . t('Empty') . '</option>';
    if (is_array($list)) {
      foreach ($list as $x => $val) {
        $thisuid=listkeeper_value(variable_get('timesaver_list_id_manager_to_supervisor', 0), $x, 1);
        $userdata=timesaver_get_user_data($thisuid);
        $username=$userdata['emp_name'];
        $output .='<option value="' . $thisuid . '">' . $username . '</option>';
      }
    }
    return $output;
  }


  //this method returns ONLY those employees which are supervisors
  function get_supervisors_drop_down_list() {
    return $this->_tables['VIEW_timesaver_data']->get_supervisors_drop_down_list();
  }

  function get_supervisors_uid_list() {
    return $this->_tables['VIEW_timesaver_data']->get_supervisors_uid_list();
  }

  function get_all_uids_which_have_supervisors() {
    $supuidlist=$this->get_supervisors_uid_list();
    $uidlist='0';
    $suparray=explode(',', $supuidlist);
    foreach ($suparray as $val) {//loop thru each supervisor
      $templist='';
      $templist=$this->get_csv_list_of_assigned_employees($val, TRUE);
      if ($templist!='') {
        if ($uidlist!='') $uidlist .=",";
        $uidlist .=$templist;
      }
    }
    return $uidlist;
  }


  //based on the passed in UID, we need to determine which users this person can approve timesheets for
  //this will generate a csv list
  //Timesaver admins see all users
  function get_csv_list_of_assigned_employees($manager_uid, $manager_uid) { //set isManager to FALSE to signify that this is NOT a supervisor you're trying to get info on
    $thisuid=intval($manager_uid);
    $output="";
    if ( (user_access('administer Timesaver') || user_access('Timesaver finance') ) && !$manager_uid) {   //show all users for admins but NOT if we're trying to only show this for a manager
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 1, '');
    }
    else {
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 1, '', '0:' . $thisuid);
    }
    foreach ($list as $x => $val) {
      $thisuid=listkeeper_value(variable_get('timesaver_list_id_supervisor_to_employee', 0), $x, 1);
      if ($output!='') $output .=",";
      $output .=$thisuid;
    }
    return $output;
  }


  //this method takes in the userid trying to do the approval and the userid of the person they're trying to approve
  //returns TRUE if they can approve or FALSE if they shouldnt be approving
  function test_if_user_can_approve($useriddoingtheapproving, $useridtoapprove) {
    global $_TIMESAVER_CONF, $_USER;
    $useriddoingtheapproving=intval($useriddoingtheapproving);
    if ($useriddoingtheapproving==0) {
      $useriddoingtheapproving=$_USER['uid'];
    }
    $list=listkeeper_option_list('alist', '', 1, 0, '', '0:'. $useridtoapprove . ',1:' . $useriddoingtheapproving);
    if (count($list)<1) {
      $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_delegates', 0), 0, '', '0:'. $useridtoapprove . ',1:' . $useriddoingtheapproving);
    }

    $isTimesaverAdmin=user_access('administer Timesaver', user_load(array('uid' => $useriddoingtheapproving)));
    if (count($list)>0 || $isTimesaverAdmin) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  function get_supervisor_uid($employeeUID) {
    $list=listkeeper_option_list('alist', '', variable_get('timesaver_list_id_supervisor_to_employee', 0), 1, '', '1:' . $employeeUID);
    if (is_array($list)) {
      foreach ($list as $x => $val) {
        $thisuid=listkeeper_value(variable_get('timesaver_list_id_supervisor_to_employee', 0), $val, 1);
        return $thisuid;
      }
    }
  }

  function get_employee_number($uid) {
    return $this->_tables['VIEW_timesaver_data']->get_employee_number($uid);
  }

  function get_user_full_name($uid) {
    $ret=timesaver_get_user_data($uid);
    return $ret['emp_name'];
  }

  //based on the uidDoingApproval, this method will fetch the related timesheet entries
  //that are NOT approved for the specified $uid_getting_approved user.
  //if uidGettingApproved==0, then all current entries that are not approved for
  //the supervisor are shown
  function generate_approval_timesheet_rows($uid_doing_approval, $uid_getting_approved=0, $hide_partially_approved=FALSE, $hide_fully_approved=FALSE, $manager_uid=FALSE) {
    global $user, $base_url;
    $error='';
    $output='';
    $uid_doing_approval=intval($uid_doing_approval);
    if ($uid_doing_approval==0) {
      $uid_doing_approval=$user->uid;
    }
    if ($uid_getting_approved!=0) { //this is a specific user's items getting looked at
      $canApprove=$this->test_if_user_can_approve($uid_doing_approval, $uid_getting_approved);
      if (!$canApprove) {
        $error=t("You're not authorized for approving this user's entries...");
        $output="";
        $retarray=array(0 => $error, 1 => "$output");
        return $retarray;
      }
      $csvListOfUIDs=$uid_getting_approved;
    }
    else {
      $csvListOfUIDs=$this->get_csv_list_of_assigned_employees($uid_doing_approval, $manager_uid);
    }

    $userarr=@implode(",", $csvListOfUIDs);
    if (!is_array($userarr)) $userarr=array($csvListOfUIDs);
    $output="";
    $row=1;

    foreach ($userarr as $userid) {
      $secondsoffset=variable_get('timesaver_approval_history_span', 45);
      $secondsoffset=$secondsoffset*60*60*24;
      $userstdt=time()-$secondsoffset;
      $userenddt=time();
      $userstdt=intval($userstdt);
      $userenddt=intval($userenddt);

      if ($userstdt==0) $userstdt=time()-$secondsoffset;
      if ($userenddt==0) $userenddt=time();

      $userdata=timesaver_get_user_data($uid_getting_approved);
      $fullname=$userdata['emp_name'];
      $output .="<table id=\"userinformation\" class=\"approvalTimesheetTable\"><tr><th colspan=5>" . $fullname . "</th></tr><tr><th>Date From</th><th>Date to</th><th>Approval Info</th><th>Lock</th><th>Info</th></tr>";
      //now loop thru starting at the startdt

      $ret_date_array=$this->generate_sunday_to_sunday_range($userstdt);
      $rptstart=$ret_date_array[0];
      $rptend=$ret_date_array[1];

      while ($rptstart<=$userenddt) {
        $s=strftime("%Y/%m/%d", $rptstart);
        $e=strftime("%Y/%m/%d", $rptend);
        $stats='';
        //get the total # of hours and put that in the stats...
        $stats .=floatval ($this->get_total_hrs_from_start_and_end_date($rptstart, $rptend, $userid)) . " Total hours ";
        $ret=$this->_tables['VIEW_timesaver_data']->check_for_all_approved_in_range($userid, $rptstart, $rptend);
        $tst=$this->determine_if_item_is_in_lock_range_by_date_stamp($rptstart, $userid);

        if (intval($ret[0])===intval($ret[1]) && intval($ret[0])>0 && $tst==TRUE) {  //if (# of items = # approved) and # of items >0
          $approved="<input type=checkbox checked onclick=\"lockItem('{$userid}','{$rptstart}','{$rptend}',this)\">";
          if ($stats!='') $stats .="<br>";
          $stats .=t("All Entered Items approved");
        }
        else {
          if (intval($ret[1])>0) {  //if there's more than one item approved
            //we can use this scenario to trap whether to show this section of the timesheet output or not
            if ($ret[1]!=$ret[0]) {
              $approvalnote=t('Partially Approved');
            }
            else {
              $approvalnote='';
            }

            //RK - keep this in here as a visual marker
            //RK - keep the checkbox enabled to do a blanket "approve all"
            //$T->set_var('is_disabled',' DISABLED ');
            if ($stats!='') $stats .="<br>";
            $stats .="{$ret[1]} " . t("Items approved");
          }
          else {  //catch all
            $approvalnote='';
            if ($stats!='') $stats .="<br>";
            $stats .="{$ret[0]} " . t("Items");
          }
          if ($stats!='') $stats .="<br>";
          $stats .="{$ret[3]} " . t('Hours Approved');
          if (!$tst) {
            $approved="<input type=checkbox onclick=\"lockItem('{$userid}','{$rptstart}','{$rptend}',this)\">";
          }
          else {
            $approved="<input type=checkbox checked onclick=\"lockItem('{$userid}','{$rptstart}','{$rptend}',this)\">";
          }
        }

        if ($row==0) {
          $row=1;
          $rowcolor="oddRow";
        }
        else {
          $row=0;
          $rowcolor="evenRow";
        }

        if ($hide_fully_approved==TRUE || $tst==FALSE) {
          $output .= "<tr class='$rowcolor'><td><a href='{$base_url}/index.php?q=timesaver_approvals&emp={$userid}&start_date={$s}&end_date={$e}&showAsTimesheet=1'>$s</a></td><td>$e</td><td>$stats</td><td>$approved</td><td>$approvalnote</td></tr>";
        }

        $ret_date_array=$this->generate_sunday_to_sunday_range($rptend+864001);

        $rptstart=$ret_date_array[0];
        $rptend=$ret_date_array[1];
      }
    }
    $output .= "</table>";
    $retarray=array(0 => $error, 1 => "$output");
    return $retarray;
  }


  //simple data accessor method that uses an already fetched and referenced data array ($resource)
  //and passes that to another table's fetching mechanism
  //$resource is the fetched array, $arrayOfTables is an array of referenced tables that you'd like to fill with this data
  function set_table_data($resource, $arrayOfTables) {
    foreach ($arrayOfTables as $table) {
      $this->_tables[$table]->generate_table_header(TRUE, $resource);  //skip the automatic fetchNext
    }
  }

  //takes a single timesheet ID in as a parameter and attempts to set its approved flag to 1
  //returns TRUE on success, FALSE on failure
  function approve_single_item($id) {
    return $this->_tables['timesaver_timesheet_entry']->approve_single_item($id);
  }

  //takes a single timesheet ID in as a parameter and attempts to set its approved flag to 1
  //returns TRUE on success, FALSE on failure
  function unapprove_single_item($id) {
    return $this->_tables['timesaver_timesheet_entry']->unapprove_single_item($id);
  }


  //takes a single timesheet ID in as a parameter and attempts to set its rejected flag to 1
  //returns TRUE on success, FALSE on failure
  function reject_single_item($id, $comment) {
    return $this->_tables['timesaver_timesheet_entry']->reject_single_item($id, $comment);
  }

  //takes a single timesheet ID in as a parameter and attempts to set its rejected flag to 1
  //returns TRUE on success, FALSE on failure
  function unreject_single_item($id) {
    return $this->_tables['timesaver_timesheet_entry']->unreject_single_item($id);
  }

  function approve_range($emp, $start, $end) {
    return $this->_tables['timesaver_timesheet_entry']->approve_range($emp, $start, $end);
  }

  function unapprove_range($emp, $start, $end) {
    return $this->_tables['timesaver_timesheet_entry']->unapprove_range($emp, $start, $end);
  }

  //based on your datestamp you pass in, this method will return an array
  //[0] is the start date, [1] is the end
  function generate_sunday_to_sunday_range($datestamp) {
    $retarray=array();
    $stamp=strtotime(variable_get('timesaver_payroll_start_date', '2014/1/6'));
    $flag=TRUE;
    $diff=(86400*variable_get('timesaver_payroll_date_span', 14));
    while ($flag) {
      if ($datestamp>=$stamp || $datestamp >= ($stamp-3600)) {
        $retarray[0]=$stamp;
      }
      if ($datestamp<$stamp && $datestamp < ($stamp-3600)) {
        $stamp=$stamp-86400;
        $retarray[1]=$stamp;
        $flag=FALSE;
      }
      $stamp=$stamp+$diff;
    }
    return $retarray;
  }

  function get_date_stamp_from_id($id) {
    return $this->_tables['timesaver_timesheet_entry']->get_date_stamp_from_id($id);
  }

  function get_uid_from_id($id) {
    return $this->_tables['timesaver_timesheet_entry']->get_uid_from_id($id);
  }

  function get_rejection_reason_by_id($id) {
    return $this->_tables['timesaver_timesheet_entry']->get_rejection_reason_by_id($id);
  }

  //TODO: get the proper ID for this listkeeper option list
  function get_activities_drop_down($selected) {
    $list=listkeeper_option_list('options', '', variable_get('timesaver_list_id_activities', 0), 0, $selected, '', -1, FALSE, 'asc');
    return $list;
  }

  function get_project_drop_down($selected) {
    $selected=intval($selected);
    if ($selected=='0') {
      $list="<option value='0'>" . t('Select Activity') . "</option>";
    }
    else {
      $list=listkeeper_option_list('options', '', variable_get('timesaver_list_id_projects', 0), 1, $selected);
    }
    return $list;
  }

  function get_expanded_project_drop_down() {
    $list=listkeeper_option_list('options', '', variable_get('timesaver_list_id_projects', 0), 1, FALSE, '', -1, TRUE, 'asc');
    return $list;
  }

  function get_project_drop_down_from_activity_id($id, $selected = FALSE) {
    $id=intval($id);
    if ($id=='0') {
      $list="<option value='0'>" . t('Select Activity') . "</option>";
    }
    else {
      $list=listkeeper_option_list('options', '', variable_get('timesaver_list_id_projects', 0), 1, $selected, '0:' . $id, -1, FALSE, 'asc');
    }
    return $list;
  }

  function get_task_drop_down_from_activity_id($id, $selected = FALSE) {
    $id=intval($id);
    if ($id=='0') {
      $list="<option value='0'>" . t('Select Activity') . "</option>";
    }
    else {
      $list=listkeeper_option_list('options', '', variable_get('timesaver_list_id_tasks', 0), 1, $selected, '0:' . $id, -1, FALSE, 'asc');
    }
    return $list;
  }


  function get_tasks_drop_down($selected, $no_default=FALSE, $mode='options') {
    $list=listkeeper_option_list($mode, '', variable_get('timesaver_list_id_tasks', 0), 1, $selected, '', -1, $no_default, 'asc');
    return $list;
  }

  function get_total_hrs_from_id($id) {
    return  $this->_tables['timesaver_timesheet_entry']->get_total_hrs_from_id($id);
  }

  function get_ot_hrs_from_id($id) {
    return  $this->_tables['timesaver_timesheet_entry']->get_ot_hrs_from_id($id);
  }

  function select_by_project_hours_by_user($uid, $start_stamp, $end_stamp) {
    return $this->_tables['timesaver_timesheet_entry']->select_by_project_hours_by_user($uid, $start_stamp, $end_stamp);
  }

  function get_total_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    return $this->_tables['timesaver_timesheet_entry']->get_total_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid);
  }

  function get_regular_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    return $this->_tables['timesaver_timesheet_entry']->get_regular_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid);
  }

  function get_ot_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid) {
    return $this->_tables['timesaver_timesheet_entry']->get_ot_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid);
  }

  //returns all rejected IDs in an array
  function get_rejected_items($uid) {
    return $this->_tables['timesaver_timesheet_entry']->get_rejected_items($uid);
  }

  //returns all rejected IDs in an array
  function get_items_submitted_by_someone_else($uid) {
    return $this->_tables['timesaver_timesheet_entry']->get_items_submitted_by_someone_else($uid);
  }

  //returns TRUE if the id's datestamp falls in the lock range
  //return FALSE otherwise
  function determine_if_item_is_in_lock_range($id) {
    $retval=$this->_tables['timesaver_locked_timesheets']->determine_if_item_is_in_lock_range($id);
    return $retval;
  }

  //returns TRUE if the id's datestamp falls in the lock range
  //return FALSE otherwise
  function determine_if_item_is_in_lock_range_by_date_stamp($dateStamp, $which_user) {
    $retval=$this->_tables['timesaver_locked_timesheets']->determine_if_item_is_in_lock_range_by_date_stamp($dateStamp, $which_user);
    return $retval;
  }

  function lock_range($start_date_stamp, $end_date_stamp, $uid) {
    //first, we check to see if the range that has been asked to be locked has >=75 hrs worked
    $ret=$this->get_total_hrs_from_start_and_end_date($start_date_stamp, $end_date_stamp, $uid);
    if ($ret>=0) {
      $start_date_stamp=$start_date_stamp-3600;
      $end_date_stamp=$end_date_stamp+3600;
      return $this->_tables['timesaver_locked_timesheets']->lock_timesheet($start_date_stamp, $end_date_stamp, $uid);
    }
    else {
      return -1;
    }
  }

  function unlock_range($start_date_stamp, $end_date_stamp, $uid) {
    return $this->_tables['timesaver_locked_timesheets']->unlock_timesheet($start_date_stamp, $end_date_stamp, $uid);
  }

  function set_acknowledged_modified($start_stamp, $end_stamp, $uid) {
    return $this->_tables['timesaver_timesheet_entry']->set_acknowledged_modified($start_stamp, $end_stamp, $uid);
  }

  //simple unit test for data table creation.
  function test_table() {
    echo "Number of tables: {$this->_number_of_tables}<HR>";
    foreach ($this->_tables as $table) {
      echo "<b>Table: {$table->tablename}</b><br>" ;
      foreach ($table->fieldlist as $x) {
        echo "$x - value: ";
        echo $table->fieldvals[$x];
        echo "<BR>";
      }
      echo "<BR>";
    }//end for

  }


}
