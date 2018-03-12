<?php


/**
 * @file
 * Timesaver ajax include file.
 *
 * Provides the ajax functionality for Timesaver!
 */

$op=$_REQUEST['op'];
switch($op) {
  case 'savetimesheet':
    $max_row_number=intval($_POST['max_row_number']);
    $approval_uid=intval($_POST['approved_by']);
    $for_which_uid=intval($_POST['emp']);

    $ts=new Timesaver();
    for ($cntr=0; $cntr<$max_row_number; $cntr++) {
      $ts->set_data_from_post($cntr);
      if ($for_which_uid==0) {
        $ret=$ts->commit_data($approval_uid, $user->uid);
      }else {
        $ret=$ts->commit_data($approval_uid, $for_which_uid);
      }
    }
    if ($ret) {
      $error=t('Successfully saved your timesheet... ');
      if ( ($for_which_uid==0) || ($for_which_uid==$user->uid) ) {
        $output=nxtime_generate_timesheet();
      }
      else {
        $output=nxtime_generate_timesheet($for_which_uid, FALSE, TRUE, $for_which_uid);
      }
    }
    else {
      $error=t('There is an error in your timesheet - ensure all numeric fields are filled with numeric data!...');
      $output='';
    }
    break;

  case 'delete_entries': //delete timesheet entries
    $list=check_plain($_REQUEST['list']);
    $approval_uid=intval($_REQUEST['approved_by']);
    $for_which_uid=intval($_REQUEST['emp']);
    $ts= new Timesaver();
    $ret=$ts->delete_entries($list);
    if ( ($for_which_uid==0) || ($for_which_uid==$user->uid) ) {
      $output=nxtime_generate_timesheet();
    }
    else {
      $output=nxtime_generate_timesheet($for_which_uid, FALSE, TRUE, $for_which_uid);
    }

    if ($ret) {
      $error=t('Successfully deleted your selected items...');
    }
    else {
      $error=t('Some of your items could not be deleted as they were approved or rejected...');
    }
    break;


  case 'lockentries':
    $datestamp=check_plain($_REQUEST['datestamp']);
    $emp=intval($_REQUEST['emp']);
    $ts=new Timesaver();
    $ts->lock_timesheet_entries($datestamp, $emp);
    if ( $emp==$user->uid ) {
      $output=nxtime_generate_timesheet($emp);
    }
    else {
      $output=nxtime_generate_timesheet($user->uid,FALSE,TRUE, $emp);
    }
    $error=t('Successfully locked your timesheet entry...');
    break;

  case 'unlockentries':
    $datestamp=check_plain($_REQUEST['datestamp']);
    $emp=intval($_REQUEST['emp']);
    $ts=new Timesaver();
    $ts->unlock_timesheet_entries($datestamp, $emp);
    if ( $emp==$user->uid ) {
      $output=nxtime_generate_timesheet($emp);
    }
    else {
      $output=nxtime_generate_timesheet($user->uid, FALSE, TRUE, $emp);
    }
    $error=t('Successfully UN-locked your timesheet entry...');
    break;

  case 'approveitem':
    $id=intval($_REQUEST['id']);
    $userid=intval($_REQUEST['uid']);
    $ts=new Timesaver();
    $ret=$ts->approve_single_item($id);
    if ($ret) {
      $error=t('Successfully approved the item...');
      $output=nxtime_generate_timesheet($userid, FALSE, TRUE);
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'unapproveitem':
    $id=intval($_REQUEST['id']);
    $userid=intval($_REQUEST['uid']);
    $ts=new Timesaver();
    $ret=$ts->unapprove_single_item($id);
    if ($ret) {
      $error=t('Successfully un-approved the item...');
      $output=nxtime_generate_timesheet($userid, FALSE, TRUE);
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'rejectitem':
    $id=intval($_REQUEST['id']);
    $comment=check_plain($_REQUEST['comment']);
    $userid=intval($_REQUEST['uid']);
    $ts=new Timesaver();
    $ret=$ts->reject_single_item($id, $comment);
    if ($ret) {
      $error=t('Successfully rejected the item... ');
      $output=nxtime_generate_timesheet($userid, FALSE, TRUE);
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'unrejectitem':
    $id=intval($_REQUEST['id']);
    $userid=intval($_REQUEST['uid']);
    $ts=new Timesaver();
    $ret=$ts->unreject_single_item($id);
    if ($ret) {
      $error=t('Successfully un-rejected the item...');
      $output=nxtime_generate_timesheet($userid, TRUE, TRUE);
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'approve_range':
    $emp=intval($_REQUEST['emp']);
    $start=check_plain($_REQUEST['start']);
    $end=check_plain($_REQUEST['end']);
    $ts=new Timesaver();
    $ret=$ts->approve_range($emp, $start, $end);
    if ($ret) {
      $error=t('Successfully approved the range of dates...');
      $ret=$ts->generate_approval_timesheet_rows($user->uid, $emp);
      $output=$ret[1];
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'unapprove_range':
    $emp=intval($_REQUEST['emp']);
    $start=check_plain($_REQUEST['start']);
    $end=check_plain($_REQUEST['end']);
    $ts=new Timesaver();
    $ret=$ts->unapprove_range($emp, $start, $end);
    if ($ret) {
      $error=t('Successfully un-approved the item...');
      $ret=$ts->generate_approval_timesheet_rows($user->uid, $emp);
      $output=$ret[1];
    }
    else {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'approveallchecked':
    $idlist=check_plain($_REQUEST['allids']);
    $postedemp=intval($_REQUEST['emp']);
    if ($idlist=='') {
      $error=t('You have not chosen any items...');
      $output='';
    }else {
      $ts=new Timesaver();
      $arr=explode(",", $idlist);
      $retval=TRUE;
      $thisval='';
      foreach ($arr as $id) {
        $thisval=($_REQUEST['approve'.$id]);
        //using this ID, we need to determine the date range it fits in...
        $dtarray=$ts->generate_sunday_to_sunday_range($ts->get_date_stamp_from_id($id));
        if ($thisval!='') {
          $emp=$ts->get_uid_from_id($id);
          $ret=$ts->approve_range($emp, $dtarray[0], $dtarray[1]);
          $retval=$retval&$ret;
        }
        $thisval='';
      }
      if ($retval) {
        $error=t('Successfully approved all of the items you chose...');
        $output='';
        $ret=$ts->generate_approval_timesheet_rows($user->uid, $postedemp);
        $output=$ret[1];
      }
      else {
        $error=t('Error! Database Error thrown.');
        $output='';
      }
    }
    break;

  case 'getrejectionreason':
    $id=intval($_REQUEST['id']);
    $ts=new Timesaver();
    $output=$ts->get_rejection_reason_by_id($id);
    $error='';
    break;

  case 'clearreject':
    $id=intval($_REQUEST['id']);
    $userid=intval($_REQUEST['uid']);
    $ts=new Timesaver();
    $ts->unreject_single_item($id);
    $output=nxtime_generate_timesheet($userid);
    $error='';
    break;

  case 'saveapprovaltimesheet':
    $max_row_number=intval($_POST['max_row_number']);
    $approval_uid=intval($_POST['approved_by']);
    $for_which_uid=intval($_POST['emp']);
    $ts=new Timesaver();
    for ($cntr=0; $cntr<$max_row_number; $cntr++) {
      $chk=($_POST['chkapproval'.$cntr]);
      $rejectchk=($_POST['chkreject'.$cntr]);
      $id=($_POST['id'.$cntr]);
      if ($rejectchk==0) {
        if ($chk==0) {
          $ret=$ts->unapprove_single_item($id);
        }
        else {
          $ret=$ts->approve_single_item($id);
        }
      }
    }
    if ($ret) {
      $error=t('Successfully saved your timesheet... ');
      if ( ($for_which_uid==0) || ($for_which_uid==$user->uid) ) {
        $output=nxtime_generate_timesheet($for_which_uid, FALSE, TRUE, $for_which_uid);
      }
      else {
        $output=nxtime_generate_timesheet($for_which_uid, FALSE, TRUE, $for_which_uid);
      }
    }
    else {
      $error=t('There is an error in your timesheet - ensure all numeric fields are filled with numeric data!...');
      $output='';
    }
    break;

  case 'lock_range':
    $emp=intval($_REQUEST['emp']);
    $start=check_plain($_REQUEST['start']);
    $end=check_plain($_REQUEST['end']);
    $ts=new Timesaver();
    $ret=$ts->lock_range($start, $end, $emp);
    if ($ret>0) {
      $error=t('Successfully locked the range of dates...');
      $ret=$ts->generate_approval_timesheet_rows($user->uid, $emp);
      $output=$ret[1];
    }
    elseif ($ret===FALSE) {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    else {
      $error=t('Sorry, you cannot lock a timesheet when it has less than 80 booked hours.');
      $output='';
    }
    break;

  case 'unlock_range':
    $emp=intval($_REQUEST['emp']);
    $start=check_plain($_REQUEST['start']);
    $end=check_plain($_REQUEST['end']);
    $ts = new Timesaver();
    $ret = $ts->unlock_range($start, $end, $emp);
    if ($ret>0) {
      $error=t('Successfully UN-locked the range of dates...');
      $ret=$ts->generate_approval_timesheet_rows($user->uid, $emp);
      $output=$ret[1];
    }
    elseif ($ret===FALSE) {
      $error=t('Error! Database Error thrown.');
      $output='';
    }
    break;

  case 'getproject':
    $id=intval($_REQUEST['id']);
    $row=intval($_REQUEST['row']);
    $ts=new Timesaver();
    $output=$ts->get_project_drop_down_from_activity_id($id);
    $output ='<select name="project_id' . $row . '" id="project_id' . $row . '" onchange="changeflag()" class="dropdown_menus">' . $output . '</select>';
    $error='';
    break;

  case 'gettask':
    $id=intval($_REQUEST['id']);
    $row=intval($_REQUEST['row']);
    $ts=new Timesaver();
    $output=$ts->get_task_drop_down_from_activity_id($id);
    $output ='<select name="task_id' . $row . '" id="task_id' . $row . '" onchange="changeflag()" class="dropdown_menus">' . $output . '</select>';
    $error='';
    break;

  case 'getsundaytosunday':
    $date=check_plain($_REQUEST['date']);
    $datestamp=strtotime($date);
    $ts=new Timesaver();
    $ret_date_array=$ts->generate_sunday_to_sunday_range($datestamp);
    $start=date("Y/m/d", $ret_date_array[0]);
    $end=date("Y/m/d", $ret_date_array[1]);
    $output="$start, $end";
    $error='';
    break;

  case 'lock_range':
    $emp=($_REQUEST['emp']);
    $start=($_REQUEST['start']);
    $end=($_REQUEST['end']);
    $ts=new Timesaver();
    $ret=$ts->lock_range($start, $end, $emp);
    if ($ret>0) {
      $error='Successfully locked the range of dates...';
      $ret=$ts->generate_approval_timesheet_rows($_USER['uid'], $emp);
      $output=$ret[1];
    }
    elseif ($ret===FALSE) {
      $error='Error! Database Error thrown.';
      $output='';
    }
    else {
      $error='Sorry, you cannot lock a timesheet when it has less than 80 booked hours.';
      $output='';
    }
    break;

  case 'unlock_range':
    $emp=intval($_REQUEST['emp']);
    $start=check_plain($_REQUEST['start']);
    $end=check_plain($_REQUEST['end']);
    $ts = new Timesaver();
    $ret = $ts->unlock_range($start, $end, $emp);
    if ($ret>0) {
      $error='Successfully UN-locked the range of dates...';
      $ret=$ts->generate_approval_timesheet_rows($_USER['uid'], $emp);
      $output=$ret[1];
    }
    elseif ($ret===FALSE) {
      $error='Error! Database Error thrown.';
      $output='';
    }
    break;

  case 'ackmodified':
    $start=check_plain($_REQUEST['startstamp']);
    $end=check_plain($_REQUEST['endstamp']);
    $ts=new Timesaver();
    $ret=$ts->set_acknowledged_modified($start, $end, $_USER['uid']);
    $output='';
    if ($ret) {
      $error='';
    }
    else {
      $error='Error! Database Error thrown.';
    }
    break;
}//end switch

$output=htmlentities($output);
$error=htmlentities($error);
$op=htmlentities($op);

$retval = "<result>";
$retval .= "<error>$error</error>";
$retval .= "<op>$op</op>";
$retval .= "<output>$output</output>";
$retval .= "</result>";

header("Cache-Control: no-store, no-cache, must-revalidate");
header("content-type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
echo $retval;